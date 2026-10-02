<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public static function middleware(): array
    {
        return ['auth', 'admin'];
    }

    public function index()
    {
        $stats = [
            'total_orders'   => Order::count(),
            'pending_orders' => Order::where('status', 'menunggu_pembayaran')->count(),
            'total_products' => Product::count(),
            'total_users'    => User::where('role', 'user')->count(),
            'total_revenue'  => Order::where('status', 'selesai')->sum('total_amount'),
            'revenue_today'  => Order::where('status', 'selesai')->whereDate('created_at', today())->sum('total_amount'),
        ];

        $recentOrders     = Order::with('user')->latest()->take(5)->get();
        $lowStockProducts = Product::where('stock', '<=', 5)->where('is_active', true)->take(5)->get();
        $salesChart       = Order::where('status', 'selesai')
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue, COUNT(*) as orders')
            ->groupBy('date')->orderBy('date')->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStockProducts', 'salesChart'));
    }
}