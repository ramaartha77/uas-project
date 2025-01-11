@extends('layouts.app')

@section('title', 'Interactive Location Map')

@section('content')

    <style>
        @keyframes slide {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        @keyframes slide-opposite {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .animate-slide {
            animation: slide 80s linear infinite;
        }

        /* Optional: Ensure no overflow from the container */
        .slider-container {
            overflow: hidden;
        }

        .animate-slide-opposite {
            animation: slide-opposite 80s linear infinite;
        }
    </style>

    <body class="gradient-bg min-h-screen">

        <!-- Main Content -->
        <div class="container mx-auto px-4 py-8">
            <div class="text-center pt-12 md:pt-24 max-w-[90%] md:max-w-2xl mx-auto pb-4 md:pb-8">
                <h1 class="text-white text-3xl">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Dignissimos, natus.
                </h1>
                <h2 class="text-gray-300 mt-10">Lorem ipsum dolor sit amet.</h2>
            </div>
            <div class="flex justify-center space-x-4">
                <button id=""
                    class="button-gradient text-white font-medium px-6 py-3 rounded-full shadow-lg flex items-center justify-center space-x-2">
                    <i class="fas fa-location-arrow"></i>
                    <span>Tell Your Experience</span>
                </button>
                <button id="get-new-location"
                    class="button-gradient text-white font-medium px-6 py-3 rounded-full shadow-lg flex items-center justify-center space-x-2">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Location</span>
                </button>
            </div>
        </div>

        <!-- Sliding Card Section -->
        <div class="slider-container py-8">
            <div class="flex animate-slide space-x-6">
                <div class="glass-effect rounded-2xl shadow-lg p-8 mb-8 inline-block">
                    <h1>Lorem ipsum dolor sit amet.</h1>
                </div>
                <div class="glass-effect rounded-2xl shadow-lg p-8 mb-8 inline-block">
                    <h1>Lorem ipsum dolor sit amet.</h1>
                </div>
                <div class="glass-effect rounded-2xl shadow-lg p-8 mb-8 inline-block">
                    <h1>Lorem ipsum dolor sit amet.</h1>
                </div>
                <div class="glass-effect rounded-2xl shadow-lg p-8 mb-8 inline-block">
                    <h1>Lorem ipsum dolor sit amet.</h1>
                </div>
                <div class="glass-effect rounded-2xl shadow-lg p-8 mb-8 inline-block">
                    <h1>Lorem ipsum dolor sit amet.</h1>
                </div>
                <div class="glass-effect rounded-2xl shadow-lg p-8 mb-8 inline-block">
                    <h1>Lorem ipsum dolor sit amet.</h1>
                </div>


            </div>
        </div>

        <!-- Sliding Card Section 2-->
        <div class="slider-container py-8">
            <div class="flex animate-slide-opposite space-x-6">

                <div class="glass-effect rounded-2xl shadow-lg p-8 mb-8 inline-block">
                    <h1>Lorem ipsum dolor sit amet.</h1>
                </div>
                <div class="glass-effect rounded-2xl shadow-lg p-8 mb-8 inline-block">
                    <h1>Lorem ipsum dolor sit amet.</h1>
                </div>
                <div class="glass-effect rounded-2xl shadow-lg p-8 mb-8 inline-block">
                    <h1>Lorem ipsum dolor sit amet.</h1>
                </div>
                <div class="glass-effect rounded-2xl shadow-lg p-8 mb-8 inline-block">
                    <h1>Lorem ipsum dolor sit amet.</h1>
                </div>
                <div class="glass-effect rounded-2xl shadow-lg p-8 mb-8 inline-block">
                    <h1>Lorem ipsum dolor sit amet.</h1>
                </div>
                <div class="glass-effect rounded-2xl shadow-lg p-8 mb-8 inline-block">
                    <h1>Lorem ipsum dolor sit amet.</h1>
                </div>


            </div>
        </div>
    </body>



@endsection
