<?php

namespace App\Http\Controllers;

use App\Models\MedicineGroup;
use Illuminate\Http\Request;

class MedicineGroupController extends Controller
{
    public function index()
    {
        return MedicineGroup::with('medicines')->get();
    }

    public function show($id)
    {
        return MedicineGroup::with('medicines')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['nom' => 'required|string']);
        $group = MedicineGroup::create($data);

        return response()->json($group, 201);
    }

    public function update(Request $request, $id)
    {
        $group = MedicineGroup::findOrFail($id);
        $group->update($request->all());

        return response()->json($group);
    }

    public function destroy($id)
    {
        MedicineGroup::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}