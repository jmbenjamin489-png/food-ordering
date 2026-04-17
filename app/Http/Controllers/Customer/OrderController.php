<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function cart()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return view('customer.cart', compact('cart', 'total'));
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $menuItem = MenuItem::findOrFail($validated['menu_item_id']);

        if (!$menuItem->is_available) {
            return back()->with('error', 'This item is currently unavailable.');
        }

        $cart = session()->get('cart', []);
        $itemId = $menuItem->id;

        if (isset($cart[$itemId])) {
            $cart[$itemId]['quantity'] += $validated['quantity'];
        } else {
            $cart[$itemId] = [
                'id' => $menuItem->id,
                'name' => $menuItem->name,
                'price' => $menuItem->price,
                'quantity' => $validated['quantity'],
                'image' => $menuItem->image,
            ];
        }

        session()->put('cart', $cart);
        return back()->with('success', 'Item added to cart successfully!');
    }

    public function updateCart(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$validated['item_id']])) {
            $cart[$validated['item_id']]['quantity'] = $validated['quantity'];
            session()->put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Item not found in cart'
        ], 404);
    }

    public function removeFromCart($itemId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$itemId])) {
            unset($cart[$itemId]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Item removed from cart.');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('customer.menu')->with('error', 'Your cart is empty.');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $tax = $subtotal * 0.12;
        $deliveryFee = 50;
        $total = $subtotal + $tax + $deliveryFee;

        return view('customer.checkout', compact('cart', 'subtotal', 'tax', 'deliveryFee', 'total'));
    }

    public function placeOrder(Request $request)
    {
      $validated = $request->validate([
    'address' => 'required|string',
    'phone' => 'required|string',
    'payment_method' => 'required|in:cash,card,online',
    'notes' => 'nullable|string',
]);


        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('customer.menu')->with('error', 'Your cart is empty.');
        }

        try {
            DB::beginTransaction();

            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $tax = $subtotal * 0.12;
            $deliveryFee = 50;
            $total = $subtotal + $tax + $deliveryFee;

          $order = Order::create([
    'user_id' => Auth::id(),
    'subtotal' => $subtotal,
    'tax' => $tax,
    'delivery_fee' => $deliveryFee,
    'total_amount' => $total,
    'status' => 'pending',
    'payment_method' => $validated['payment_method'],
    'payment_status' => 'pending',
    'delivery_address' => $validated['address'],
    'customer_phone' => $validated['phone'],
    'notes' => $validated['notes'] ?? null,
]);


            foreach ($cart as $item) {
                $menuItem = MenuItem::find($item['id']);
                if (!$menuItem) {
                    throw new \RuntimeException('One of the cart items no longer exists. Please refresh your cart.');
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $menuItem->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('customer.order-success', $order->id)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to place order', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to place order: ' . $e->getMessage());
        }
    }

    public function orderSuccess(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('orderItems.menuItem');
        return view('customer.order-success', compact('order'));
    }

    public function orderHistory()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('orderItems.menuItem')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.order-history', compact('orders'));
    }

    public function orderDetails(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('orderItems.menuItem');
        return view('customer.order-details', compact('order'));
    }

public function cancel(Order $order)
{
    // Security check
    if ($order->user_id !== auth()->id()) {
        abort(403);
    }

    // Only allow cancel if pending or processing
    if (!in_array($order->status, ['pending', 'processing'])) {
        return back()->with('error', 'Order cannot be cancelled.');
    }

    $order->update([
        'status' => 'cancelled'
    ]);

    return back()->with('success', 'Order cancelled successfully.');
}

}
