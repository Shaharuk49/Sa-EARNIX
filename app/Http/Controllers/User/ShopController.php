<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();

        $query = Product::with('category', 'media')
            ->where('is_active', true);

        if ($request->filled('category')) {
            $query->where('product_category_id', $request->category);
        }

        $products = $query->latest()->paginate(12);

        return view('user.shop.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        abort_if(!$product->is_active, 404);

        $product->load('category', 'media');

        return view('user.shop.show', compact('product'));
    }

    public function placeOrder(Request $request, Product $product)
    {
        $request->validate([
            'quantity'               => ['required', 'integer', 'min:1'],
            'selling_price'          => ['required', 'numeric', 'min:' . $product->final_price],
            'customer_name'          => ['required', 'string', 'max:255'],
            'customer_phone'         => ['required', 'string', 'max:20'],
            'district'               => ['required', 'string', 'max:100'],
            'upazila'                => ['required', 'string', 'max:100'],
            'delivery_address'       => ['required', 'string', 'max:500'],
            'shop_name'              => ['nullable', 'string', 'max:255'],
            'additional_instruction' => ['nullable', 'string', 'max:500'],
        ]);

        $qty         = $request->quantity;
        $sellingPrice = (float) $request->selling_price;
        $profit      = ($sellingPrice - $product->final_price) * $qty;

        Order::create([
            'user_id'                => Auth::id(),
            'product_id'             => $product->id,
            'quantity'               => $qty,
            'base_price'             => $product->base_price,
            'vat_percent'            => $product->vat_percent,
            'final_admin_price'      => $product->final_price,
            'selling_price'          => $sellingPrice,
            'profit_amount'          => $profit,
            'delivery_charge'        => 120,
            'shop_name'              => $request->shop_name,
            'customer_name'          => $request->customer_name,
            'customer_phone'         => $request->customer_phone,
            'district'               => $request->district,
            'upazila'                => $request->upazila,
            'delivery_address'       => $request->delivery_address,
            'additional_instruction' => $request->additional_instruction,
            'status'                 => 'pending',
            'profit_status'          => 'hold',
        ]);

        return redirect()->route('user.orders.index')
            ->with('success', 'Order placed successfully! Your profit is ৳' . number_format($profit, 2));
    }

    public function orders()
    {
        $orders = Order::with('product')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('user.shop.orders', compact('orders'));
    }
}
