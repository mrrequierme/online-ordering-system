<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\History;

class OrderController extends Controller
{
    public function index(){
        $orders = Order::with(['products', 'user'])
        ->where('status','pending')
        ->orderBy('claim_date')
        ->get();
        return view('admins.orders.index',compact('orders'));
    }

    public function approve(Order $order){
        $order->update(['status' => 'approved']);
        return redirect()->back()->with('success','Order approved successfully!');
    }

    public function show(){
        $allOrders= Order::with(['products','user'])
        ->where('status','approved')
        ->orderBy('claim_date','desc')
        ->orderBy('updated_at')
        ->get();
        return view('admins.orders.show',compact('allOrders'));
    }

   public function done(Order $order)
{
    $staff = auth()->user();

    // Create history record
    $history = History::create([
        'order_id'         => $order->id,
        'user_id'          => $order->user_id,
        'customer_name'    => $order->user->name,
        'customer_email'   => $order->user->email,
        'customer_contact' => $order->user->contact,
        'customer_gender'  => $order->user->gender,
        'customer_birthday'=> $order->user->birthday,
        'customer_address' => $order->user->address,
        'total'            => $order->total,
        'claim_date'       => $order->claim_date,
        'status'           => 'done',
        'staff_id'         => $staff->id,
        'staff_name'       => $staff->name,
        'staff_email'      => $staff->email,
    ]);

    // Save each product line into history_products
    foreach ($order->products as $product) {
        $history->products()->create([
            'name' => $product->name,
            'price'        => $product->pivot->price,
            'qty'          => $product->pivot->qty,
            'subtotal'     => $product->pivot->price * $product->pivot->qty,
        ]);
    }

    $order->update(['status' => 'done']);

    return redirect()->back()->with('success', 'Order moved to history successfully!');
}
   public function unclaimed(Order $order)
{
    $staff = auth()->user();

    // Create history record
    $history = History::create([
        'order_id'         => $order->id,
        'user_id'          => $order->user_id,
        'customer_name'    => $order->user->name,
        'customer_email'   => $order->user->email,
        'customer_contact' => $order->user->contact,
        'customer_gender'  => $order->user->gender,
        'customer_birthday'=> $order->user->birthday,
        'customer_address' => $order->user->address,
        'total'            => $order->total,
        'claim_date'       => $order->claim_date,
        'status'           => 'unclaimed',
        'staff_id'         => $staff->id,
        'staff_name'       => $staff->name,
        'staff_email'      => $staff->email,
    ]);

    // Save each product line into history_products
    foreach ($order->products as $product) {
        $history->products()->create([
            'name' => $product->name,
            'price'        => $product->pivot->price,
            'qty'          => $product->pivot->qty,
            'subtotal'     => $product->pivot->price * $product->pivot->qty,
        ]);
    }

    $order->update(['status' => 'unclaimed']);

    return redirect()->back()->with('success', 'Order moved to history successfully!');
}


}
