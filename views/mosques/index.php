<?php 
$pageTitle = $pageTitle ?? (getLang() === 'id' ? 'Kelola Masjid' : 'Manage Mosques');
$mosques = $mosques ?? [];
?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800"><?= getLang() === 'id' ? 'Kelola Masjid' : 'Manage Mosques' ?></h1>
        <p class="text-slate-500 text-sm mt-1"><?= getLang() === 'id' ? 'Kelola semua masjid dalam sistem' : 'Manage all mosques in the system' ?></p>
    </div>
    <a href="<?= base_url('mosques/create') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-violet-600 text-white rounded-xl font-medium hover:bg-violet-700 transition-all duration-200 hover:shadow-lg hover:shadow-violet-200">
        <i data-lucide="plus" class="w-5 h-5"></i>
        <?= getLang() === 'id' ? 'Tambah Masjid' : 'Add Mosque' ?>
    </a>
</div>

<!-- Summary Card -->
<div class="bg-gradient-to-br from-violet-600 to-purple-700 rounded-2xl p-6 text-white mb-6 shadow-lg shadow-violet-200">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-violet-100 text-sm font-medium"><?= getLang() === 'id' ? 'Total Masjid' : 'Total Mosques' ?></p>
            <p class="text-3xl font-bold mt-1"><?= count($mosques) ?></p>
        </div>
        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
            <i data-lucide="building" class="w-7 h-7"></i>
        </div>
    </div>
</div>

<!-- Mosques Grid -->
<?php if (empty($mosques)): ?>
<!-- Empty State -->
<div class="bg-white rounded-2xl p-12 text-center shadow-md border border-slate-100">
    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i data-lucide="building" class="w-10 h-10 text-slate-400"></i>
    </div>
    <h3 class="text-lg font-semibold text-slate-800 mb-2">
        <?= getLang() === 'id' ? 'Belum ada masjid' : 'No mosques yet' ?>
    </h3>
    <p class="text-slate-500 mb-6">
        <?= getLang() === 'id' ? 'Mulai dengan menambahkan masjid pertama' : 'Start by adding your first mosque' ?>
    </p>
    <a href="<?= base_url('mosques/create') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-violet-600 text-white rounded-xl font-medium hover:bg-violet-700 transition-all">
        <i data-lucide="plus" class="w-5 h-5"></i>
        <?= getLang() === 'id' ? 'Tambah Masjid' : 'Add Mosque' ?>
    </a>
</div>
<?php else: ?>
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    <?php foreach ($mosques as $mosque): ?>
    <div class="bg-white rounded-2xl p-6 shadow-md border border-slate-100 card-hover group">
        <!-- Header -->
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="building" class="w-6 h-6 text-violet-600"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800"><?= sanitize($mosque['name']) ?></h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-violet-100 text-violet-700">
                        <?= $mosque['user_count'] ?> <?= getLang() === 'id' ? 'Pengguna' : 'Users' ?>
                    </span>
                </div>
            </div>
            
            <!-- Actions Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="p-2 rounded-lg hover:bg-slate-100 transition-colors">
                    <i data-lucide="more-vertical" class="w-5 h-5 text-slate-400"></i>
                </button>
                <div x-show="open" @click.outside="open = false" x-cloak
                     class="absolute right-0 mt-1 w-36 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-10">
                    <a href="<?= base_url('mosques/detail/' . $mosque['id']) ?>" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-slate-50">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                        <?= getLang() === 'id' ? 'Detail' : 'View' ?>
                    </a>
                    <a href="<?= base_url('mosques/edit/' . $mosque['id']) ?>" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-slate-50">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                        <?= getLang() === 'id' ? 'Edit' : 'Edit' ?>
                    </a>
                    <button type="button" 
                            data-mosque-id="<?= (int)$mosque['id'] ?>" 
                            data-mosque-name="<?= htmlspecialchars(sanitize($mosque['name']), ENT_QUOTES) ?>"
                            onclick="return confirmDelete(<?= (int)$mosque['id'] ?>, this.dataset.mosqueName);" 
                            class="flex items-center gap-2 px-4 py-2 text-red-600 hover:bg-red-50 w-full text-left">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        <?= getLang() === 'id' ? 'Hapus' : 'Delete' ?>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Info -->
        <div class="space-y-2 mb-4">
            <?php if (!empty($mosque['address'])): ?>
            <div class="flex items-start gap-2 text-sm">
                <i data-lucide="map-pin" class="w-4 h-4 text-slate-400 mt-0.5"></i>
                <span class="text-slate-600"><?= sanitize($mosque['address']) ?></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($mosque['phone'])): ?>
            <div class="flex items-center gap-2 text-sm">
                <i data-lucide="phone" class="w-4 h-4 text-slate-400"></i>
                <span class="text-slate-600"><?= sanitize($mosque['phone']) ?></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($mosque['email'])): ?>
            <div class="flex items-center gap-2 text-sm">
                <i data-lucide="mail" class="w-4 h-4 text-slate-400"></i>
                <span class="text-slate-600"><?= sanitize($mosque['email']) ?></span>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Divider -->
        <hr class="border-slate-100 my-4">
        
        <!-- Stats -->
        <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="bg-slate-50 rounded-lg p-3">
                <p class="text-xs text-slate-500"><?= getLang() === 'id' ? 'Pengguna' : 'Users' ?></p>
                <p class="text-lg font-bold text-slate-800"><?= $mosque['user_count'] ?></p>
            </div>
            <div class="bg-slate-50 rounded-lg p-3">
                <p class="text-xs text-slate-500"><?= getLang() === 'id' ? 'Transaksi' : 'Transactions' ?></p>
                <p class="text-lg font-bold text-slate-800"><?= $mosque['transaction_count'] ?></p>
            </div>
        </div>
        
        <!-- Footer Actions -->
        <div class="flex items-center gap-2">
            <a href="<?= base_url('mosques/detail/' . $mosque['id']) ?>" class="flex-1 flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-violet-50 text-violet-600 hover:bg-violet-100 transition-colors text-sm">
                <i data-lucide="eye" class="w-4 h-4"></i>
                <?= getLang() === 'id' ? 'Detail' : 'View Details' ?>
            </a>
            <a href="<?= base_url('mosques/edit/' . $mosque['id']) ?>" class="flex-1 flex items-center justify-center gap-2 px-3 py-2 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors text-sm">
                <i data-lucide="pencil" class="w-4 h-4"></i>
                <?= getLang() === 'id' ? 'Edit' : 'Edit' ?>
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

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
            <form id="deleteForm" method="POST" action="">
                <input type="hidden" name="_token" value="<?= csrf_token() ?>">
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

<script src="<?= base_url('public/js/mosques.js') ?>?v=<?= time() ?>"></script>
<script>
// Fallback inline script
if (typeof confirmDelete === 'undefined') {
    console.error('mosques.js failed to load! Using inline fallback.');
    
    function confirmDelete(id, name) {
        console.log('Inline confirmDelete called - ID:', id, 'Name:', name);
        const modal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');
        const mosqueName = document.getElementById('mosqueName');
        
        if (!modal || !deleteForm || !mosqueName) {
            alert('Error: Modal elements not found!');
            return false;
        }
        
        // Ensure id is a number
        id = parseInt(id, 10);
        if (isNaN(id) || id <= 0) {
            alert('Error: Invalid mosque ID!');
            return false;
        }
        
        const baseUrl = 'http://localhost/project-basmalahCopy';
        const deleteUrl = baseUrl + '/mosques/delete/' + id;
        console.log('Delete URL:', deleteUrl);
        
        deleteForm.action = deleteUrl;
        mosqueName.textContent = name;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('.transform').classList.remove('scale-95');
            modal.querySelector('.transform').classList.add('scale-100');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }, 10);
        
        return false;
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
}
</script>
