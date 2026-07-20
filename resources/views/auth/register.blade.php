<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tanos ERP — Register</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="h-full bg-[#0f2e6b] text-white flex items-center justify-center p-6">
    <div class="max-w-md w-full bg-[#1e40af]/20 border border-white/10 p-8 rounded-2xl shadow-2xl backdrop-blur-md">
        <h2 class="text-2xl font-bold mb-2 text-center">Buat Akun Baru</h2>
        <p class="text-xs text-blue-200/70 text-center mb-6">Daftar user ke database MySQL Tanos</p>

        @if ($errors->any())
            <div class="mb-4 bg-rose-500/20 border border-rose-500/30 p-3 rounded-lg text-xs text-rose-200">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold uppercase text-blue-200/80 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-transparent border-b border-blue-400/30 text-white py-2 text-sm focus:outline-none focus:border-blue-300">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase text-blue-200/80 mb-1">Email / Username</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-transparent border-b border-blue-400/30 text-white py-2 text-sm focus:outline-none focus:border-blue-300">
            </div>
            <div x-data="{ show: false }">
                <label class="block text-xs font-semibold uppercase text-blue-200/80 mb-1">Password</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="password" required class="w-full bg-transparent border-b border-blue-400/30 text-white py-2 pr-8 text-sm focus:outline-none focus:border-blue-300">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center text-blue-200/60 hover:text-blue-200 cursor-pointer">
                        <template x-if="!show">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-blue-200/60">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </template>
                        <template x-if="show">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-blue-200/60">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </template>
                    </button>
                </div>
            </div>
            <div x-data="{ show: false }">
                <label class="block text-xs font-semibold uppercase text-blue-200/80 mb-1">Konfirmasi Password</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="password_confirmation" required class="w-full bg-transparent border-b border-blue-400/30 text-white py-2 pr-8 text-sm focus:outline-none focus:border-blue-300">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center text-blue-200/60 hover:text-blue-200 cursor-pointer">
                        <template x-if="!show">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-blue-200/60">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </template>
                        <template x-if="show">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-blue-200/60">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </template>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full bg-[#1e40af] hover:bg-[#1d3a9e] text-white text-xs font-bold py-3 uppercase rounded tracking-wider mt-4 transition-colors">
                DAFTAR SEKARANG
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-xs text-blue-300 hover:underline">Kembali ke Halaman Login</a>
        </div>
    </div>
</body>
</html>