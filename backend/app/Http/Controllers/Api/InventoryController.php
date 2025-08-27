<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()->orderBy('name')->paginate(20);
        $materials = Material::query()->orderBy('name')->paginate(20);

        return response()->json(compact('products', 'materials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:product,material'],
            'data' => ['required', 'array'],
        ]);

        if ($validated['type'] === 'product') {
            $item = Product::create($validated['data']);
        } else {
            $item = Material::create($validated['data']);
        }

        return response()->json($item, 201);
    }

    public function show(string $id)
    {
        $product = Product::find($id);
        if ($product) return response()->json($product);
        $material = Material::findOrFail($id);
        return response()->json($material);
    }

    public function update(Request $request, string $id)
    {
        $type = $request->input('type');
        $data = $request->input('data', []);
        if ($type === 'product') {
            $item = Product::findOrFail($id);
        } else {
            $item = Material::findOrFail($id);
        }
        $item->update($data);
        return response()->json($item);
    }

    public function destroy(Request $request, string $id)
    {
        $type = $request->input('type');
        $item = $type === 'product' ? Product::findOrFail($id) : Material::findOrFail($id);
        $item->delete();
        return response()->json(['deleted' => true]);
    }

    public function lowStock()
    {
        $lowProducts = Product::whereColumn('stock', '<=', 'low_stock_threshold')->orderBy('stock')->get();
        $lowMaterials = Material::whereColumn('stock', '<=', 'low_stock_threshold')->orderBy('stock')->get();
        return response()->json(['products' => $lowProducts, 'materials' => $lowMaterials]);
    }
}

