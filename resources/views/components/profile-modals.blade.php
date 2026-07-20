@php
    $dbUser = null;
    if (session()->has('user')) {
        $dbUser = \App\Models\User::where('email', session('user.username'))->first();
    }
@endphp
<div x-data="profileModalsData()"
     @open-profile-modal.window="showProfileModal = true"
     @open-password-modal.window="showPasswordModal = true">

    <!-- Toast Notification -->
    <div x-show="toast.show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed bottom-6 right-6 z-[1100] flex items-center p-4 mb-4 text-white bg-emerald-500 rounded-xl shadow-[0_10px_25px_-5px_rgba(16,185,129,0.3)]" role="alert" style="display: none;">
        <svg class="w-5 h-5 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
        </svg>
        <span class="text-sm font-semibold" x-text="toast.message"></span>
        <button type="button" @click="toast.show = false" class="ml-4 -mx-1.5 -my-1.5 bg-emerald-500 text-emerald-100 hover:text-white rounded-lg focus:ring-2 focus:ring-emerald-400 p-1.5 hover:bg-emerald-600 inline-flex items-center justify-center h-8 w-8 transition-colors">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>

    <!-- Edit Profile Modal -->
    <div x-show="showProfileModal" style="display: none;" class="fixed inset-0 z-[1050] overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        
        <div x-show="showProfileModal" @click.away="showProfileModal = false"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white dark:bg-slate-900 rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-100 dark:border-slate-800">
            
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Edit Profil</h3>
                <button type="button" @click="showProfileModal = false" class="text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form @submit.prevent="submitEditProfile" class="space-y-4">
                
                <!-- Photo Upload -->
                <div class="flex flex-col items-center mb-4">
                    <div class="relative">
                        <img :src="profilePreview || '{{ ($dbUser && $dbUser->photo) ? asset('storage/' . $dbUser->photo) : 'https://ui-avatars.com/api/?name='.urlencode(session('user.name', 'Guest')).'&color=ffffff&background=2563eb' }}'" 
                             class="w-20 h-20 rounded-full object-cover border border-slate-200 dark:border-slate-800 shadow-sm" alt="Foto Profil" id="navbar-avatar-preview">
                        
                        <label for="inputPhoto" class="absolute bottom-0 right-0 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 rounded-full p-1.5 shadow border border-slate-200 dark:border-slate-700 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </label>
                        <input type="file" id="inputPhoto" class="hidden" accept=".jpg,.jpeg,.png" @change="previewPhoto">
                    </div>
                </div>

                <!-- Inputs -->
                <div>
                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Nama Lengkap</label>
                    <input type="text" x-model="profile.name" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-700 dark:text-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500" :class="{'border-red-500': errors.name}">
                    <p x-show="errors.name" class="text-xs text-red-500 mt-1" x-text="errors.name"></p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Email</label>
                    <input type="email" x-model="profile.email" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-700 dark:text-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500" :class="{'border-red-500': errors.email}">
                    <p x-show="errors.email" class="text-xs text-red-500 mt-1" x-text="errors.email"></p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Nomor HP (Opsional)</label>
                    <input type="text" x-model="profile.phone" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-700 dark:text-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500" :class="{'border-red-500': errors.phone}">
                    <p x-show="errors.phone" class="text-xs text-red-500 mt-1" x-text="errors.phone"></p>
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" @click="showProfileModal = false" class="px-4 py-2 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50">Batal</button>
                    <button type="submit" :disabled="isLoadingProfile" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium shadow-sm disabled:opacity-70">
                        <span x-show="!isLoadingProfile">Simpan Perubahan</span>
                        <span x-show="isLoadingProfile">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div x-show="showPasswordModal" style="display: none;" class="fixed inset-0 z-[1050] overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
         
        <div x-show="showPasswordModal" @click.away="showPasswordModal = false"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white dark:bg-slate-900 rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-100 dark:border-slate-800">
            
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Ubah Password</h3>
                <button type="button" @click="showPasswordModal = false" class="text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                </button>
            </div>
            
            <form @submit.prevent="submitChangePassword" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Password Saat Ini</label>
                    <input type="password" x-model="password.current_password" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-700 dark:text-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500" :class="{'border-red-500': errors.current_password}">
                    <p x-show="errors.current_password" class="text-xs text-red-500 mt-1" x-text="errors.current_password"></p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Password Baru</label>
                    <input type="password" x-model="password.password" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-700 dark:text-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500" :class="{'border-red-500': errors.password}">
                    <p x-show="errors.password" class="text-xs text-red-500 mt-1" x-text="errors.password"></p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Konfirmasi Password Baru</label>
                    <input type="password" x-model="password.password_confirmation" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-700 dark:text-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" @click="showPasswordModal = false" class="px-4 py-2 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50">Batal</button>
                    <button type="submit" :disabled="isLoadingPassword" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium shadow-sm disabled:opacity-70">
                        <span x-show="!isLoadingPassword">Ubah Password</span>
                        <span x-show="isLoadingPassword">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('profileModalsData', () => ({
        showProfileModal: false,
        showPasswordModal: false,
        isLoadingProfile: false,
        isLoadingPassword: false,
        profilePreview: null,
        
        toast: {
            show: false,
            message: ''
        },
        
        profile: {
            name: '{{ $dbUser?->name ?? session("user.name", "") }}',
            email: '{{ $dbUser?->email ?? session("user.username", "") }}',
            phone: '{{ $dbUser?->phone ?? "" }}'
        },
        
        password: {
            current_password: '',
            password: '',
            password_confirmation: ''
        },
        
        errors: {},

        showToast(msg) {
            this.toast.message = msg;
            this.toast.show = true;
            setTimeout(() => {
                this.toast.show = false;
            }, 3000);
        },

        previewPhoto(event) {
            const file = event.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file maksimal 2MB');
                    event.target.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.profilePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        async submitEditProfile() {
            this.isLoadingProfile = true;
            this.errors = {};
            
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('name', this.profile.name);
            formData.append('email', this.profile.email);
            formData.append('phone', this.profile.phone);
            
            const photoInput = document.getElementById('inputPhoto');
            if (photoInput.files.length > 0) {
                formData.append('photo', photoInput.files[0]);
            }

            try {
                const response = await fetch('{{ route("profile.update") }}', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json();

                if (!response.ok) {
                    if (response.status === 422) {
                        for (const key in data.errors) {
                            this.errors[key] = data.errors[key][0];
                        }
                    } else {
                        alert(data.message || 'Terjadi kesalahan');
                    }
                } else {
                    this.showProfileModal = false;
                    this.showToast('Profil berhasil diperbarui');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            } catch (error) {
                alert('Terjadi kesalahan pada server');
            } finally {
                this.isLoadingProfile = false;
            }
        },

        async submitChangePassword() {
            this.isLoadingPassword = true;
            this.errors = {};

            try {
                const response = await fetch('{{ route("profile.password") }}', {
                    method: 'PUT',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(this.password)
                });

                const data = await response.json();

                if (!response.ok) {
                    if (response.status === 422) {
                        for (const key in data.errors) {
                            this.errors[key] = data.errors[key][0];
                        }
                    } else {
                        alert(data.message || 'Terjadi kesalahan');
                    }
                } else {
                    this.showPasswordModal = false;
                    this.showToast('Password berhasil diperbarui');
                    this.password = {
                        current_password: '',
                        password: '',
                        password_confirmation: ''
                    };
                }
            } catch (error) {
                alert('Terjadi kesalahan pada server');
            } finally {
                this.isLoadingPassword = false;
            }
        }
    }));
});
</script>
