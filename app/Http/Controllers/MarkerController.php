<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;

class MarkerController extends Controller
{
    public function create()
    {
        return view('markers.create');
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
}
