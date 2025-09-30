@extends('layouts.authenticated')
@section('title','History ' . config('app.name'))
@section('content')
<div class="container">
    <div class="table-responsive-sm">
    <table class="table table-striped h-100vh">
        <thead class="sticky-top histories_table" style="top: 79.5px;">
            <tr class="border">
                <th>Customer Details</th>
                <th>Order Details</th>
                <th>Managed By</th>
            </tr>
        </thead>
        <tbody>
             @foreach($histories as $history)
            <tr>
                <td>
                    <div class="text-capitalize"><small class="text-muted">Name:</small> {{ $history->customer_name }}</div>
                    <div><small class="text-muted">Email:</small> {{ $history->customer_email }}</div>
                    <div><small class="text-muted">Contact:</small> {{ $history->customer_contact }}</div>
                    <div><small class="text-muted">Birthday:</small> {{ $history->customer_birthday->format('d M Y') }}</div>
                    <div class="text-capitalize"><small class="text-muted">Gender:</small> {{ $history->customer_gender }}</div>
                    <div class="text-capitalize"><small class="text-muted">Address:</small> {{ $history->customer_address }}</div>
                </td>
                <td>
                    @foreach($history->products as $product)
                    <div class="text-capitalize"><small class="text-muted">Name:</small> {{ $product->name }}</div>
                    <div><small class="text-muted">Price:</small> ₱{{ number_format($product->price,2) }}</div>
                    <div><small class="text-muted">Qty:</small> {{ $product->qty }}</div>
                    <div><small class="text-muted">SubTotal:</small> ₱{{ number_format($product->subtotal,2) }}</div>
                    <hr>
                    @endforeach
                    <div><small class="text-muted">Total:</small> <span class="bg-dark-subtle px-1"> ₱{{ number_format($history->total,2) }} </span></div>
                    <div><small class="text-muted">Claimed Date:</small> {{ $history->claim_date->format('d M Y') }}</div>
                    <div class="text-capitalize"><small class="text-muted">Status:</small> <small class="py-1 px-2  {{ $history->status === 'done' ? 'bg-success text-white' : '' }}
    {{ $history->status === 'unclaimed' ? 'bg-danger text-white' : '' }}">{{ $history->status }}</small></div>
                </td>
                <td>
                    <div class="text-capitalize"><small class="text-muted">Name:</small> {{ $history->staff_name }}</div>
                    <div><small class="text-muted">Email:</small> {{ $history->staff_email }}</div>

                </td>
                
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
@endsection