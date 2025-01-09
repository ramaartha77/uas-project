<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Interactive Map with Location Markers</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Leaflet.js CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4lKVb0eLSNyhEO-C_8JoHhAvba6aZc3U&libraries=places">
    </script>

    <script src="https://cdn.tailwindcss.com"></script>


</head>

<style>
    .leaflet-popup-content {
        margin: 0;
        max-width: 200px;
    }

    .leaflet-popup-content p {
        margin: 0.5em 0
    }

    .gm-style .gm-style-iw-c {
        padding: 0;
    }

    .gm-style .gm-style-iw-c,
    .gm-style .gm-style-iw-d {
        max-height: none !important;
        max-width: 200px;
    }

    .gm-style-iw-chr {
        position: absolute;
        top: 0;
        right: 0;
        z-index: 10;
    }

    .gm-style-iw-d {
        overflow: visible;
        max-height: none
    }
</style>

<body class="bg-gray-100 font-sans min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-md p-4">
        <div class="container mx-auto flex items-center justify-between">
            <div class="flex space-x-6">
                <a href="{{ route('markers.create') }}"
                    class="text-gray-800 hover:text-blue-600 transition duration-300 flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i>
                    <span class="font-semibold">Form Add Data</span>
                </a>
                <a href="{{ route('index') }}"
                    class="text-gray-800 hover:text-blue-600 transition duration-300 flex items-center">
                    <i class="fas fa-map-marked-alt mr-2"></i>
                    <span class="font-semibold">Maps</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-black font-bold text-center text-3xl mb-8">INTERACTIVE MAP WITH LARAVEL</h1>

        <!-- Maps Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <div id="leaflet-map" class="h-96 rounded-lg shadow-lg"></div>
            <div id="google-map" class="h-96 rounded-lg shadow-lg"></div>
        </div>



        <!-- Error Messages -->
        @if ($errors->any())
            <div class="max-w-2xl mx-auto mt-4">
                <div class="bg-red-50 border-l-4 border-red-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">There were errors with your submission:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/script.js') }}"></script>
</body>

</html>
