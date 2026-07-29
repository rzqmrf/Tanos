@extends('layouts.app')

@section('title', 'User Management — Tanos ERP')

@section('content')
<div class="space-y-6">
    <!-- Header Block -->
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center space-x-3">
            <div class="p-2.5 bg-violet-50 dark:bg-violet-950/30 text-violet-600 dark:text-violet-400 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0 1 10.089 18H9.91A11.386 11.386 0 0 1 5 19.237v-.11c0-1.113.285-2.16.786-3.07M15 7.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5-3a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 9a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">User Management</h1>
                <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">Pengelolaan akun pengguna ERP, hak akses, dan pemetaan ke pegawai lapangan.</p>
            </div>
        </div>
        <button onclick="openCreateModal()" class="bg-violet-600 hover:bg-violet-700 text-white px-5 py-2.5 rounded-xl text-xs font-bold transition-colors shadow-sm flex items-center space-x-2 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Tambah User</span>
        </button>
    </div>

    <!-- Alert Sukses / Eror -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 border border-rose-100 dark:border-rose-900/30 rounded-xl text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Stats Panel -->
    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-2xl shadow-sm flex flex-col md:flex-row divide-y md:divide-y-0 md:divide-x divide-slate-100 dark:divide-slate-800/80">
        <!-- Stat Item 1 -->
        <div class="flex-1 p-5 flex items-center space-x-4">
            <div class="p-3 bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm border border-blue-100/50 dark:border-blue-800/30 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0 1 10.089 18H9.91A11.386 11.386 0 0 1 5 19.237v-.11c0-1.113.285-2.16.786-3.07M15 7.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5-3a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 9a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                </svg>
            </div>
            <div>
                <span class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Total Akun</span>
                <span class="block text-2xl font-extrabold text-slate-800 dark:text-slate-100 font-mono tracking-tight leading-none mt-1">{{ $stats['total'] }}</span>
            </div>
        </div>

        <!-- Stat Item 2 -->
        <div class="flex-1 p-5 flex items-center space-x-4">
            <div class="p-3 bg-violet-50 dark:bg-violet-950/20 text-violet-600 dark:text-violet-400 rounded-xl shadow-sm border border-violet-100/50 dark:border-violet-800/30 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div>
                <span class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Administrator</span>
                <span class="block text-2xl font-extrabold text-slate-800 dark:text-slate-100 font-mono tracking-tight leading-none mt-1">{{ $stats['admins'] }}</span>
            </div>
        </div>

        <!-- Stat Item 3 -->
        <div class="flex-1 p-5 flex items-center space-x-4">
            <div class="p-3 bg-amber-50 dark:bg-amber-950/20 text-amber-600 dark:text-amber-400 rounded-xl shadow-sm border border-amber-100/50 dark:border-amber-800/30 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
            </div>
            <div>
                <span class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Pegawai Lapangan</span>
                <span class="block text-2xl font-extrabold text-slate-800 dark:text-slate-100 font-mono tracking-tight leading-none mt-1">{{ $stats['employees'] }}</span>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-2xl shadow-sm">
        <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                        <th class="p-4">Nama Lengkap</th>
                        <th class="p-4">Username</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Role</th>
                        <th class="p-4">Pegawai Terhubung</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-600 dark:text-slate-300">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                            <td class="p-4 font-semibold text-slate-800 dark:text-slate-100 flex items-center space-x-3">
                                <div class="w-9 h-9 rounded-full bg-violet-600 text-white flex items-center justify-center font-bold text-xs select-none shadow-sm ring-2 ring-white dark:ring-slate-800">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                                <span>{{ $user->name }}</span>
                            </td>
                            <td class="p-4 font-mono text-slate-500 dark:text-slate-400">
                                {{ $user->username }}
                            </td>
                            <td class="p-4 text-slate-500 dark:text-slate-400">
                                {{ $user->email ?? '-' }}
                            </td>
                            <td class="p-4">
                                @if($user->role === 'Admin')
                                    <span class="px-2.5 py-1.5 bg-violet-50 dark:bg-violet-950/40 text-violet-600 dark:text-violet-400 border border-violet-200 dark:border-violet-800/50 rounded-lg text-[10px] font-extrabold tracking-wide uppercase shadow-sm">
                                        {{ $user->role }}
                                    </span>
                                @elseif($user->role === 'Employee')
                                    <span class="px-2.5 py-1.5 bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50 rounded-lg text-[10px] font-extrabold tracking-wide uppercase shadow-sm">
                                        {{ $user->role }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-1.5 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800/50 rounded-lg text-[10px] font-extrabold tracking-wide uppercase shadow-sm">
                                        {{ $user->role }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($user->employee)
                                    <span class="text-slate-800 dark:text-slate-200 font-medium">{{ $user->employee->name }}</span>
                                    <span class="block text-[10px] text-slate-400">{{ $user->employee->role }}</span>
                                @else
                                    <span class="text-slate-400 italic">Tidak Terhubung</span>
                                @endif
                            </td>
                            <td class="p-4 text-right space-x-2 shrink-0">
                                <button onclick="openEditModal({{ json_encode($user) }})" class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 rounded-lg transition cursor-pointer inline-flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                    </svg>
                                </button>
                                @if($user->id !== session('user.id'))
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 hover:bg-rose-50 dark:hover:bg-rose-950/20 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 rounded-lg transition cursor-pointer inline-flex">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">
                                Belum ada akun user yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- Modal Create / Edit User -->
<div id="userModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden">
    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform transition-all">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between bg-slate-50 dark:bg-slate-800/50">
            <h3 id="modalTitle" class="text-sm font-bold text-slate-700 dark:text-slate-200">Tambah Akun User Baru</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="userForm" method="POST" action="{{ route('users.store') }}" class="p-6 space-y-4">
            @csrf
            <div id="methodContainer"></div>

            <div>
                <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Nama Lengkap</label>
                <input type="text" name="name" id="userName" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-violet-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Username</label>
                    <input type="text" name="username" id="userUsername" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-violet-500">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Role</label>
                    <select name="role" id="userRole" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-violet-500 cursor-pointer">
                        @foreach($roles as $r)
                            <option value="{{ $r }}">{{ $r }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Email</label>
                <input type="email" name="email" id="userEmail" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-violet-500">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Password</label>
                <input type="password" name="password" id="userPassword" placeholder="Masukkan password..." class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-violet-500">
                <span id="passwordHelp" class="block text-[9px] text-slate-400 mt-1 hidden">*Biarkan kosong jika tidak ingin mengubah password.</span>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Hubungkan Pegawai (Opsional)</label>
                <select name="employee_id" id="userEmployeeId" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-violet-500 cursor-pointer">
                    <option value="">-- Tidak Dihubungkan --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->role }} - {{ $emp->regional }})</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end space-x-2 pt-4">
                <button type="button" onclick="closeModal()" class="px-4 py-2 border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 text-slate-600 dark:text-slate-400 rounded-xl text-xs font-semibold cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white rounded-xl text-xs font-bold shadow-sm cursor-pointer">
                    Simpan User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('userModal');
    const form = document.getElementById('userForm');
    const modalTitle = document.getElementById('modalTitle');
    const methodContainer = document.getElementById('methodContainer');
    const userName = document.getElementById('userName');
    const userUsername = document.getElementById('userUsername');
    const userRole = document.getElementById('userRole');
    const userEmail = document.getElementById('userEmail');
    const userPassword = document.getElementById('userPassword');
    const passwordHelp = document.getElementById('passwordHelp');
    const userEmployeeId = document.getElementById('userEmployeeId');

    function openCreateModal() {
        modalTitle.textContent = "Tambah Akun User Baru";
        form.action = "{{ route('users.store') }}";
        methodContainer.innerHTML = "";
        
        userName.value = "";
        userUsername.value = "";
        userRole.value = "Employee";
        userEmail.value = "";
        userPassword.value = "";
        userPassword.required = true;
        passwordHelp.classList.add('hidden');
        userEmployeeId.value = "";

        modal.classList.remove('hidden');
    }

    function openEditModal(user) {
        modalTitle.textContent = "Edit Akun User";
        form.action = "{{ url('dashboard/users') }}/" + user.id;
        methodContainer.innerHTML = '@method("PUT")';
        
        userName.value = user.name;
        userUsername.value = user.username;
        userRole.value = user.role;
        userEmail.value = user.email || "";
        userPassword.value = "";
        userPassword.required = false;
        passwordHelp.classList.remove('hidden');
        userEmployeeId.value = user.employee_id || "";

        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
    }
</script>
@endsection
