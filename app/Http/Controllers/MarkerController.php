<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarkerController extends Controller
{
    public function create()
    {
        return view('markers.create');
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'description' => 'required|string',
            'address' => 'required|string',
            'price' => 'required|numeric|min:0',
            'rate' => 'required|numeric|between:0,5',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/markers', $imageName);
            $validated['image'] = 'markers/' . $imageName;
        }

        try {
            // Create the marker
            Marker::create($validated);
            return redirect()->route('index')
                ->with('success', 'Location marker added successfully!');
        } catch (\Exception $e) {
            // If there's an error, redirect back with error message
            return redirect()->back()
                ->with('error', 'Error creating marker: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function index()
    {
        $markers = Marker::all();
        return view('index', compact('markers')); // This passes $markers to the view
    }
}
