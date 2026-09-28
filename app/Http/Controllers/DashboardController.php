<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Sale;
use App\Models\Client;
use App\Models\Supplier;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMedicines = Medicine::count();
        $lowStockCount = Medicine::whereColumn('stock', '<=', 'seuil_alerte')->count();

        $startOfMonth = now()->startOfMonth();
        $salesThisMonth = Sale::where('created_at', '>=', $startOfMonth)->get();
        $revenue = $salesThisMonth->sum('montant_total');
        $invoicesCount = $salesThisMonth->count();

        return response()->json([
            'statutInventaire' => $lowStockCount === 0 ? 'Bien' : 'Attention',
            'revenuMois' => $revenue,
            'medicamentsDisponibles' => $totalMedicines,
            'penurieMedicaments' => $lowStockCount,
            'totalClients' => Client::count(),
            'totalFournisseurs' => Supplier::count(),
            'facturesGenerees' => $invoicesCount,
        ]);
    }
}