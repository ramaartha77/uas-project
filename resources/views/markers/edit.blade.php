@extends('layouts.app')

@section('title', 'Update Location')

@section('content')
    <div class="max-w-2xl mx-auto glass-effect p-8 rounded-3xl shadow-lg my-8">
        <h2 class="text-3xl font-bold text-center mb-8 rainbow-text">Add New Location</h2>

        <form action="{{ route('markers.update', $marker->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-lg font-medium rainbow-text mb-2">Location Name</label>
                <input type="text" name="name" id="name" required
                    class="w-full px-4 py-3 rounded-full border-2 border-purple-200 focus:border-purple-400 focus:ring-2 focus:ring-purple-200 transition duration-300 glass-effect">
            </div>

            <div>
                <label for="image" class="block text-lg font-medium rainbow-text mb-2">Image</label>
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full text-sm text-gray-700
                    file:mr-4 file:py-3 file:px-6
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:button-gradient file:text-white
                    hover:file:scale-105 transition duration-300">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label for="latitude" class="block text-lg font-medium rainbow-text mb-2">Latitude</label>
                    <input type="number" name="latitude" id="latitude" step="any" required
                        class="w-full px-4 py-3 rounded-full border-2 border-purple-200 focus:border-purple-400 focus:ring-2 focus:ring-purple-200 transition duration-300 glass-effect">
                </div>

                <div>
                    <label for="longitude" class="block text-lg font-medium rainbow-text mb-2">Longitude</label>
                    <input type="number" name="longitude" id="longitude" step="any" required
                        class="w-full px-4 py-3 rounded-full border-2 border-purple-200 focus:border-purple-400 focus:ring-2 focus:ring-purple-200 transition duration-300 glass-effect">
                </div>
            </div>

            <div>
                <label for="description" class="block text-lg font-medium rainbow-text mb-2">Description</label>
                <textarea name="description" id="description" rows="3" required
                    class="w-full px-4 py-3 rounded-2xl border-2 border-purple-200 focus:border-purple-400 focus:ring-2 focus:ring-purple-200 transition duration-300 glass-effect"></textarea>
            </div>

            <div>
                <label for="address" class="block text-lg font-medium rainbow-text mb-2">Address</label>
                <input type="text" name="address" id="address" required
                    class="w-full px-4 py-3 rounded-full border-2 border-purple-200 focus:border-purple-400 focus:ring-2 focus:ring-purple-200 transition duration-300 glass-effect">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label for="price" class="block text-lg font-medium rainbow-text mb-2">Price</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-600">Rp</span>
                        </div>
                        <input type="number" name="price" id="price" step="0.01" required
                            class="w-full pl-12 px-4 py-3 rounded-full border-2 border-purple-200 focus:border-purple-400 focus:ring-2 focus:ring-purple-200 transition duration-300 glass-effect">
                    </div>
                </div>

                <div>
                    <label for="rate" class="block text-lg font-medium rainbow-text mb-2">Rating</label>
                    <input type="number" name="rate" id="rate" min="0" max="5" step="0.1"
                        required
                        class="w-full px-4 py-3 rounded-full border-2 border-purple-200 focus:border-purple-400 focus:ring-2 focus:ring-purple-200 transition duration-300 glass-effect">
                </div>
            </div>

            <div class="flex items-center justify-end mt-6">
                <button type="submit"
                    class="button-gradient text-white font-medium px-8 py-3 rounded-full shadow-lg hover:scale-105 transition duration-300 flex items-center space-x-2">
                    Update Marker
                </button>
            </div>
        </form>
    </div>
@endsection
