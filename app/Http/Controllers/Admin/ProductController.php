<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with(['category', 'variants'])
            ->latest()
            ->paginate(20);

        return view('admin.products.index', ['products' => $products]);
    }

    public function create(): View
    {
        return view('admin.products.form', [
            'product' => new Product,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedProduct($request);
        $variant = $request->validate([
            'sku' => ['required', 'string', 'max:100', 'unique:product_variants,sku'],
            'size' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'sale_price' => ['nullable', 'numeric', 'min:0.01', 'lte:price'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
        ]);

        if ($request->hasFile('image_upload')) {
            $imagePath = $request->file('image_upload')?->storePublicly('products', 'public');

            if (! is_string($imagePath)) {
                return back()->withErrors(['image_upload' => 'The product image could not be stored.'])->withInput();
            }

            $data['image_path'] = $imagePath;
        }

        $product = DB::transaction(function () use ($data, $variant): Product {
            $product = Product::create($data);
            $product->variants()->create([
                'sku' => $variant['sku'],
                'size' => $variant['size'],
                'price_cents' => (int) round($variant['price'] * 100),
                'sale_price_cents' => isset($variant['sale_price'])
                    ? (int) round($variant['sale_price'] * 100)
                    : null,
                'stock_quantity' => $variant['stock_quantity'],
            ]);

            return $product;
        });

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Product created. Add more sizes below.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', [
            'product' => $product->load('variants'),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validatedProduct($request, $product);
        $previousImagePath = $product->image_path;
        $previousImageWasUploaded = $product->usesUploadedImage();

        if ($request->hasFile('image_upload')) {
            $imagePath = $request->file('image_upload')?->storePublicly('products', 'public');

            if (! is_string($imagePath)) {
                return back()->withErrors(['image_upload' => 'The product image could not be stored.'])->withInput();
            }

            $data['image_path'] = $imagePath;
        }

        $product->update($data);

        if ($data['image_path'] !== $previousImagePath && $previousImageWasUploaded && is_string($previousImagePath)) {
            Storage::disk('public')->delete($previousImagePath);
        }

        return back()->with('success', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $imagePath = $product->image_path;
        $imageWasUploaded = $product->usesUploadedImage();

        $product->delete();

        if ($imageWasUploaded && is_string($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    /** @return array<string, mixed> */
    private function validatedProduct(Request $request, ?Product $product = null): array
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('products', 'name')->ignore($product?->id),
            ],
            'brand' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:5000'],
            'style' => ['nullable', 'string', 'max:255'],
            'opening_smell' => ['nullable', 'string', 'max:1000'],
            'main_vibe' => ['nullable', 'string', 'max:1000'],
            'character' => ['nullable', 'string', 'max:1000'],
            'overall_smell' => ['nullable', 'string', 'max:1000'],
            'best_seasons' => ['nullable', 'string', 'max:255'],
            'use_cases' => ['nullable', 'string', 'max:255'],
            'longevity' => ['nullable', 'string', 'max:255'],
            'projection' => ['nullable', 'string', 'max:500'],
            'image_path' => ['nullable', 'url', 'starts_with:http://,https://', 'max:2048'],
            'image_upload' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image_alt' => ['nullable', 'string', 'max:255'],
            'gallery_urls' => ['nullable', 'string', 'max:10000'],
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_published'] = $request->boolean('is_published');
        $data['image_path'] = ($data['image_path'] ?? null)
            ?: ($product?->image_path ?? '/images/perfume.svg');

        $data['image_alt'] = ($data['image_alt'] ?? null)
            ?: $data['name'].' perfume bottle';
        $galleryUrls = preg_split('/\r\n|\r|\n/', $data['gallery_urls'] ?? '');
        $data['gallery'] = collect($galleryUrls)
            ->map(fn ($url) => trim($url))
            ->filter(fn ($url) => filter_var($url, FILTER_VALIDATE_URL))
            ->values()
            ->all();
        unset($data['gallery_urls'], $data['image_upload']);

        return $data;
    }
}
