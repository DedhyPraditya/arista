<x-guest-layout>
    <div class="flex flex-col md:flex-row min-h-screen">
        <!-- Gambar untuk mobile (atas) dan tablet/desktop (kiri) -->
        <div class="w-full h-48 md:h-auto md:w-1/2 bg-blue-500 bg-center md:mt-12 md:mr-6 lg:mt-16 lg:mr-8" style="background-image: url('{{ asset('sbadmin2/img/4886718.jpg') }}'); background-size: cover; background-position: center;">
            <!-- Gambar di sini -->
        </div>

        <!-- Kolom kanan untuk form login -->
        <div class="w-full md:w-1/2 flex justify-center items-center bg-blue-500 p-4 md:p-6 lg:p-0">
            <!-- Card untuk form login -->
            <div class="w-full sm:w-4/5 md:w-4/5 lg:w-1/2 max-w-md bg-white p-6 md:p-7 lg:p-6 rounded-lg shadow-lg">
                <h1 class="text-2xl font-bold text-gray-800 text-center">Aplikasi Sistem Terstruktur Arsip</h1>
                <h1 class="text-2xl font-bold text-gray-800 text-center mb-4">LLDIKTI WILAYAH IX</h1>
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    @if ($errors->any())
                        <div class="mb-4 text-red-600 text-sm">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div>
                        <x-label for="email" value="{{ __('Email') }}" />
                        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    </div>

                    <div class="mt-3">
                        <x-label for="password" value="{{ __('Password') }}" />
                        <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                    </div>

                    <div class="block mt-4">
                        <label for="remember_me" class="flex items-center">
                            <x-checkbox id="remember_me" name="remember" />
                            <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif

                        <x-button class="ms-4">
                            {{ __('Log in') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
