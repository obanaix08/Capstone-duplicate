<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Material;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\InventoryMovement;
use App\Models\Production;
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
                'tracking_code' => 'TRK-'.strtoupper(uniqid()),
            ]);

            $total = 0;
            // First pass: validate material availability across all items
            $materialRequirements = [];
            foreach ($validated['items'] as $line) {
                $product = Product::findOrFail($line['product_id']);
                foreach ($product->materials as $material) {
                    $requiredQty = $material->pivot->quantity_per_unit * $line['quantity'];
                    $key = $material->id;
                    $materialRequirements[$key] = ($materialRequirements[$key] ?? 0) + $requiredQty;
                }
            }
            // Check stock sufficiency
            foreach ($materialRequirements as $materialId => $requiredTotal) {
                $mat = Material::lockForUpdate()->findOrFail($materialId);
                if ($mat->stock < $requiredTotal) {
                    abort(422, "Insufficient material stock for {$mat->name}. Required: {$requiredTotal}, Available: {$mat->stock}");
                }
            }

            // Second pass: create order items, deduct materials and log movements
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

                foreach ($product->materials as $material) {
                    $requiredQty = $material->pivot->quantity_per_unit * $line['quantity'];
                    $material->decrement('stock', $requiredQty);
                    InventoryMovement::create([
                        'movable_type' => Order::class,
                        'movable_id' => $order->id,
                        'type' => 'out',
                        'item_type' => 'material',
                        'item_id' => $material->id,
                        'quantity' => $requiredQty,
                        'reason' => 'BOM reservation for order',
                    ]);
                }

                // Auto-queue production for finished goods if needed
                Production::create([
                    'product_id' => $product->id,
                    'quantity' => $line['quantity'],
                    'batch_number' => 'RESV-'.strtoupper(uniqid()),
                    'progress_percent' => 0,
                    'start_date' => now(),
                    'estimated_completion_date' => now()->addDays(7),
                    'status' => 'planned',
                ]);
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
        // TODO: dispatch notification to customer about status change (email/SMS/push)
        return response()->json($order);
    }
}

