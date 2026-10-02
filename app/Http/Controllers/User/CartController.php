<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getCart(): array { return session()->get('cart', []); }
    private function saveCart(array $cart): void { session()->put('cart', $cart); }

    public function index()
    {
        $cart  = $this->getCart();
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        return view('user.cart.index', compact('cart', 'total')); // ← fix
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1|max:100',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->is_active) return back()->with('error', 'Produk tidak tersedia.');
        if ($product->stock < $request->quantity) return back()->with('error', 'Stok tidak mencukupi.');

        $cart = $this->getCart();
        $key  = $product->id;

        if (isset($cart[$key])) {
            $newQty = $cart[$key]['quantity'] + $request->quantity;
            if ($newQty > $product->stock) return back()->with('error', 'Jumlah melebihi stok.');
            $cart[$key]['quantity'] = $newQty;
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'name'       => $product->name,
                'slug'       => $product->slug,
                'price'      => $product->price,
                'image'      => $product->image,
                'quantity'   => $request->quantity,
            ];
        }

        $this->saveCart($cart);
        return back()->with('success', $product->name . ' ditambahkan ke keranjang.');
    }

    public function update(Request $request, $productId)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:100']);
        $cart = $this->getCart();

        if (!isset($cart[$productId])) return back()->with('error', 'Produk tidak ditemukan.');

        $product = Product::find($productId);
        if ($product && $request->quantity > $product->stock) {
            return back()->with('error', 'Jumlah melebihi stok (' . $product->stock . ').');
        }

        $cart[$productId]['quantity'] = $request->quantity;
        $this->saveCart($cart);

        if ($request->ajax()) {
            return response()->json([
                'success'  => true,
                'subtotal' => $cart[$productId]['price'] * $cart[$productId]['quantity'],
                'total'    => collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']),
                'count'    => count($cart),
            ]);
        }

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove($productId)
    {
        $cart = $this->getCart();
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            $this->saveCart($cart);
            return back()->with('success', 'Produk dihapus dari keranjang.');
        }
        return back()->with('error', 'Produk tidak ditemukan.');
    }

    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Keranjang dikosongkan.');
    }

    public function count()
    {
        $cart = $this->getCart();
        return response()->json([
            'count' => count($cart),
            'total' => collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']),
        ]);
    }
}