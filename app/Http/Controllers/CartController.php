<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $items = $request->user()->cartItems()->with('variant.product')->get();

        return view('user.cart', [
            'items' => $items,
            'subtotal' => $items->sum(fn (CartItem $item) => $item->variant->effectivePriceCents() * $item->quantity),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        DB::transaction(function () use ($request, $validated): void {
            $variant = ProductVariant::query()
                ->with('product')
                ->lockForUpdate()
                ->findOrFail($validated['product_variant_id']);
            abort_unless($variant->is_active && $variant->product->is_published, 404);
            $item = $request->user()
                ->cartItems()
                ->firstOrNew(['product_variant_id' => $variant->id]);
            $quantity = ($item->exists ? $item->quantity : 0) + $validated['quantity'];

            if ($quantity > $variant->stock_quantity) {
                throw ValidationException::withMessages([
                    'quantity' => 'Only '.$variant->stock_quantity.' available in this size.',
                ]);
            }

            $item->quantity = $quantity;
            $item->save();
        });

        return redirect()->route('user.cart.index')->with('success', 'Added to your bag.');
    }

    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        abort_unless($cartItem->user_id === $request->user()->id, 404);
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);
        $cartItem->load('variant');

        if ($validated['quantity'] > $cartItem->variant->stock_quantity) {
            throw ValidationException::withMessages([
                'quantity' => 'Only '.$cartItem->variant->stock_quantity.' available in this size.',
            ]);
        }

        $cartItem->update($validated);

        return back()->with('success', 'Bag updated.');
    }

    public function destroy(Request $request, CartItem $cartItem): RedirectResponse
    {
        abort_unless($cartItem->user_id === $request->user()->id, 404);
        $cartItem->delete();

        return back()->with('success', 'Item removed.');
    }
}
