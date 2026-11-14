<?php

namespace App\Http\Controllers\ERP;

use App\Http\Controllers\Controller;
use App\Models\StockItem;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $stockItems = StockItem::with('hotel')
            ->orderByRaw('quantity <= alert_threshold DESC')
            ->get();

        return view('erp.stock.index', compact('stockItems'));
    }

    public function restock(Request $request, StockItem $stockItem)
    {
        $validated = $request->validate([
            'quantity_to_add' => ['nullable', 'integer', 'min:1', 'max:1000'],
        ]);

        $qty = $validated['quantity_to_add'] ?? 20;
        $stockItem->increment('quantity', $qty);

        return back()->with('success', "+{$qty} unités ajoutées pour {$stockItem->name}.");
    }
}
