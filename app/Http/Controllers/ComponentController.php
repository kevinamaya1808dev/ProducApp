<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\Category;
use Illuminate\Http\Request;

class ComponentController extends Controller
{
    public function index()
    {
        $components = Component::with('category')->get();
        return view('components.index', compact('components'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|unique:components,sku',
            'base_unit' => 'required|string|max:20',
        ]);

        Component::create($validated);

        return back()->with('success', 'Componente registrado correctamente.');
    }
}