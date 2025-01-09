<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;

class MarkerController extends Controller
{
    public function create()
    {
        $markers = Marker::all(); // Retrieve markers if needed
        return view('markers.create', compact('markers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'rate' => 'nullable|numeric|between:0,5',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('markers', 'public');
            $validated['image'] = $imagePath;
        }

        Marker::create($validated);

        return redirect()->route('index')->with('success', 'Marker added successfully!');
    }

    public function index()
    {
        $markers = Marker::all();
        return view('index', compact('markers'));
    }

    public function edit(Marker $marker)
    {
        return view('markers.edit', compact('marker'));
    }

    public function update(Request $request, Marker $marker)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'rate' => 'nullable|numeric|between:0,5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('markers', 'public');
            $validated['image'] = $imagePath;
        }

        $marker->update($validated);

        return redirect()->route('index')->with('success', 'Marker updated successfully!');
    }

    public function destroy(Marker $marker)
    {
        $marker->delete();
        return redirect()->route('index')->with('success', 'Marker deleted successfully!');
    }
}
