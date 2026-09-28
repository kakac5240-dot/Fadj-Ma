<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        return Supplier::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string',
            'contact' => 'nullable|string',
        ]);

        $supplier = Supplier::create($data);

        return response()->json($supplier, 201);
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update($request->all());

        return response()->json($supplier);
    }

    public function destroy($id)
    {
        Supplier::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}