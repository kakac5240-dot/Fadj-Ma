<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index()
    {
        return Medicine::with('group')->get();
    }

    public function show($id)
    {
        return Medicine::with('group')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string',
            'code_medicament' => 'required|string',
            'stock' => 'required|integer',
            'seuil_alerte' => 'required|integer',
            'medicine_group_id' => 'required|exists:medicine_groups,id',
            'photo_url' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $medicine = Medicine::create($data);

        return response()->json($medicine, 201);
    }

    public function update(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->update($request->all());

        return response()->json($medicine);
    }

    public function destroy($id)
    {
        Medicine::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}