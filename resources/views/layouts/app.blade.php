<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Default Title')</title>

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

<style>
    @keyframes gradient {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    .gradient-bg {
        background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
        background-size: 400% 400%;
        animation: gradient 15s ease infinite;
    }

    .glass-effect {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .map-container {
        height: 500px;
        transition: all 0.3s ease;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        .map-container {
            height: 400px;
        }
    }

    .card {
        transition: all 0.3s ease;
        border-radius: 20px;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .nav-link {
        position: relative;
        overflow: hidden;
    }

    .nav-link::before {
        content: "";
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg,
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent);
        transition: 0.5s;
    }

    .nav-link:hover::before {
        left: 100%;
    }

    .table-container {
        border-radius: 20px;
        overflow: hidden;
    }

    .rainbow-text {
        background: linear-gradient(45deg,
                #ff6b6b 0%,
                #4ecdc4 50%,
                #45b7d1 75%,
                #96e6b3 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        font-weight: 600;
        animation: gradient 15s ease infinite;
        background-size: 300% 300%;
    }

    .button-gradient {
        background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
        background-size: 200% 200%;
        animation: gradient 5s ease infinite;
        transition: all 0.3s ease;
    }

    .button-gradient:hover {
        background-size: 150% 150%;
        transform: scale(1.05);
    }

    .leaflet-popup-content {
        margin: 0;
        max-width: 200px;
    }

    .leaflet-popup-content p {
        margin: 0.5em 0;
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
        max-height: none;
    }

    .nav-brand {
        font-size: 1.5rem;
        font-weight: 700;
        background: linear-gradient(45deg, #3498db, #2ecc71);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .nav-button {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .nav-button:hover {
        transform: translateY(-2px);
    }

    .nav-button::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, #3498db, #2ecc71);
        transform: scaleX(0);
        transform-origin: right;
        transition: transform 0.3s ease;
    }

    .nav-button:hover::after {
        transform: scaleX(1);
        transform-origin: left;
    }
</style>

<body class="gradient-bg min-h-screen">
    <nav class="glass-effect w-full fixed top-0 z-50 shadow-lg backdrop-blur-lg">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between h-20">
                <!-- Logo/Brand -->
                <div class="flex-shrink-0">
                    <span class="nav-brand">GeoMapper</span>
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
