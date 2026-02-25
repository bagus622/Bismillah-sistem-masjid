<?php 
$pageTitle = $pageTitle ?? (getLang() === 'id' ? 'Tambah Masjid Baru' : 'Add New Mosque');
?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="<?= base_url('mosques') ?>" class="p-2 rounded-lg hover:bg-slate-100 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5 text-slate-600"></i>
        </a>
        <h1 class="text-2xl font-bold text-slate-800"><?= getLang() === 'id' ? 'Tambah Masjid Baru' : 'Add New Mosque' ?></h1>
    </div>
    <p class="text-slate-500 text-sm ml-14"><?= getLang() === 'id' ? 'Tambahkan masjid baru ke dalam sistem' : 'Add a new mosque to the system' ?></p>
</div>

<!-- Form Card -->
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-6">
        <form action="<?= base_url('mosques/store') ?>" method="POST">
            <!-- Mosque Name -->
            <div class="mb-5">
                <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                    <?= getLang() === 'id' ? 'Nama Masjid' : 'Mosque Name' ?> <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       required
                       class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-colors"
                       placeholder="<?= getLang() === 'id' ? 'Contoh: Masjid Al-Ikhlas' : 'e.g., Al-Ikhlas Mosque' ?>">
            </div>
            
            <!-- Address -->
            <div class="mb-5">
                <label for="address" class="block text-sm font-medium text-slate-700 mb-2">
                    <?= getLang() === 'id' ? 'Alamat' : 'Address' ?>
                </label>
                <textarea id="address" 
                          name="address" 
                          rows="3"
                          class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-colors"
                          placeholder="<?= getLang() === 'id' ? 'Masukkan alamat lengkap masjid' : 'Enter complete mosque address' ?>"></textarea>
            </div>
            
            <!-- Phone -->
            <div class="mb-5">
                <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">
                    <?= getLang() === 'id' ? 'Nomor Telepon' : 'Phone Number' ?>
                </label>
                <div class="relative">
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                        <i data-lucide="phone" class="w-5 h-5 text-slate-400"></i>
                    </div>
                    <input type="tel" 
                           id="phone" 
                           name="phone"
                           class="w-full pl-11 pr-4 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-colors"
                           placeholder="<?= getLang() === 'id' ? 'Contoh: 021-12345678' : 'e.g., 021-12345678' ?>">
                </div>
            </div>
            
            <!-- Email -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                    <?= getLang() === 'id' ? 'Email' : 'Email' ?>
                </label>
                <div class="relative">
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                        <i data-lucide="mail" class="w-5 h-5 text-slate-400"></i>
                    </div>
                    <input type="email" 
                           id="email" 
                           name="email"
                           class="w-full pl-11 pr-4 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-colors"
                           placeholder="<?= getLang() === 'id' ? 'Contoh: info@masjid.com' : 'e.g., info@mosque.com' ?>">
                </div>
            </div>
            
            <!-- Divider -->
            <hr class="border-slate-100 my-6">
            
            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-violet-600 text-white rounded-xl font-medium hover:bg-violet-700 transition-all duration-200 hover:shadow-lg hover:shadow-violet-200">
                    <i data-lucide="save" class="w-5 h-5"></i>
                    <?= getLang() === 'id' ? 'Simpan Masjid' : 'Save Mosque' ?>
                </button>
                <a href="<?= base_url('mosques') ?>" class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-2.5 border border-slate-300 text-slate-700 rounded-xl font-medium hover:bg-slate-50 transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                    <?= getLang() === 'id' ? 'Batal' : 'Cancel' ?>
                </a>
            </div>
        </form>
    </div>
    
    <!-- Info Card -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
        <div class="flex gap-3">
            <div class="flex-shrink-0">
                <i data-lucide="info" class="w-5 h-5 text-blue-600"></i>
            </div>
            <div class="text-sm text-blue-800">
                <p class="font-medium mb-1"><?= getLang() === 'id' ? 'Informasi' : 'Information' ?></p>
                <p><?= getLang() === 'id' ? 'Setelah masjid dibuat, Anda dapat menambahkan admin dan pengguna untuk masjid tersebut melalui menu Pengguna.' : 'After creating the mosque, you can add admins and users for this mosque through the Users menu.' ?></p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
