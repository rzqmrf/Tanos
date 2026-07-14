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
            <div>
                <label class="block text-xs font-semibold uppercase text-blue-200/80 mb-1">Password</label>
                <input type="password" name="password" required class="w-full bg-transparent border-b border-blue-400/30 text-white py-2 text-sm focus:outline-none focus:border-blue-300">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase text-blue-200/80 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required class="w-full bg-transparent border-b border-blue-400/30 text-white py-2 text-sm focus:outline-none focus:border-blue-300">
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