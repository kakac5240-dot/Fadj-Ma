<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return Client::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string',
            'contact' => 'nullable|string',
        ]);

        $client = Client::create($data);

        return response()->json($client, 201);
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        $client->update($request->all());

        return response()->json($client);
    }

    public function destroy($id)
    {
        Client::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}