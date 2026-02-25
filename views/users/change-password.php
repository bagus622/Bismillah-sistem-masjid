<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Ubah Password</h1>
            <p class="text-slate-600 mt-1">Ubah password untuk: <strong><?= htmlspecialchars($user['name']) ?></strong></p>
        </div>
        <a href="<?= base_url('users') ?>" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition-colors flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali
        </a>
    </div>
    
    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 max-w-2xl">
        <form action="<?= base_url('users/updatePassword/' . $user['id']) ?>" method="POST" class="space-y-6">
            <!-- User Info -->
            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl">
                <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                    <?= strtoupper(substr($user['name'], 0, 2)) ?>
                </div>
                <div>
                    <p class="font-semibold text-slate-800"><?= htmlspecialchars($user['name']) ?></p>
                    <p class="text-sm text-slate-600"><?= htmlspecialchars($user['email']) ?></p>
                </div>
            </div>
            
            <!-- New Password -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Password Baru <span class="text-red-500">*</span>
                </label>
                <input type="password" name="password" required
                       class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all"
                       placeholder="Minimal 6 karakter">
                <p class="text-xs text-slate-500 mt-1">Password minimal 6 karakter</p>
            </div>
            
            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Konfirmasi Password <span class="text-red-500">*</span>
                </label>
                <input type="password" name="confirm_password" required
                       class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all"
                       placeholder="Ulangi password baru">
            </div>
            
            <!-- Warning Box -->
            <div class="flex items-start gap-3 p-4 bg-orange-50 border border-orange-200 rounded-xl">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-orange-600 flex-shrink-0 mt-0.5"></i>
                <div class="text-sm text-orange-800">
                    <p class="font-semibold mb-1">Perhatian!</p>
                    <p>Setelah password diubah, pengguna harus login ulang dengan password baru.</p>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all flex items-center gap-2">
                    <i data-lucide="key" class="w-4 h-4"></i>
                    Ubah Password
                </button>
                <a href="<?= base_url('users') ?>" 
                   class="px-6 py-3 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
        
        // Validate password match
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const password = document.querySelector('input[name="password"]').value;
            const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak cocok!');
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Password minimal 6 karakter!');
                return false;
            }
        });
    });
</script>
