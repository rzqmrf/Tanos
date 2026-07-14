<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tanos ERP — Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
        }
    </style>
</head>
<body class="h-full">

    <div class="h-full flex flex-col md:flex-row">

        <div class="w-full md:w-1/2 bg-[#0f2e6b] flex flex-col justify-between p-8 sm:p-12 lg:p-20 text-white min-h-screen md:min-h-full">

            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center border border-white/20">
                    <span class="text-white font-extrabold text-sm select-none">T</span>
                </div>
                <span class="text-white font-semibold text-lg tracking-wide">Login</span>
            </div>

            <div class="my-auto max-w-sm w-full py-8">

                <h1 class="text-3xl font-extrabold tracking-tight mb-8">Sign In</h1>

                @if ($errors->any())
                    <div class="mb-6 bg-rose-500/10 border border-rose-500/20 rounded-xl p-4 flex items-start space-x-2.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-rose-400 shrink-0 mt-0.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                        <span class="text-sm font-medium text-rose-200">{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="username" class="block text-xs font-semibold text-blue-200/80 uppercase tracking-wider">Username</label>
                        <input type="text"
                                id="username"
                                name="username"
                                value="{{ old('username', 'rozaq') }}"
                                placeholder="Username"
                                required
                                class="w-full bg-transparent border-b border-blue-400/30 text-white placeholder-blue-300/40 py-2.5 focus:outline-none focus:border-blue-300 text-sm transition-colors rounded-none px-0 mt-1">
                    </div>

                    <div x-data="{ show: false }">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-xs font-semibold text-blue-200/80 uppercase tracking-wider">Password</label>
                        </div>
                        <div class="relative mt-1">
                            <input :type="show ? 'text' : 'password'"
                                    id="password"
                                    name="password"
                                    placeholder="Password"
                                    value="admin123"
                                    required
                                    class="w-full bg-transparent border-b border-blue-400/30 text-white placeholder-blue-300/40 py-2.5 pr-8 focus:outline-none focus:border-blue-300 text-sm transition-colors rounded-none px-0">

                            <button type="button"
                                    @click="show = !show"
                                    class="absolute inset-y-0 right-0 flex items-center text-blue-200/60 hover:text-blue-200 cursor-pointer">
                                <template x-if="!show">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </template>
                                <template x-if="show">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                    </svg>
                                </template>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="server" class="block text-xs font-semibold text-blue-200/80 uppercase tracking-wider">Server</label>
                        <div class="relative mt-1">
                            <select id="server"
                                    name="server"
                                    class="w-full bg-transparent border-b border-blue-400/30 text-white py-2.5 focus:outline-none focus:border-blue-300 text-sm transition-colors rounded-none px-0 appearance-none pr-8 cursor-pointer">
                                <option value="main" class="bg-[#0f2e6b] text-white">Server Utama (Production)</option>
                                <option value="staging" class="bg-[#0f2e6b] text-white">Server Staging</option>
                                <option value="local" class="bg-[#0f2e6b] text-white">Server Local</option>
                            </select>

                            <div class="absolute inset-y-0 right-0 flex items-center pr-1 pointer-events-none text-blue-200/60">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#1e40af] hover:bg-[#1d3a9e] active:bg-[#1e3a8a] text-white text-xs font-bold py-3.5 tracking-wider uppercase rounded transition-colors shadow-md mt-6 cursor-pointer">
                        LOGIN
                    </button>
                </form>

                <div class="mt-4 text-center">
                    <p class="text-xs text-blue-200/60">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-white font-semibold hover:text-blue-300 hover:underline transition-colors ml-1">
                            Daftar Akun Baru
                        </a>
                    </p>
                </div>

                <div class="mt-8 bg-white/5 border border-white/10 rounded-xl p-4 text-xs text-purple-200">
                    <div class="font-bold text-white mb-3 flex items-center space-x-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        <span>Akun Demo:</span>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between border-b border-white/5 pb-2">
                            <div>
                                <span class="block font-bold text-white leading-none">Naufal</span>
                                <span class="block text-[10px] text-purple-300 mt-1">Admin</span>
                            </div>
                            <div class="flex space-x-1">
                                <code class="bg-white/10 px-1.5 py-0.5 rounded text-[10px] text-white">naufal / naufal@gmail.com</code>
                                <code class="bg-white/10 px-1.5 py-0.5 rounded text-[10px] text-white">naufal123</code>
                            </div>
                        </div>
                        <div class="flex items-center justify-between border-b border-white/5 pb-2">
                            <div>
                                <span class="block font-bold text-white leading-none">Rozaq</span>
                                <span class="block text-[10px] text-purple-300 mt-1">Supervisor</span>
                            </div>
                            <div class="flex space-x-1">
                                <code class="bg-white/10 px-1.5 py-0.5 rounded text-[10px] text-white">rozaq / rozaq@gmail.com</code>
                                <code class="bg-white/10 px-1.5 py-0.5 rounded text-[10px] text-white">rozaq123</code>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="text-xs text-purple-300/40">
                &copy; {{ date('Y') }} Tanos ERP &bull; All rights reserved.
            </div>

        </div>

        <div class="hidden md:flex flex-1 bg-white flex-col items-center justify-center p-12 lg:p-20 relative">
            <div class="flex flex-col items-center justify-center text-center max-w-md">

                <div class="mb-10 min-h-30 flex items-center justify-center">
                    <img src="{{ asset('images/logo.png') }}"
                            alt="Tanos Logo"
                            class="max-h-28 object-contain"
                            onerror="this.style.display='none'; document.getElementById('logo-fallback').classList.remove('hidden');">

                    <div id="logo-fallback" class="flex hidden flex-col items-center">
                        <div class="w-20 h-20 rounded-2xl bg-blue-600 flex items-center justify-center shadow-lg shadow-blue-200">
                            <span class="text-white font-extrabold text-4xl select-none">T</span>
                        </div>
                        <span class="mt-4 text-slate-800 font-extrabold text-2xl tracking-tight">Tanos ERP</span>
                    </div>
                </div>

                <div class="text-left mt-8 border-t border-slate-100 pt-8 w-full">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Pro Tip #1</span>
                    <h3 class="text-3xl font-bold text-slate-800 tracking-tight mb-3">Speed up data entry</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Use the F4 key to automatically accept all field defaults for faster data entry.
                    </p>
                </div>

            </div>
        </div>

    </div>

</body>
</html>