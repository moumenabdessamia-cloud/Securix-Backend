<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::all());
    }

    public function store(Request $request)
    {
        $request->validate(['cat_title' => 'required|string|max:255']);
        $category = Category::create(['cat_title' => $request->cat_title]);
        return response()->json($category, 201);
    }

    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return response()->json(['message' => 'Catégorie supprimée']);
    }
}