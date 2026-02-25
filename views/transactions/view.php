<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Transaksi</h1>
            <p class="text-slate-600 mt-1">Informasi lengkap transaksi</p>
        </div>
        <div class="flex items-center gap-3">
            <?php if (can('transactions.edit')): ?>
            <a href="<?= base_url('transactions/edit/' . $transaction['id']) ?>" 
               class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition-colors flex items-center gap-2">
                <i data-lucide="edit" class="w-4 h-4"></i>
                Edit
            </a>
            <?php endif; ?>
            <a href="<?= base_url('transactions') ?>" 
               class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition-colors flex items-center gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali
            </a>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Transaction Info Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <!-- Header with Type Badge -->
                <div class="p-6 <?= $transaction['type'] === 'income' ? 'bg-gradient-to-r from-green-50 to-emerald-50 border-b border-green-200' : 'bg-gradient-to-r from-red-50 to-rose-50 border-b border-red-200' ?>">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold <?= $transaction['type'] === 'income' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' ?>">
                                <i data-lucide="<?= $transaction['type'] === 'income' ? 'trending-up' : 'trending-down' ?>" class="w-4 h-4 mr-1"></i>
                                <?= $transaction['type'] === 'income' ? 'Pemasukan' : 'Pengeluaran' ?>
                            </span>
                            <?php if ($transaction['is_upcoming']): ?>
                            <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-orange-500 text-white">
                                <i data-lucide="clock" class="w-4 h-4 mr-1"></i>
                                Akan Datang
                            </span>
                            <?php endif; ?>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-slate-600">Nominal</p>
                            <p class="text-3xl font-bold <?= $transaction['type'] === 'income' ? 'text-green-600' : 'text-red-600' ?>">
                                <?= formatCurrency($transaction['amount']) ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Details -->
                <div class="p-6 space-y-4">
                    <!-- Category -->
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" 
                             style="background: <?= $transaction['category_color'] ?? '#6366f1' ?>20;">
                            <i data-lucide="tag" class="w-6 h-6" style="color: <?= $transaction['category_color'] ?? '#6366f1' ?>;"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-slate-600">Kategori</p>
                            <p class="text-lg font-semibold text-slate-800"><?= htmlspecialchars($transaction['category_name']) ?></p>
                        </div>
                    </div>
                    
                    <!-- Account -->
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i data-lucide="wallet" class="w-6 h-6 text-blue-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-slate-600">Akun</p>
                            <p class="text-lg font-semibold text-slate-800"><?= htmlspecialchars($transaction['account_name']) ?></p>
                            <p class="text-xs text-slate-500 mt-1">
                                <?php
                                $accountTypes = ['cash' => 'Tunai', 'bank' => 'Bank', 'e_wallet' => 'E-Wallet'];
                                echo $accountTypes[$transaction['account_type']] ?? $transaction['account_type'];
                                ?>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i data-lucide="file-text" class="w-6 h-6 text-purple-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-slate-600">Deskripsi</p>
                            <p class="text-slate-800"><?= nl2br(htmlspecialchars($transaction['description'])) ?></p>
                        </div>
                    </div>
                    
                    <!-- Date -->
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i data-lucide="calendar" class="w-6 h-6 text-indigo-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-slate-600">Tanggal Transaksi</p>
                            <p class="text-lg font-semibold text-slate-800"><?= formatDate($transaction['transaction_date']) ?></p>
                        </div>
                    </div>
                    
                    <?php if ($transaction['reference_number']): ?>
                    <!-- Reference Number -->
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i data-lucide="hash" class="w-6 h-6 text-amber-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-slate-600">No. Referensi</p>
                            <p class="text-slate-800 font-mono"><?= htmlspecialchars($transaction['reference_number']) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Attachments -->
            <?php if (!empty($attachments)): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i data-lucide="paperclip" class="w-5 h-5"></i>
                    Bukti Transaksi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($attachments as $attachment): ?>
                    <div class="border-2 border-slate-200 rounded-xl overflow-hidden hover:border-indigo-500 transition-colors">
                        <?php if (in_array(strtolower(pathinfo($attachment['file_name'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif'])): ?>
                        <a href="<?= base_url($attachment['file_path']) ?>" target="_blank" class="block">
                            <img src="<?= base_url($attachment['file_path']) ?>" 
                                 alt="<?= htmlspecialchars($attachment['file_name']) ?>"
                                 class="w-full h-48 object-cover">
                        </a>
                        <?php else: ?>
                        <a href="<?= base_url($attachment['file_path']) ?>" target="_blank" 
                           class="flex items-center justify-center h-48 bg-slate-50 hover:bg-slate-100 transition-colors">
                            <div class="text-center">
                                <i data-lucide="file" class="w-16 h-16 text-slate-400 mx-auto mb-2"></i>
                                <p class="text-sm font-semibold text-slate-700">PDF Document</p>
                            </div>
                        </a>
                        <?php endif; ?>
                        <div class="p-3 bg-slate-50">
                            <p class="text-sm font-semibold text-slate-800 truncate"><?= htmlspecialchars($attachment['file_name']) ?></p>
                            <p class="text-xs text-slate-500"><?= number_format($attachment['file_size'] / 1024, 2) ?> KB</p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Created By -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-sm font-semibold text-slate-600 mb-4">Informasi Tambahan</h3>
                
                <div class="space-y-4">
                    <!-- Created By -->
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Dibuat Oleh</p>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                                <?= strtoupper(substr($transaction['created_by_name'], 0, 2)) ?>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-800"><?= htmlspecialchars($transaction['created_by_name']) ?></p>
                                <p class="text-xs text-slate-500"><?= htmlspecialchars($transaction['created_by_email']) ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Created At -->
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Tanggal Dibuat</p>
                        <p class="text-sm text-slate-800"><?= formatDateTime($transaction['created_at']) ?></p>
                    </div>
                    
                    <?php if ($transaction['updated_at'] && $transaction['updated_at'] !== $transaction['created_at']): ?>
                    <!-- Updated At -->
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Terakhir Diupdate</p>
                        <p class="text-sm text-slate-800"><?= formatDateTime($transaction['updated_at']) ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Transaction ID -->
                    <div>
                        <p class="text-xs text-slate-500 mb-1">ID Transaksi</p>
                        <p class="text-sm text-slate-800 font-mono">#<?= str_pad($transaction['id'], 6, '0', STR_PAD_LEFT) ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-sm font-semibold text-slate-600 mb-4">Aksi</h3>
                <div class="space-y-2">
                    <?php if (can('transactions.edit')): ?>
                    <a href="<?= base_url('transactions/edit/' . $transaction['id']) ?>" 
                       class="w-full px-4 py-2 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition-colors flex items-center justify-center gap-2">
                        <i data-lucide="edit" class="w-4 h-4"></i>
                        Edit Transaksi
                    </a>
                    <?php endif; ?>
                    
                    <?php if (can('transactions.delete')): ?>
                    <button onclick="confirmDelete(<?= $transaction['id'] ?>)" 
                            class="w-full px-4 py-2 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition-colors flex items-center justify-center gap-2">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        Hapus Transaksi
                    </button>
                    <?php endif; ?>
                    
                    <button onclick="window.print()" 
                            class="w-full px-4 py-2 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition-colors flex items-center justify-center gap-2">
                        <i data-lucide="printer" class="w-4 h-4"></i>
                        Cetak
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
    
    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus transaksi ini? Tindakan ini tidak dapat dibatalkan.')) {
            window.location.href = '<?= base_url('transactions/delete/') ?>' + id;
        }
    }
</script>

<style>
    @media print {
        .no-print {
            display: none !important;
        }
    }
</style>
