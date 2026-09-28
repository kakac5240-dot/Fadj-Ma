<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        return Sale::with(['client', 'items.medicine'])->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.quantite' => 'required|integer|min:1',
            'items.*.prix_unitaire' => 'required|numeric',
        ]);

        return DB::transaction(function () use ($data) {
            $montant_total = 0;

            foreach ($data['items'] as $item) {
                $medicine = Medicine::findOrFail($item['medicine_id']);
                if ($medicine->stock < $item['quantite']) {
                    abort(400, "Stock insuffisant pour {$medicine->nom}");
                }
                $montant_total += $item['quantite'] * $item['prix_unitaire'];
            }

            $sale = Sale::create([
                'client_id' => $data['client_id'],
                'montant_total' => $montant_total,
            ]);

            foreach ($data['items'] as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'medicine_id' => $item['medicine_id'],
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => $item['prix_unitaire'],
                ]);

                Medicine::where('id', $item['medicine_id'])->decrement('stock', $item['quantite']);
            }

            return response()->json($sale, 201);
        });
    }
}