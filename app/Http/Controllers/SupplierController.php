<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index() {
        return response()->json(Supplier::all());
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
        ]);

        $supplier = Supplier::create($validated);
        return response()->json($supplier, 201);
    }

    public function destroy($id) {
        $supplier = Supplier::find($id);
        if (!$supplier) return response()->json(['message' => 'Not found'], 404);
        
        $supplier->delete();
        return response()->json(null, 204);
    }
}
