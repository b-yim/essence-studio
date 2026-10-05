<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        $items = $request->user()->cartItems()
            ->with('variant.product')
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('user.cart.index');
        }

        return view('user.checkout', [
            'items' => $items,
            'subtotal' => $items->sum(
                fn (CartItem $item) => $item->variant->effectivePriceCents() * $item->quantity,
            ),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $shipping = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'shipping_address' => ['required', 'string', 'max:2000'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $order = DB::transaction(function () use ($request, $shipping): Order {
            $cartItems = $request->user()->cartItems()
                ->with('variant.product')
                ->lockForUpdate()
                ->get();

            if ($cartItems->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => 'Your bag is empty.',
                ]);
            }

            $lines = [];
            $totalCents = 0;

            foreach ($cartItems as $item) {
                $variant = ProductVariant::query()
                    ->with('product')
                    ->lockForUpdate()
                    ->findOrFail($item->product_variant_id);

                if (! $variant->is_active || ! $variant->product->is_published) {
                    throw ValidationException::withMessages([
                        'cart' => $variant->product->name.' is no longer available.',
                    ]);
                }

                if ($item->quantity > $variant->stock_quantity) {
                    throw ValidationException::withMessages([
                        'cart' => 'Only '.$variant->stock_quantity.' of '.$variant->product->name.' is available.',
                    ]);
                }

                $unitPriceCents = $variant->effectivePriceCents();
                $lineTotalCents = $unitPriceCents * $item->quantity;
                $totalCents += $lineTotalCents;

                $lines[] = [
                    'product_variant_id' => $variant->id,
                    'product_name' => $variant->product->name,
                    'sku' => $variant->sku,
                    'size' => $variant->size,
                    'unit_price_cents' => $unitPriceCents,
                    'quantity' => $item->quantity,
                    'line_total_cents' => $lineTotalCents,
                ];
            }

            $order = $request->user()->orders()->create([
                ...$shipping,
                'status' => 'pending_payment',
                'currency' => 'USD',
                'total_cents' => $totalCents,
            ]);

            $order->items()->createMany($lines);
            $request->user()->cartItems()->delete();

            return $order;
        });

        return redirect()
            ->route('user.orders.show', $order)
            ->with('success', 'Order placed. Payment is pending confirmation.');
    }
}
