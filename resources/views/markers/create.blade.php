<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Add New Marker</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body class="bg-gray-100 font-sans min-h-screen">

    <!-- Navigation -->
    <nav class="bg-white shadow-md p-4 mb-10">
        <div class="container mx-auto flex items-center justify-between">
            <div class="flex space-x-6">
                <a href="{{ route('markers.create') }}"
                    class="text-gray-800 hover:text-blue-600 transition duration-300 flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i>
                    <span class="font-semibold">Add New Data</span>
                </a>
                <a href="{{ route('index') }}"
                    class="text-gray-800 hover:text-blue-600 transition duration-300 flex items-center">
                    <i class="fas fa-map-marked-alt mr-2"></i>
                    <span class="font-semibold">View Map</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Form Section -->
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">Add New Location Marker</h2>

        <!-- Success or Error Messages -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <ul>
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
                <label for="name" class="block text-sm font-medium text-gray-700">Location Name</label>
                <input type="text" name="name" id="name" required
                    class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                <input type="file" name="image" id="image" accept="image/*"
                    class="mt-1 block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                    <input type="number" name="latitude" id="latitude" step="any" required
                        class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                    <input type="number" name="longitude" id="longitude" step="any" required
                        class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="3" required
                    class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                <input type="text" name="address" id="address" required
                    class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" name="price" id="price" step="0.01" required
                            class="pl-12 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label for="rate" class="block text-sm font-medium text-gray-700">Rating</label>
                    <input type="number" name="rate" id="rate" min="0" max="5" step="0.1"
                        required
                        class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex items-center justify-end mt-6">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                    Add Location
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table Section -->
    <div class="container mx-auto mt-8">
        <h2 class="text-lg font-bold mb-4">Data Lokasi</h2>
        <div class="overflow-x-auto shadow-lg rounded-lg">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left">Name</th>
                        <th class="py-3 px-4 text-left">Description</th>
                        <th class="py-3 px-4 text-left">Address</th>
                        <th class="py-3 px-4 text-left">Price</th>
                        <th class="py-3 px-4 text-left">Rating</th>
                        <th class="py-3 px-4 text-left">Latitude</th>
                        <th class="py-3 px-4 text-left">Longitude</th>
                        <th class="py-3 px-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($markers as $marker)
                        <tr class="border-t">
                            <td class="py-2 px-4">{{ $marker->name }}</td>
                            <td class="py-2 px-4">{{ $marker->description }}</td>
                            <td class="py-2 px-4">{{ $marker->address }}</td>
                            <td class="py-2 px-4">{{ $marker->price }}</td>
                            <td class="py-2 px-4">{{ $marker->rate }}/5</td>
                            <td class="py-2 px-4">{{ $marker->latitude }}</td>
                            <td class="py-2 px-4">{{ $marker->longitude }}</td>
                            <td class="py-2 px-4 flex space-x-2">
                                <a href="{{ route('markers.edit', $marker->id) }}"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-3 rounded text-sm">Edit</a>
                                <form action="{{ route('markers.destroy', $marker->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded text-sm"
                                        onclick="return confirm('Are you sure you want to delete this marker?')">
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

</body>

</html>
