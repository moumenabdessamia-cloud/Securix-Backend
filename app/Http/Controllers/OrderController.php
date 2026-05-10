<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items.product'])->orderBy('created_at', 'desc')->get();
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $request->validate([
            'products'            => 'required|array',
            'products.*.id'       => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $total = 0;
        $order = Order::create([
            'user_id' => $request->user()->id,
            'total'   => 0,
            'status'  => 'pending',
        ]);

        foreach ($request->products as $item) {
            $product = Product::findOrFail($item['id']);

            // Prix selon offre ou prix normal
            $price = $product->is_on_sale && $product->sale_price
                ? $product->sale_price
                : $product->product_price;

            // ✅ Décrémenter le stock automatiquement
            $product->stock_qty = max(0, $product->stock_qty - $item['quantity']);
            $product->save();

            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $product->id,
                'quantity'   => $item['quantity'],
                'price'      => $price,
            ]);

            $total += $price * $item['quantity'];
        }

        $order->update(['total' => $total]);

        return response()->json($order->load('items.product'), 201);
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();
        return response()->json($order);
    }
}