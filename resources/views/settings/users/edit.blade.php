@extends('layouts.app')

@section('title', 'User - Edit — Tanos ERP')

@section('content')
<div class="space-y-6" x-data="{
    activeTab: 'role_group',
    changePassword: false,
    selectedEmployeeId: '{{ $user->employee_id ?? '' }}',
    selectedEmployeeName: '{{ $user->employee ? $user->employee->name . ' (' . ($user->employee->nipp ?? '-') . ')' : '' }}',
    showEmployeeModal: false,
    roleGroups: {{ json_encode($userRoleGroups) }},
    addRoleRow() {
        this.roleGroups.push({
            role_group: 'IT Pemagang',
            module: 'General',
            action: 'All',
            table: '',
            column: '',
            value: ''
        });
    },
    removeRoleRow(index) {
        if (this.roleGroups.length > 1) {
            this.roleGroups.splice(index, 1);
        } else {
            alert('Minimal satu Role Group harus terdaftar.');
        }
    },
    selectEmployee(id, name, nipp) {
        this.selectedEmployeeId = id;
        this.selectedEmployeeName = name + ' (' + nipp + ')';
        this.showEmployeeModal = false;
    },
    clearEmployee() {
        this.selectedEmployeeId = '';
        this.selectedEmployeeName = '';
    }
}">

    <!-- Page Header & Action matching Tanos Screenshot -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <!-- Breadcrumbs -->
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 dark:text-slate-500 mb-1.5">
                <a href="{{ route('dashboard.index') }}" class="hover:text-primary transition flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                    Home
                </a>
                <span>/</span>
                <span>General</span>
                <span>/</span>
                <span>Setting</span>
                <span>/</span>
                <a href="{{ route('users.index') }}" class="hover:text-primary transition">User</a>
                <span>/</span>
                <span class="text-primary font-black">Edit</span>
            </div>

            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">
                User
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-medium">
                Manage Users.
            </p>
        </div>

        <a href="{{ route('users.index') }}"
           class="px-4 py-2 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 self-start sm:self-auto shadow-xs cursor-pointer">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            <span>Back</span>
        </a>
    </div>

    <!-- Alert Notification -->
    @if($errors->any())
    <div class="p-4 bg-rose-50 dark:bg-rose-950/30 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 rounded-2xl text-xs font-bold space-y-1">
        @foreach($errors->all() as $err)
            <div class="flex items-center space-x-1.5">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>{{ $err }}</span>
            </div>
        @endforeach
    </div>
    @endif

    <!-- Main Card matching Screenshot: User - Edit -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6">
        
        <div class="pb-4 border-b border-slate-100 dark:border-slate-800">
            <h2 class="text-base font-black text-slate-800 dark:text-slate-100">
                User - Edit
            </h2>
        </div>

        <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-5 text-xs">
            @csrf
            @method('PUT')

            <!-- Hidden input holding JSON of dynamic role groups -->
            <input type="hidden" name="role_groups_data" :value="JSON.stringify(roleGroups)">
            <input type="hidden" name="employee_id" :value="selectedEmployeeId">

            <div class="space-y-4 max-w-4xl">
                <!-- Name -->
                <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-2">
                    <label class="font-bold text-slate-700 dark:text-slate-300">
                        Name <span class="text-rose-500">*</span>
                    </label>
                    <div class="md:col-span-3">
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>

                <!-- Username -->
                <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-2">
                    <label class="font-bold text-slate-700 dark:text-slate-300">
                        Username <span class="text-rose-500">*</span>
                    </label>
                    <div class="md:col-span-3">
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 rounded-xl font-mono font-bold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>

                <!-- Jabatan -->
                <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-2">
                    <label class="font-bold text-slate-700 dark:text-slate-300">
                        Jabatan
                    </label>
                    <div class="md:col-span-3">
                        <input type="text" name="jabatan" value="{{ old('jabatan', $user->jabatan ?? 'Jabatan') }}" placeholder="Jabatan"
                               class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 rounded-xl font-medium text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>

                <!-- Email -->
                <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-2">
                    <label class="font-bold text-slate-700 dark:text-slate-300">
                        Email <span class="text-rose-500">*</span>
                    </label>
                    <div class="md:col-span-3">
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>

                <!-- Change Password Checkbox -->
                <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-2">
                    <div></div>
                    <div class="md:col-span-3">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" x-model="changePassword"
                                   class="w-4 h-4 text-primary rounded border-slate-300 focus:ring-primary">
                            <span class="font-bold text-slate-700 dark:text-slate-300">Change Password</span>
                        </label>
                    </div>
                </div>

                <!-- Password (Conditional) -->
                <div x-show="changePassword" x-transition class="space-y-4" style="display: none;">
                    <div class="grid grid-cols-1 md:grid-cols-4 items-start gap-2">
                        <label class="font-bold text-slate-700 dark:text-slate-300 pt-2.5">
                            Password <span class="text-rose-500">*</span>
                        </label>
                        <div class="md:col-span-3 space-y-1.5">
                            <input type="password" name="password" placeholder="Password"
                                   class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary/20">
                            <p class="text-[11px] text-slate-400 dark:text-slate-500 font-medium">
                                Use 8 or more characters with a mix of letters, numbers & symbols.
                            </p>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-2">
                        <label class="font-bold text-slate-700 dark:text-slate-300">
                            Confirm Password <span class="text-rose-500">*</span>
                        </label>
                        <div class="md:col-span-3">
                            <input type="password" name="password_confirmation" placeholder="Confirm Password"
                                   class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary/20">
                        </div>
                    </div>
                </div>

                <!-- Employee Picker -->
                <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-2">
                    <label class="font-bold text-slate-700 dark:text-slate-300">
                        Employee
                    </label>
                    <div class="md:col-span-3 flex items-center space-x-1.5">
                        <input type="text" readonly :value="selectedEmployeeName" placeholder="Pilih Pegawai / Employee..."
                               class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-100 font-semibold focus:outline-none">
                        
                        <button type="button" @click="showEmployeeModal = true"
                                class="p-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl transition shadow-xs cursor-pointer" title="Cari Pegawai">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </button>

                        <button type="button" @click="clearEmployee()" x-show="selectedEmployeeId"
                                class="p-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl transition shadow-xs cursor-pointer" title="Hapus Pemetaan Pegawai">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Bottom Tabs Area matching Screenshot -->
            <div class="pt-6 space-y-4">
                <div class="flex items-center space-x-2 border-b border-slate-200 dark:border-slate-800">
                    <button type="button" @click="activeTab = 'role_group'"
                            :class="activeTab === 'role_group' ? 'border-b-2 border-primary text-primary font-black' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 font-bold'"
                            class="px-4 py-2 text-xs transition cursor-pointer">
                        Role Group
                    </button>
                    <button type="button" @click="activeTab = 'approval_authority'"
                            :class="activeTab === 'approval_authority' ? 'border-b-2 border-primary text-primary font-black' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 font-bold'"
                            class="px-4 py-2 text-xs transition cursor-pointer">
                        Approval Authority
                    </button>
                </div>

                <!-- TAB 1: Role Group Dynamic Table -->
                <div x-show="activeTab === 'role_group'" class="space-y-4">
                    <div class="flex items-center justify-start">
                        <button type="button" @click="addRoleRow()"
                                class="px-4 py-2 bg-primary hover:bg-primary-hover text-white font-bold text-xs rounded-xl shadow-md shadow-primary transition flex items-center space-x-1.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            <span>Add Role</span>
                        </button>
                    </div>

                    <!-- Matrix Table matching Tanos Screenshot -->
                    <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-800 text-[11px] font-black text-slate-700 dark:text-slate-200">
                                    <th rowspan="2" class="py-3 px-3 w-12 text-center border-r border-slate-200 dark:border-slate-700">No</th>
                                    <th rowspan="2" class="py-3 px-4 min-w-[180px] border-r border-slate-200 dark:border-slate-700">Role Group</th>
                                    <th colspan="5" class="py-2 px-4 text-center border-b border-slate-200 dark:border-slate-700 bg-slate-100/70 dark:bg-slate-800">Scope Data</th>
                                    <th rowspan="2" class="py-3 px-3 w-14 text-center">Action</th>
                                </tr>
                                <tr class="bg-slate-50/90 dark:bg-slate-800/60 text-[11px] font-bold text-slate-600 dark:text-slate-400">
                                    <th class="py-2 px-3 min-w-[150px]">Module</th>
                                    <th class="py-2 px-3 min-w-[120px]">Action</th>
                                    <th class="py-2 px-3 min-w-[130px]">Table</th>
                                    <th class="py-2 px-3 min-w-[130px]">Column</th>
                                    <th class="py-2 px-3 min-w-[180px]">Value</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                                <template x-for="(row, idx) in roleGroups" :key="idx">
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                                        <!-- No -->
                                        <td class="py-2.5 px-3 text-center font-bold font-mono text-slate-500 border-r border-slate-200 dark:border-slate-800" x-text="idx + 1"></td>
                                        
                                        <!-- Role Group Dropdown -->
                                        <td class="py-2.5 px-3 border-r border-slate-200 dark:border-slate-800">
                                            <select x-model="row.role_group"
                                                    class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-bold text-slate-800 dark:text-slate-200 focus:outline-none">
                                                @foreach($availableRoleGroups as $rg)
                                                    <option value="{{ $rg }}">{{ $rg }}</option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <!-- Module Dropdown -->
                                        <td class="py-2.5 px-3">
                                            <select x-model="row.module"
                                                    class="w-full px-2 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:outline-none">
                                                @foreach($modules as $mod)
                                                    <option value="{{ $mod }}">{{ $mod }}</option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <!-- Action Dropdown -->
                                        <td class="py-2.5 px-3">
                                            <select x-model="row.action"
                                                    class="w-full px-2 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:outline-none">
                                                @foreach($actions as $act)
                                                    <option value="{{ $act }}">{{ $act }}</option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <!-- Table Dropdown -->
                                        <td class="py-2.5 px-3">
                                            <input type="text" x-model="row.table" placeholder="Table"
                                                   class="w-full px-2 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:outline-none">
                                        </td>

                                        <!-- Column Dropdown -->
                                        <td class="py-2.5 px-3">
                                            <input type="text" x-model="row.column" placeholder="Column"
                                                   class="w-full px-2 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:outline-none">
                                        </td>

                                        <!-- Value -->
                                        <td class="py-2.5 px-3">
                                            <div class="flex items-center space-x-1">
                                                <input type="text" x-model="row.value" placeholder="Value"
                                                       class="w-full px-2 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:outline-none">
                                                <button type="button" class="p-1.5 bg-amber-500 text-white rounded-lg hover:bg-amber-600 cursor-pointer" title="Cari Value">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                                </button>
                                                <button type="button" @click="row.value = ''" class="p-1.5 bg-rose-600 text-white rounded-lg hover:bg-rose-700 cursor-pointer" title="Hapus Value">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                        </td>

                                        <!-- Row Action Delete Button -->
                                        <td class="py-2.5 px-3 text-center">
                                            <button type="button" @click="removeRoleRow(idx)"
                                                    class="p-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg transition cursor-pointer shadow-xs" title="Hapus Baris Role">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 2: Approval Authority -->
                <div x-show="activeTab === 'approval_authority'" style="display: none;" class="space-y-4">
                    <div class="p-4 bg-slate-50 dark:bg-slate-800/60 rounded-2xl border border-slate-200 dark:border-slate-700 space-y-3">
                        <h4 class="font-bold text-slate-800 dark:text-slate-100">Batas Otorisasi & Persetujuan Dokumen</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Limit Approval Anggaran RAB (Rp)</label>
                                <input type="number" name="approval_authority[rab_limit]" value="{{ $user->approval_authority['rab_limit'] ?? 100000000 }}"
                                       class="w-full px-3.5 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Limit Approval Faktur / Invoice (Rp)</label>
                                <input type="number" name="approval_authority[invoice_limit]" value="{{ $user->approval_authority['invoice_limit'] ?? 100000000 }}"
                                       class="w-full px-3.5 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Save Button matching Screenshot -->
            <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
                <button type="submit"
                        class="px-6 py-2.5 bg-primary hover:bg-primary-hover text-white font-bold rounded-xl shadow-lg shadow-primary transition cursor-pointer">
                    Save
                </button>
            </div>

        </form>

    </div>

    <!-- Modal Picker Employee -->
    <div x-show="showEmployeeModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="showEmployeeModal = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-xl bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-base font-black text-slate-800 dark:text-slate-100">Pilih Data Pegawai / Employee</h3>
                    <button type="button" @click="showEmployeeModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <div class="max-h-80 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                    @foreach($employees as $emp)
                    <div class="py-2.5 px-3 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-800/50 rounded-xl transition cursor-pointer"
                         @click="selectEmployee('{{ $emp->id }}', '{{ $emp->name }}', '{{ $emp->nipp ?? '-' }}')">
                        <div>
                            <span class="font-bold text-slate-800 dark:text-slate-200 block">{{ $emp->name }}</span>
                            <span class="text-slate-400 font-mono text-[11px]">NIPP: {{ $emp->nipp ?? '-' }} • {{ $emp->position ?? 'Staff' }}</span>
                        </div>
                        <button type="button" class="px-3 py-1 bg-primary text-white font-bold rounded-lg text-xs">Pilih</button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
