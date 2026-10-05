<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductVariantController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validatedVariant($request);
        $product->variants()->create($data);

        return back()->with('success', 'Size added.');
    }

    public function update(Request $request, Product $product, ProductVariant $variant): RedirectResponse
    {
        abort_unless($variant->product_id === $product->id, 404);
        $variant->update($this->validatedVariant($request, $variant));

        return back()->with('success', 'Size updated.');
    }

    public function destroy(Product $product, ProductVariant $variant): RedirectResponse
    {
        abort_unless($variant->product_id === $product->id, 404);
        abort_if($product->variants()->count() <= 1, 422, 'A product needs at least one size.');
        $variant->delete();

        return back()->with('success', 'Size deleted.');
    }

    /** @return array<string, mixed> */
    private function validatedVariant(Request $request, ?ProductVariant $variant = null): array
    {
        $data = $request->validate([
            'sku' => ['required', 'string', 'max:100', Rule::unique('product_variants', 'sku')->ignore($variant?->id)],
            'size' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'sale_price' => ['nullable', 'numeric', 'min:0.01', 'lte:price'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
        ]);
        $data['price_cents'] = (int) round($data['price'] * 100);
        $data['sale_price_cents'] = isset($data['sale_price']) ? (int) round($data['sale_price'] * 100) : null;
        $data['is_active'] = $request->boolean('is_active');
        unset($data['price'], $data['sale_price']);

        return $data;
    }
}
