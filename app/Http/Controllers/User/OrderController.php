<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public static function middleware(): array
    {
        return ['auth'];
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');

        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $user  = Auth::user();
        $paymentMethods = \App\Models\PaymentMethod::all()->groupBy('type');

        $jabodetabekCities = [
            'Jakarta Pusat', 'Jakarta Utara', 'Jakarta Barat', 'Jakarta Selatan', 'Jakarta Timur',
            'Kota Bogor', 'Kabupaten Bogor', 'Depok',
            'Kota Tangerang', 'Tangerang Selatan', 'Kabupaten Tangerang',
            'Kota Bekasi', 'Kabupaten Bekasi',
        ];

        return view('user.orders.checkout', compact('cart', 'total', 'user', 'paymentMethods', 'jabodetabekCities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone'            => 'required|string|max:20',
            'city'             => 'required|string|max:100',
            'shipping_address' => 'required|string|max:500',
            'payment_method'   => 'required|in:transfer_bank,dompet_digital,virtual_account',
            'notes'            => 'nullable|string|max:500',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');

        DB::beginTransaction();
        try {
            foreach ($cart as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);
                if (!$product || $product->stock < $item['quantity']) {
                    DB::rollBack();
                    return back()->with('error', 'Stok "' . $item['name'] . '" tidak mencukupi.');
                }
            }

            $subtotal     = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
            $shippingCost = $this->calculateShipping($request->city);
            $totalAmount  = $subtotal + $shippingCost;

            $order = Order::create([
                'user_id'          => Auth::id(),
                'order_code'       => 'ORD-' . strtoupper(Str::random(8)),
                'status'           => 'menunggu_pembayaran',
                'phone'            => $request->phone,
                'city'             => $request->city,
                'shipping_address' => $request->shipping_address,
                'notes'            => $request->notes,
                'shipping_cost'    => $shippingCost,
                'payment_method'   => $request->payment_method,
                'total_amount'     => $totalAmount,
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item['product_id'],
                    'product_name' => $item['name'],
                    'price'        => $item['price'],
                    'quantity'     => $item['quantity'],
                    'subtotal'     => $item['price'] * $item['quantity'],
                ]);
                Product::where('id', $item['product_id'])->decrement('stock', $item['quantity']);
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('orders.show', $order->order_code)
                ->with('success', 'Pesanan berhasil! No: ' . $order->order_code);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->with('items')->latest()->paginate(10);
        return view('user.orders.index', compact('orders'));
    }

    public function show($orderCode)
    {
        $order = Order::with('items.product')
            ->where('order_code', $orderCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $paymentMethods = \App\Models\PaymentMethod::where('type', $order->payment_method)->get();

        return view('user.orders.show', compact('order', 'paymentMethods'));
    }

    public function uploadProof(Request $request, $orderCode)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $order = Order::where('order_code', $orderCode)
            ->where('user_id', Auth::id())
            ->where('status', 'menunggu_pembayaran')
            ->firstOrFail();

        $file     = $request->file('payment_proof');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Document root domain ini adalah /htdocs (bukan /htdocs/laravel/public),
        // jadi file harus disimpan satu folder di atas base_path() Laravel,
        // bukan pakai public_path() yang menunjuk ke /htdocs/laravel/public.
        $destinationFolder = base_path('../uploads/payment_proofs');
        if (!is_dir($destinationFolder)) {
            mkdir($destinationFolder, 0755, true);
        }
        $file->move($destinationFolder, $filename);

        $order->update([
            'payment_proof' => 'uploads/payment_proofs/' . $filename,
            'status'        => 'menunggu_verifikasi',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.');
    }

    public function cancel($orderCode)
    {
        $order = Order::where('order_code', $orderCode)->where('user_id', Auth::id())->firstOrFail();

        if (!in_array($order->status, ['menunggu_pembayaran', 'diproses'])) {
            return back()->with('error', 'Pesanan tidak bisa dibatalkan.');
        }

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)->increment('stock', $item->quantity);
            }
            $order->update(['status' => 'dibatalkan']);
        });

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function confirm($orderCode)
    {
        $order = Order::where('order_code', $orderCode)
            ->where('user_id', Auth::id())
            ->where('status', 'dikirim')
            ->firstOrFail();

        $order->update(['status' => 'selesai']);
        return back()->with('success', 'Pesanan dikonfirmasi diterima. Terima kasih!');
    }

    private function calculateShipping(string $city): int
    {
        $jabodetabek = [
            'jakarta pusat', 'jakarta utara', 'jakarta barat', 'jakarta selatan', 'jakarta timur',
            'kota bogor', 'kabupaten bogor', 'depok',
            'kota tangerang', 'tangerang selatan', 'kabupaten tangerang',
            'kota bekasi', 'kabupaten bekasi',
        ];

        return in_array(strtolower(trim($city)), $jabodetabek) ? 0 : 15000;
    }
}