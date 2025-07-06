<!DOCTYPE html>
<html lang="{{ str_replace('_','-', app()->getLocale()) }}">
<head>
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

{{-- style script --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body class="font-sans antialiased">
    <div class="bg-gray-100">
        <div class="flex justify-center items-center h-sceen w-full">
            <form action="" class="w-full max-w-2xl" method="post">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 pb-12">
                        <div class="flex justify-between items-center gap-x-6">
                            <div>
                                <h2 class="text-base/7 font-semibold text-gray-900">Kamu akan mengirimkan dukungan kepada {{ $user->username }}</h2>
                                <p class="mt-1 text-sm/6 text-gray-600">Silahkan melengkapi data dibawah ini</p>
                            </div>
                            @guest
                                <a href="{{ route('login') }}" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                    Log in
                                </a>
                            @endguest
                        </div>
                        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-full">
                                <label for="name" class="block text-sm/6 font-medium text-gray-900">
                                    Nama
                                </label>
                                <div class="mt-2">
                                    <input type="text" id="name" name="name" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" value="{{ auth()->check() ? auth()->user()->name : old('name') }}" @if (auth()->check()) readonly
                                        
                                    @endif>
                                </div>
                            </div>
                            <div class="sm:col-span-full">
                                <label for="email" class="block text-sm/6 font-medium text-gray-900">
                                    Email
                                </label>
                                <div class="mt-2">
                                    <input type="email" id="email" name="email" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" value="{{ auth()->check() ? auth()->user()->email : old('email') }}" @if (auth()->check()) readonly
                                        
                                    @endif>
                                </div>
                            </div>
                            <div class="sm:col-span-full">
                                <label for="amount" class="block text-sm/6 font-medium text-gray-900">
                                    Nominal
                                </label>
                                <div class="mt-2">
                                    <input type="number" id="amount" name="amount" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" >
                                </div>
                            </div>
                            <div class="sm:col-span-full">
                                <label for="message" class="block text-sm/6 font-medium text-gray-900">
                                    Pesan / Kesan
                                </label>
                                <div class="mt-2">
                                    <textarea type="text" id="message" name="message" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>