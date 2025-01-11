<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Default Title')</title>

    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">


    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Leaflet.js CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Google Maps API -->
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4lKVb0eLSNyhEO-C_8JoHhAvba6aZc3U&libraries=places,directions">
    </script>

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Leaflet Routing Machine -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
</head>

<body class="gradient-bg min-h-screen">
    <nav class="glass-effect w-full fixed top-0 z-50 shadow-lg backdrop-blur-lg">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between h-20">
                <!-- Logo/Brand -->
                <div class="flex-shrink-0">
                    <span class="nav-brand">JejakHati</span>
                </div>

                <!-- Navigation Links -->
                <div class="flex items-center space-x-6 ">
                    <a href="{{ route('index') }}"
                        class="nav-button group flex items-center space-x-3 px-5 py-2.5 rounded-full bg-white bg-opacity-20 hover:bg-opacity-30 transition-all duration-300">
                        <i
                            class="fas fa-map-marked-alt text-2xl text-blue-500 group-hover:scale-110 transition-transform duration-300"></i>
                        <span class="font-medium text-gray-800">View Map</span>
                    </a>

                    <a href="{{ route('markers.create') }}"
                        class="nav-button group flex items-center space-x-3 px-5 py-2.5 rounded-full bg-white bg-opacity-20 hover:bg-opacity-30 transition-all duration-300">
                        <i
                            class="fas fa-plus-circle text-2xl text-green-500 group-hover:scale-110 transition-transform duration-300"></i>
                        <span class="font-medium text-gray-800">Add Location</span>
                    </a>

                </div>
            </div>
        </div>
    </nav>
    <!-- Content Section -->
    <div class="container mx-auto mt-24 mb-9">
        @yield('content')
    </div>
</body>
<!-- Scripts -->
<script src="{{ asset('assets/script.js') }}"></script>

</html>
