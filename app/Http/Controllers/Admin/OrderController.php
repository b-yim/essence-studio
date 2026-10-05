<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        return view('admin.orders', ['orders' => Order::with('user')->latest()->paginate(20)]);
    }

    public function show(Order $order): View
    {
        return view('admin.order', [
            'order' => $order->load(['user', 'items']),
            'availableStatuses' => $order->availableStatuses(),
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => [
                'required',
                Rule::in(['pending_payment', 'paid', 'processing', 'shipped', 'delivered', 'cancelled']),
            ],
        ]);

        DB::transaction(function () use ($order, $data): void {
            $lockedOrder = Order::query()
                ->with('items')
                ->lockForUpdate()
                ->findOrFail($order->id);

            $nextStatus = $data['status'];

            if ($nextStatus === $lockedOrder->status) {
                return;
            }

            if (! $lockedOrder->canTransitionTo($nextStatus)) {
                throw ValidationException::withMessages([
                    'status' => 'This status change is not allowed.',
                ]);
            }

            if ($nextStatus === 'paid') {
                $this->adjustStock($lockedOrder, -1);
            }

            if ($nextStatus === 'cancelled' && $lockedOrder->status !== 'pending_payment') {
                $this->adjustStock($lockedOrder, 1);
            }

            $lockedOrder->update(['status' => $nextStatus]);
        });

        return back()->with('success', 'Order status updated.');
    }

    private function adjustStock(Order $order, int $direction): void
    {
        foreach ($order->items as $item) {
            $variant = ProductVariant::query()
                ->lockForUpdate()
                ->find($item->product_variant_id);

            if (! $variant) {
                throw ValidationException::withMessages([
                    'status' => 'An ordered size no longer exists.',
                ]);
            }

            if ($direction < 0 && $variant->stock_quantity < $item->quantity) {
                throw ValidationException::withMessages([
                    'status' => 'There is not enough stock to mark this order paid.',
                ]);
            }

            $variant->increment('stock_quantity', $direction * $item->quantity);
        }
    }
}
