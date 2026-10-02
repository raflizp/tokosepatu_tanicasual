<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function home()
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        return view('welcome', compact('products'));
    }

    public function index(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }
        if ($request->filled('search')) {
            $query->where(fn($q) => $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%'));
        }
        if ($request->filled('min_price')) $query->where('price', '>=', $request->min_price);
        if ($request->filled('max_price')) $query->where('price', '<=', $request->max_price);

        match ($request->get('sort', 'latest')) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'popular'    => $query->orderBy('sold_count', 'desc'),
            default      => $query->latest(),
        };

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('user.products.index', compact('products', 'categories'));
    }

    public function show($slug)
{
    $product = Product::with(['category', 'images'])
        ->where('slug', $slug)->where('is_active', true)->firstOrFail();

    $relatedProducts = Product::with('images')
        ->where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)->where('is_active', true)->take(4)->get();

    return view('user.products.show', compact('product', 'relatedProducts'));
}

    public function byCategory($slug)
    {
        $category = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $products = Product::with('category')
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::where('is_active', true)->get();

        return view('user.products.index', compact('products', 'categories'));
    }

    public function search(Request $request)
    {
        $products = Product::where('is_active', true)
            ->where('name', 'like', '%' . $request->get('q') . '%')
            ->take(8)->get(['id', 'name', 'slug', 'price', 'image']);
        return response()->json($products);
    }
}