<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Material;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'items.product'])->orderByDesc('id');
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }
        return response()->json($query->paginate(20));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        return DB::transaction(function () use ($validated) {
            $order = Order::create([
                'customer_id' => $validated['customer_id'],
                'status' => 'pending',
                'total_amount' => 0,
                'ordered_at' => now(),
            ]);

            $total = 0;
            foreach ($validated['items'] as $line) {
                $product = Product::findOrFail($line['product_id']);
                $lineTotal = $product->price * $line['quantity'];
                $total += $lineTotal;
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $line['quantity'],
                    'unit_price' => $product->price,
                    'line_total' => $lineTotal,
                ]);

                // Predictive analytics: reserve materials based on BOM usage
                foreach ($product->materials as $material) {
                    $requiredQty = $material->pivot->quantity_per_unit * $line['quantity'];
                    // immediate stock deduction for reservation
                    $material->decrement('stock', $requiredQty);
                }
            }

            $order->update(['total_amount' => $total]);

            return response()->json($order->load(['items.product', 'customer']), 201);
        });
    }

    public function show(Order $order)
    {
        return response()->json($order->load(['items.product', 'customer']));
    }

    public function update(Request $request, Order $order)
    {
        $order->update($request->only(['status', 'delivered_at']));
        return response()->json($order);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(['deleted' => true]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => ['required', 'string']]);
        $order->update(['status' => $request->status]);
        return response()->json($order);
    }
}

