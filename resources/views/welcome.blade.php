<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Arista - Aplikasi Repositori Informasi dan Sistem Terstruktur Arship</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    </head>
    <body class="font-sans antialiased flex flex-col h-screen">
        <!-- Navbar -->
        <nav class="bg-gray-800 p-4 flex-shrink-0">
            <div class="container mx-auto flex justify-between items-center">
                <div class="flex items-center">
                    <img src="{{ asset('sbadmin2/img/more-shadow.png') }}" alt="Gambar More Shadow" class="w-50 h-10 rounded-lg">
                </div>
                <div class="space-x-4">
                    <a href="{{ route('login') }}" class="text-white py-2 px-4 rounded-full bg-blue-500 hover:bg-blue-600">Login</a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="flex-1 flex items-center justify-center">
            <section class="container px-6 py-6 w-full">
                <div class="grid gap-6 lg:grid-cols-2 items-center h-full">
                    <!-- Kolom 1: Informasi Singkat -->
                    <div class="flex flex-col justify-center p-6 rounded-lg">
                        <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-4 mb-4">
                            <img src="{{ asset('sbadmin2/img/more-shadow.png') }}" alt="Gambar More Shadow" class="w-32 sm:w-40 rounded-lg">
                        </div>
                        <h1 class="text-4xl font-extrabold text-center text-gray-800 mb-2">Aplikasi Sistem Terstruktur Arsip LLDIKTI WILAYAH IX</h1>
                        <p class="text-lg text-center text-gray-600 mb-5">Manajemen file arsip dengan mudah dan cepat</p>
                    </div>

                    <!-- Kolom 2: Gambar -->
                    <div class="flex items-center justify-center p-6">
                        <img src="{{ asset('sbadmin2/img/arista.jpg') }}" alt="Gambar Arista" class="w-full h-auto rounded-lg">
                    </div>
                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-4 flex-shrink-0">
            <div class="container mx-auto text-center">
                <p class="text-sm">
                    &copy; {{ date('Y') }} Arista
                    <a href="https://www.instagram.com/lldikti9/" target="_blank" class="text-blue-400 hover:underline">lldikti9</a>
                    All Rights Reserved.
                </p>
            </div>
        </footer>
    </body>
</html>
