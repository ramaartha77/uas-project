@extends('layouts.app')

@section('title', 'Interactive Location Map')

@section('content')

    <body class="gradient-bg min-h-screen">

        <!-- Main Content -->
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-4xl font-bold text-center mb-8 text-white">
                Interactive Location Map
            </h1>

            <!-- Location Control Panel -->
            <div class="glass-effect rounded-3xl shadow-lg p-8 mb-8">
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Starting Point Section -->
                    <div class="space-y-4">
                        <h3 class="text-2xl font-semibold text-gray-800 rainbow-text">Set Starting Point</h3>
                        <button id="get-current-location"
                            class="w-full button-gradient text-white font-medium px-6 py-3 rounded-full shadow-lg flex items-center justify-center space-x-2">
                            <i class="fas fa-location-arrow"></i>
                            <span>Use Current Location</span>
                        </button>
                        <div class="flex space-x-2">
                            <input type="text" id="start-location"
                                class="flex-1 px-4 py-2 rounded-full border-2 border-purple-200 focus:border-purple-400 focus:ring-2 focus:ring-purple-200 transition duration-300"
                                placeholder="Enter starting point">
                            <button id="set-manual-location"
                                class="button-gradient text-white px-6 py-2 rounded-full shadow-lg transition duration-300">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Route Information -->
                    <div id="route-info" class="hidden space-y-4">
                        <h3 class="text-2xl font-semibold rainbow-text">Route Details</h3>
                        <div class="space-y-3 bg-white bg-opacity-50 p-6 rounded-2xl">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-play-circle text-green-500 text-xl"></i>
                                <span id="start-address" class="text-gray-700">Not set</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-flag-checkered text-red-500 text-xl"></i>
                                <span id="end-address" class="text-gray-700">Not set</span>
                            </div>
                        </div>
                        <button id="cancel-route"
                            class="w-full bg-red-500 hover:bg-red-600 text-white font-medium px-6 py-3 rounded-full shadow-lg transition duration-300 flex items-center justify-center space-x-2">
                            <i class="fas fa-times"></i>
                            <span>Cancel Route</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Maps Section -->
            <div class="grid lg:grid-cols-2 gap-8 mb-12">
                <div class="card glass-effect">
                    <h3 class="text-xl font-semibold p-4 rainbow-text border-b border-purple-100">Leaflet Map</h3>
                    <div id="leaflet-map" class="map-container"></div>
                </div>
                <div class="card glass-effect">
                    <h3 class="text-xl font-semibold p-4 rainbow-text border-b border-blue-100">Google Map</h3>
                    <div id="google-map" class="map-container"></div>
                </div>
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
                                    <td class="px-6 py-4">
                                        <button
                                            class="view-button button-gradient text-white font-medium py-2 px-4 rounded-full shadow-md"
                                            data-marker='@json($marker)'>
                                            View
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        No data available
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
@endsection
