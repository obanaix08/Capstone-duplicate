<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Production;
use App\Models\Product;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductionController extends Controller
{
    public function index()
    {
        return response()->json(Production::with('product')->orderByDesc('id')->paginate(20));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'estimated_completion_date' => ['nullable', 'date'],
        ]);

        $production = Production::create([
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'batch_number' => 'BATCH-'.Str::upper(Str::random(8)),
            'progress_percent' => 0,
            'start_date' => now(),
            'estimated_completion_date' => $validated['estimated_completion_date'] ?? now()->addDays(7),
            'status' => 'in_production',
        ]);

        return response()->json($production->load('product'), 201);
    }

    public function show(Production $production)
    {
        return response()->json($production->load('product'));
    }

    public function update(Request $request, Production $production)
    {
        $production->update($request->only(['progress_percent', 'status', 'estimated_completion_date', 'completed_date']));

        if ($production->progress_percent >= 100 && $production->completed_date && $production->status !== 'completed') {
            $production->status = 'completed';
            $production->save();
            // Increase finished goods stock upon completion
            $product = Product::find($production->product_id);
            if ($product) {
                $product->increment('stock', $production->quantity);
                InventoryMovement::create([
                    'movable_type' => Production::class,
                    'movable_id' => $production->id,
                    'type' => 'in',
                    'item_type' => 'product',
                    'item_id' => $product->id,
                    'quantity' => $production->quantity,
                    'reason' => 'Production completion',
                ]);
            }
        }

        return response()->json($production);
    }

    public function destroy(Production $production)
    {
        $production->delete();
        return response()->json(['deleted' => true]);
    }
}

