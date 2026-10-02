<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public static function middleware(): array
    {
        return ['auth', 'admin'];
    }

    public function index(Request $request)
    {
        $query = Order::with('user');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('order_code', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }
        $orders = $query->latest()->paginate(15)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'     => 'required|in:menunggu_pembayaran,menunggu_verifikasi,diproses,dikirim,selesai,dibatalkan',
            'admin_note' => 'nullable|string|max:1000',
        ]);
        Order::findOrFail($id)->update([
            'status'     => $request->status,
            'admin_note' => $request->admin_note,
        ]);
        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}