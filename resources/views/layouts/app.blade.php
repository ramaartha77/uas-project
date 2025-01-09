<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Default Title')</title>
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

    <!-- Content Section -->
    <div class="container mx-auto">
        @yield('content')
    </div>
</body>

</html>
