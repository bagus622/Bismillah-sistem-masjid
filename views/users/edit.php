<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Pengguna</h1>
            <p class="text-slate-600 mt-1">Ubah informasi pengguna</p>
        </div>
        <a href="<?= base_url('users') ?>" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition-colors flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali
        </a>
    </div>
    
    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
        <form action="<?= base_url('users/update/' . $user['id']) ?>" method="POST" class="space-y-6">
            <!-- Name -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required
                       class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all"
                       placeholder="Nama lengkap pengguna">
            </div>
            
            <!-- Email (Read Only) -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Email
                </label>
                <input type="email" value="<?= htmlspecialchars($user['email']) ?>" readonly
                       class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl bg-slate-50 text-slate-500 cursor-not-allowed"
                       placeholder="Email pengguna">
                <p class="text-xs text-slate-500 mt-1">Email tidak dapat diubah</p>
            </div>
            
            <!-- Phone -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    No. Telepon
                </label>
                <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                       class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all"
                       placeholder="08123456789">
            </div>
            
            <!-- Role -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Role <span class="text-red-500">*</span>
                </label>
                <select name="role" required
                        class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all">
                    <?php foreach ($availableRoles as $roleKey => $roleName): ?>
                    <option value="<?= $roleKey ?>" <?= $user['role'] === $roleKey ? 'selected' : '' ?>>
                        <?= $roleName ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <p class="text-xs text-slate-500 mt-1">Pilih role sesuai dengan tanggung jawab pengguna</p>
            </div>
            
            <!-- Info Box -->
            <div class="flex items-start gap-3 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                <i data-lucide="info" class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5"></i>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Informasi Role:</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        <li><strong>Super Admin:</strong> Akses penuh ke semua fitur</li>
                        <li><strong>Admin:</strong> Kelola semua data kecuali user management</li>
                        <li><strong>Akuntan:</strong> Kelola transaksi, kategori, dan laporan</li>
                        <li><strong>Bendahara:</strong> Kelola transaksi dan setoran goal</li>
                        <li><strong>Anggota:</strong> Hanya dapat melihat data</li>
                    </ul>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Simpan Perubahan
                </button>
                <a href="<?= base_url('users/changePassword/' . $user['id']) ?>" 
                   class="px-6 py-3 bg-orange-500 text-white rounded-xl font-semibold hover:bg-orange-600 transition-colors flex items-center gap-2">
                    <i data-lucide="key" class="w-4 h-4"></i>
                    Ubah Password
                </a>
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
    });
</script>
