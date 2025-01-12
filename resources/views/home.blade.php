@extends('layouts.app')

@section('title', 'Interactive Location Map')

@section('content')


    <body class="gradient-bg min-h-screen">

        <!-- Main Content -->
        <div class="container mx-auto px-4 py-8">
            <div class="text-center pt-12 md:pt-24 max-w-[90%] md:max-w-2xl mx-auto pb-4 md:pb-8">
                <h1 class="text-white text-3xl font-bold">Bingung mau jalan kemana sama HTSan mu?
                </h1>
                <h2 class="text-gray-200 mt-10">Cari referensi jalan sama HTSan mu yang gak seberapa itu</h2>
            </div>
            <div class="flex justify-center space-x-4">
                <button
                    class="button-gradient text-white font-medium px-6 py-3 rounded-full shadow-lg flex items-center justify-center space-x-2"
                    onclick="window.location.href='{{ route('index') }}'">
                    <i class="fas fa-location-arrow"></i>
                    <span>Kemana yah?</span>
                </button>
                <button
                    class="button-gradient text-white font-medium px-6 py-3 rounded-full shadow-lg flex items-center justify-center space-x-2"
                    onclick="window.location.href='{{ route('markers.create') }}'">
                    <i class="fas
                    fa-plus-circle"></i>
                    <span>Aku Punya Ide</span>
                </button>
            </div>
        </div>
    </body>



@endsection
