<?php 
$pageTitle = $pageTitle ?? trans('accounts');
$accounts = $accounts ?? [];
$totalBalance = $totalBalance ?? 0;
?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800"><?= trans('accounts') ?></h1>
        <p class="text-slate-500 text-sm mt-1"><?= getLang() === 'id' ? 'Kelola akun kas dan bank' : 'Manage your cash and bank accounts' ?></p>
    </div>
    <?php if (can('accounts.create')): ?>
    <a href="<?= base_url('accounts/create') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 transition-all duration-200 hover:shadow-lg hover:shadow-indigo-200">
        <i data-lucide="plus" class="w-5 h-5"></i>
        <?= trans('add_account') ?>
    </a>
    <?php endif; ?>
</div>

<!-- Grand Total Card -->
<div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl p-6 text-white mb-6 shadow-lg shadow-indigo-200">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-indigo-100 text-sm font-medium"><?= getLang() === 'id' ? 'Total Saldo' : 'Grand Total Balance' ?></p>
            <p class="text-3xl font-bold mt-1"><?= formatCurrency($totalBalance) ?></p>
        </div>
        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
            <i data-lucide="wallet" class="w-7 h-7"></i>
        </div>
    </div>
</div>

<!-- Accounts Grid -->
<?php if (empty($accounts)): ?>
<!-- Empty State -->
<div class="bg-white rounded-2xl p-12 text-center shadow-md border border-slate-100">
    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i data-lucide="wallet" class="w-10 h-10 text-slate-400"></i>
    </div>
    <h3 class="text-lg font-semibold text-slate-800 mb-2">
        <?= getLang() === 'id' ? 'Belum ada akun' : 'No accounts yet' ?>
    </h3>
    <p class="text-slate-500 mb-6">
        <?= getLang() === 'id' ? 'Mulai dengan menambahkan akun pertama Anda' : 'Start by adding your first account' ?>
    </p>
    <?php if (can('accounts.create')): ?>
    <a href="<?= base_url('accounts/create') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 transition-all">
        <i data-lucide="plus" class="w-5 h-5"></i>
        <?= getLang() === 'id' ? 'Tambah Akun' : 'Add Account' ?>
    </a>
    <?php endif; ?>
</div>
<?php else: ?>
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    <?php foreach ($accounts as $account): ?>
    <div class="bg-white rounded-2xl p-6 shadow-md border border-slate-100 card-hover group">
        <!-- Header -->
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 <?= ($account['type'] ?? 'cash') === 'bank' ? 'bg-blue-100' : 'bg-emerald-100' ?> rounded-xl flex items-center justify-center">
                    <i data-lucide="<?= ($account['type'] ?? 'cash') === 'bank' ? 'building-2' : 'banknote' ?>" class="w-6 h-6 <?= ($account['type'] ?? 'cash') === 'bank' ? 'text-blue-600' : 'text-emerald-600' ?>"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800"><?= $account['name'] ?></h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= ($account['type'] ?? 'cash') === 'bank' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700' ?>">
                        <?= ($account['type'] ?? 'cash') === 'bank' ? (getLang() === 'id' ? 'Bank' : 'Bank') : (getLang() === 'id' ? 'Tunai' : 'Cash') ?>
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
                    <?php if (can('accounts.edit')): ?>
                    <a href="<?= base_url('accounts/edit/' . $account['id']) ?>" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-slate-50">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                        <?= getLang() === 'id' ? 'Edit' : 'Edit' ?>
                    </a>
                    <?php endif; ?>
                    <?php if (can('accounts.delete')): ?>
                    <button onclick="confirmDelete(<?= $account['id'] ?>)" class="flex items-center gap-2 px-4 py-2 text-red-600 hover:bg-red-50">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        <?= getLang() === 'id' ? 'Hapus' : 'Delete' ?>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Balance -->
        <div class="mb-4">
            <p class="text-slate-500 text-xs uppercase tracking-wide"><?= getLang() === 'id' ? 'Saldo Saat Ini' : 'Current Balance' ?></p>
            <p class="text-2xl font-bold text-slate-800 mt-1"><?= formatCurrency($account['balance'] ?? 0) ?></p>
        </div>
        
        <!-- Divider -->
        <hr class="border-slate-100 my-4">
        
        <!-- Details -->
        <div class="space-y-2">
            <?php if (!empty($account['account_number'])): ?>
            <div class="flex items-center justify-between text-sm">
                <span class="text-slate-500"><?= getLang() === 'id' ? 'Nomor Rekening' : 'Account Number' ?></span>
                <span class="font-medium text-slate-700"><?= $account['account_number'] ?></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($account['description'])): ?>
            <div class="flex items-start justify-between text-sm">
                <span class="text-slate-500"><?= getLang() === 'id' ? 'Deskripsi' : 'Description' ?></span>
                <span class="text-slate-700 text-right max-w-[60%]"><?= $account['description'] ?></span>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Footer Actions -->
        <div class="flex items-center gap-2 mt-4 pt-4 border-t border-slate-100">
            <?php if (can('accounts.edit')): ?>
            <a href="<?= base_url('accounts/edit/' . $account['id']) ?>" class="flex-1 flex items-center justify-center gap-2 px-3 py-2 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors text-sm">
                <i data-lucide="pencil" class="w-4 h-4"></i>
                <?= getLang() === 'id' ? 'Edit' : 'Edit' ?>
            </a>
            <?php endif; ?>
            <?php if (can('transactions.view')): ?>
            <a href="<?= base_url('transactions?account=' . $account['id']) ?>" class="flex-1 flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors text-sm">
                <i data-lucide="list" class="w-4 h-4"></i>
                <?= getLang() === 'id' ? 'Detail Transaksi' : 'Transaction Details' ?>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
    
    function confirmDelete(id) {
        const modal = document.getElementById('deleteModal');
        const deleteLink = document.getElementById('deleteLink');
        
        deleteLink.href = '<?= base_url('accounts/delete/') ?>' + id;
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('div').classList.remove('scale-95');
            modal.querySelector('div').classList.add('scale-100');
        }, 10);
    }
    
    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
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

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm hidden items-center justify-center z-50 transition-opacity duration-200 opacity-0">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform scale-95 transition-transform duration-200">
        <div class="text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="alert-triangle" class="w-8 h-8 text-red-600"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2"><?= trans('confirm_delete') ?></h3>
            <p class="text-gray-500 mb-6"><?= getLang() === 'id' ? 'Tindakan ini tidak dapat dibatalkan. Akun akan dihapus secara permanen.' : 'This action cannot be undone. The account will be permanently deleted.' ?></p>
            <div class="flex gap-3 justify-center">
                <button onclick="closeDeleteModal()" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors duration-200">
                    <?= getLang() === 'id' ? 'Batal' : 'Cancel' ?>
                </button>
                <a id="deleteLink" href="#" class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                    <?= trans('delete') ?>
                </a>
            </div>
        </div>
    </div>
</div>
