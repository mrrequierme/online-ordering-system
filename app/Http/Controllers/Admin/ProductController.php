<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(){
        $products = Product::with('user')
        ->orderBy('name')
        ->get();
        return view('admins.products.index',compact('products'));
    }

    public function create(){
        return view('admins.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'price' => [
                'required',
                'numeric',
                'min:0',
                'max:999999.99',
                'regex:/^\d{1,6}(\.\d{1,2})?$/', // optional: extra strict for 6 digits + 2 decimals
            ],
            'description' => 'required|string',
            'image' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Store the file storage/app/public/products
        $path = $request->file('image')->store('products', 'public');

        // Create the product
        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $path, // store file path
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product created!');
    }

    public function edit(Product $product){
        return view('admins.products.edit',compact('product'));
    }

   public function update(Request $request, Product $product)
{
    // Validation
    $request->validate([
        'name' => ['required','string','max:255',Rule::unique('products','name')->ignore($product->id)], //unique for update
        'price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/', // works Laravel 9+
        'description' => 'required|string',
        'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048', // optional
    ]);

    // Update main fields
    $product->update([
        'name' => $request->name,
        'price' => $request->price,
        'description' => $request->description,
        'user_id' => auth()->id(), // <-- set current logged-in user as updater
    ]);

    // Handle file upload only if a new file is provided
    if ($request->hasFile('image')) {
        // Store new file
        $path = $request->file('image')->store('products', 'public');

        // Delete old file if it exists
        if ($product->image && \Storage::disk('public')->exists($product->image)) {
            \Storage::disk('public')->delete($product->image);
        }

        // Update product with new path
        $product->update([
            'image' => $path,
            'user_id' => auth()->id(), 
        ]);
    }

    return redirect()->route('admin.products.index')
        ->with('success', 'Product updated successfully!');
}

    public function destroy(Product $product){
        $product->delete();
        return redirect()->route('admin.products.index')->with('success','Product deleted successfully.');
    }
}
