<?php 
$pageTitle = $pageTitle ?? (getLang() === 'id' ? 'Edit Masjid' : 'Edit Mosque');
$mosque = $mosque ?? [];
?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="<?= base_url('mosques') ?>" class="p-2 rounded-lg hover:bg-slate-100 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5 text-slate-600"></i>
        </a>
        <h1 class="text-2xl font-bold text-slate-800"><?= getLang() === 'id' ? 'Edit Masjid' : 'Edit Mosque' ?></h1>
    </div>
    <p class="text-slate-500 text-sm ml-14"><?= getLang() === 'id' ? 'Perbarui informasi masjid' : 'Update mosque information' ?></p>
</div>

<!-- Form Card -->
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-6">
        <form action="<?= base_url('mosques/update/' . $mosque['id']) ?>" method="POST">
            <!-- Mosque Name -->
            <div class="mb-5">
                <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                    <?= getLang() === 'id' ? 'Nama Masjid' : 'Mosque Name' ?> <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       required
                       value="<?= sanitize($mosque['name'] ?? '') ?>"
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
                          placeholder="<?= getLang() === 'id' ? 'Masukkan alamat lengkap masjid' : 'Enter complete mosque address' ?>"><?= sanitize($mosque['address'] ?? '') ?></textarea>
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
                           value="<?= sanitize($mosque['phone'] ?? '') ?>"
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
                           value="<?= sanitize($mosque['email'] ?? '') ?>"
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
                    <?= getLang() === 'id' ? 'Simpan Perubahan' : 'Save Changes' ?>
                </button>
                <a href="<?= base_url('mosques') ?>" class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-2.5 border border-slate-300 text-slate-700 rounded-xl font-medium hover:bg-slate-50 transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                    <?= getLang() === 'id' ? 'Batal' : 'Cancel' ?>
                </a>
            </div>
        </form>
    </div>
    
    <!-- Danger Zone -->
    <div class="mt-6 bg-red-50 border border-red-200 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>
            </div>
            <div class="flex-1">
                <p class="font-medium text-red-800 text-sm mb-1"><?= getLang() === 'id' ? 'Zona Berbahaya' : 'Danger Zone' ?></p>
                <p class="text-sm text-red-700 mb-3"><?= getLang() === 'id' ? 'Menghapus masjid akan menghapus semua data terkait. Tindakan ini tidak dapat dibatalkan.' : 'Deleting this mosque will remove all related data. This action cannot be undone.' ?></p>
                <button type="button" 
                        data-mosque-id="<?= $mosque['id'] ?>" 
                        data-mosque-name="<?= htmlspecialchars(sanitize($mosque['name']), ENT_QUOTES) ?>"
                        onclick="confirmDelete(this.dataset.mosqueId, this.dataset.mosqueName)" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    <?= getLang() === 'id' ? 'Hapus Masjid' : 'Delete Mosque' ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm hidden items-center justify-center z-[100] transition-opacity duration-200 opacity-0">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform scale-95 transition-transform duration-200">
        <div class="text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="alert-triangle" class="w-8 h-8 text-red-600"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2"><?= getLang() === 'id' ? 'Hapus Masjid?' : 'Delete Mosque?' ?></h3>
            <p class="text-gray-500 mb-6">
                <?= getLang() === 'id' ? 'Apakah Anda yakin ingin menghapus' : 'Are you sure you want to delete' ?> 
                <span id="mosqueName" class="font-semibold text-gray-900"></span>? 
                <?= getLang() === 'id' ? 'Tindakan ini tidak dapat dibatalkan.' : 'This action cannot be undone.' ?>
            </p>
            <form id="deleteForm" method="GET" action="">
                <div class="flex gap-3 justify-center">
                    <button type="button" onclick="closeDeleteModal()" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors duration-200">
                        <?= getLang() === 'id' ? 'Batal' : 'Cancel' ?>
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                        <?= getLang() === 'id' ? 'Hapus' : 'Delete' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
    
    function confirmDelete(id, name) {
        console.log('Delete ID:', id, 'Name:', name); // Debug
        const modal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');
        const mosqueName = document.getElementById('mosqueName');
        
        // Use absolute URL to avoid relative path issues
        const baseUrl = 'http://localhost/project-basmalahCopy';
        const deleteUrl = baseUrl + '/mosques/delete/' + id;
        console.log('Delete URL:', deleteUrl); // Debug
        
        deleteForm.action = deleteUrl;
        mosqueName.textContent = name;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('.transform').classList.remove('scale-95');
            modal.querySelector('.transform').classList.add('scale-100');
            lucide.createIcons();
        }, 10);
    }
    
    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('opacity-0');
        modal.querySelector('.transform').classList.remove('scale-100');
        modal.querySelector('.transform').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }
    
    // Close modal on backdrop click
    document.getElementById('deleteModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
    
    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>
