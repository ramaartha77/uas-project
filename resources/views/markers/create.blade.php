@extends('layouts.app')

@section('title', 'Add Location')

@section('content')

    <!-- Form Section -->
    <div class="max-w-2xl mx-auto glass-effect p-8 rounded-3xl shadow-lg my-8">
        <h2 class="text-3xl font-bold text-center mb-8 rainbow-text">Add New Location</h2>

        <!-- Success Message -->
        @if (session('success'))
            <div class="glass-effect border-l-4 border-green-400 p-4 mb-6 rounded-xl" role="alert">
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="glass-effect border-l-4 border-red-400 p-4 mb-6 rounded-xl" role="alert">
                <ul class="text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Add Marker Form -->
        <form action="{{ route('markers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

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

            <div class="flex justify-center mt-8">
                <button type="submit"
                    class="button-gradient text-white font-medium px-8 py-3 rounded-full shadow-lg hover:scale-105 transition duration-300 flex items-center space-x-2">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Location</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table Section -->
    <div class="glass-effect rounded-3xl overflow-hidden">
        <div class="p-6 border-b border-purple-100">
            <h2 class="text-2xl font-bold rainbow-text">Location Data</h2>
        </div>
        <div class="table-container">
            <table class="min-w-full divide-y divide-purple-100">
                <thead class="bg-white bg-opacity-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rating</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Coordinates</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white bg-opacity-75 divide-y divide-purple-50">
                    @forelse ($markers as $marker)
                        <tr class="hover:bg-purple-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $marker->name }}</td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs overflow-hidden text-ellipsis">
                                    {{ $marker->description }}
                                </div>
                            </td>
                            <td class="px-6 py-4">{{ $marker->address }}</td>
                            <td class="px-6 py-4">{{ $marker->price }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <span class="text-yellow-400">★</span>
                                    <span class="ml-1">{{ $marker->rate }}/5</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600">
                                    {{ $marker->latitude }}, {{ $marker->longitude }}
                                </span>
                            </td>
                            <td class="px-6 py-4 space-x-2 flex">
                                <a href="{{ route('markers.edit', $marker->id) }}"
                                    class="button-gradient text-white font-medium py-2 px-4 rounded-full shadow-md hover:scale-105 transition duration-300 flex items-center">
                                    <i class="fas fa-edit mr-2"></i>
                                    Edit
                                </a>
                                <form action="{{ route('markers.destroy', $marker->id) }}" method="POST"
                                    class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white font-medium py-2 px-4 rounded-full shadow-md hover:scale-105 transition duration-300 flex items-center"
                                        onclick="return confirm('Are you sure you want to delete this marker?')">
                                        <i class="fas fa-trash-alt mr-2"></i>
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-2 px-4 text-center text-gray-600">No data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
