<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
public function index()
{
    $userId = auth()->id();

    // Pending order (only 1 at a time)
    $pendingOrder = Order::with('products')
        ->where('user_id', $userId)
        ->where('status', 'pending')
        ->latest()
        ->first();

    // All approved orders
    $approvedOrders = Order::with('products')
        ->where('user_id', $userId)
        ->where('status','!=', 'pending')
        ->orderBy('updated_at', 'desc')
        ->get();

    return view('users.orders.index', compact('pendingOrder', 'approvedOrders'));
}


//     public function index()
// {
//     // get current user's orders (adjust user filter as needed)
//     $orders = Order::with(['products', 'user'])
//         ->where('user_id', auth()->id())
//         ->orderBy('created_at', 'desc')
//         ->get();

//     // split
//     $pendingOrders  = $orders->where('status', 'pending');
//     $approvedOrders = $orders->where('status', 'approved');

//     // Which single order to show on the page?
//     // Prefer the pending order (cart) if exists; otherwise use the latest approved order; otherwise null.
//     $order = $pendingOrders->first() ?? $approvedOrders->first() ?? null;

//     return view('users.orders.index', compact('orders', 'pendingOrders', 'approvedOrders', 'order'));
// }


 public function store(Request $request)
{
     $request->validate([
        'cart' => 'required|string',
        'claim_date' => 'required|date|after:today', // ✅ must be tomorrow or later
    ]);

    $cart = json_decode($request->cart, true);

    if (!$cart || count($cart) === 0) {
        return redirect()->back()->with('error', 'Cart is empty.');
    }

    $userId = auth()->id();

    // 🔍 Check if user already has a pending order
    $order = Order::where('user_id', $userId)
                  ->where('status', 'pending')
                  ->first();

    // ✅ Recalculate total
    $total = collect($cart)->sum(fn($item) => $item['qty'] * $item['price']);

    if ($order) {
        // ✅ Update existing order total
        $order->update(['total' => $total,'claim_date' => $request->claim_date,]);

        // 🔄 Sync products (detach + attach logic)
        $cartProducts = [];
        foreach ($cart as $item) {
            $cartProducts[$item['id']] = [
                'qty'   => $item['qty'],
                'price' => $item['price'],
            ];
        }

        // This will update existing pivot rows, insert new ones,
        // and remove products not in the new cart update the pivot record
        $order->products()->sync($cartProducts);

    } else {
        // 🆕 Create new order if none pending
        $order = Order::create([
            'user_id' => $userId,
            'total'   => $total,
            'claim_date' => $request->claim_date,
            'status'     => 'pending',
            
        ]);

        foreach ($cart as $item) {
            $order->products()->attach($item['id'], [
                'qty'   => $item['qty'],
                'price' => $item['price'],
            ]);
        }
    }

    return redirect()->route('user.orders.index')
        ->with('success', 'Your order was placed successfully! It will be reviewed shortly.');
        
}
// ->with('clearCart', true); // 👈 pass a flag to clear cart

}
