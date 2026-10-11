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
            'medicamentsDisponibles' => \App\Models\Medicine::where('stock', '>', 0)->count(),
'totalMedicaments'       => \App\Models\Medicine::count(),
'groupesMedicaments'     => \App\Models\MedicineGroup::count(),
'penurieMedicaments'     => \App\Models\Medicine::whereColumn('stock', '<=', 'seuil_alerte')->count(),
'statutInventaire'       => \App\Models\Medicine::whereColumn('stock', '<=', 'seuil_alerte')->count() > 0 ? 'Attention' : 'Bien',
        ]);
    }
}