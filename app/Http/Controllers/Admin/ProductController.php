<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public static function middleware(): array
    {
        return ['auth', 'admin'];
    }

    public function index(Request $request)
    {
        $query = Product::with('category');
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        $products   = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::all();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'images'      => 'nullable|array|min:1',
            'images.*'    => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data         = $request->except(['images']);
        $data['slug'] = Str::slug($request->name) . '-' . Str::random(5);

        $product = Product::create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/products'), $filename);
                $path = 'products/' . $filename;

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'sort_order' => $index,
                    'is_primary' => $index === 0,
                ]);
            }
            // Set kolom image lama ke gambar pertama
            $product->update(['image' => $product->images()->first()->image_path]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $product    = Product::with('images')->findOrFail($id);
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::with('images')->findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'images'      => 'nullable|array',
            'images.*'    => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except(['images', 'delete_images']);
        $product->update($data);

        // Hapus gambar yang dicentang untuk dihapus
        if ($request->filled('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $img = ProductImage::find($imageId);
                if ($img) {
                    $fullPath = public_path('uploads/' . $img->image_path);
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                    }
                    $img->delete();
                }
            }
        }

        // Upload gambar baru
        if ($request->hasFile('images')) {
            $lastOrder = $product->images()->max('sort_order') ?? -1;
            foreach ($request->file('images') as $index => $file) {
                $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/products'), $filename);
                $path = 'products/' . $filename;

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'sort_order' => $lastOrder + $index + 1,
                    'is_primary' => $product->images()->count() === 0 && $index === 0,
                ]);
            }
        }

        // Update kolom image lama ke gambar primary/pertama
        $firstImage = $product->images()->orderBy('sort_order')->first();
        if ($firstImage) {
            $product->update(['image' => $firstImage->image_path]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $product = Product::with('images')->findOrFail($id);
        foreach ($product->images as $img) {
            $fullPath = public_path('uploads/' . $img->image_path);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
        if ($product->image) {
            $fullPath = public_path('uploads/' . $product->image);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
        $product->delete();
        return back()->with('success', 'Produk berhasil dihapus.');
    }
}