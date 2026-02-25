<?php 
$pageTitle = $pageTitle ?? (getLang() === 'id' ? 'Detail Masjid' : 'Mosque Details');
$mosque = $mosque ?? [];
$users = $users ?? [];
$stats = $stats ?? [];
?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="<?= base_url('mosques') ?>" class="p-2 rounded-lg hover:bg-slate-100 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5 text-slate-600"></i>
        </a>
        <h1 class="text-2xl font-bold text-slate-800"><?= sanitize($mosque['name'] ?? '') ?></h1>
    </div>
    <p class="text-slate-500 text-sm ml-14"><?= getLang() === 'id' ? 'Informasi lengkap masjid' : 'Complete mosque information' ?></p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Mosque Info -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Mosque Info Card -->
        <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="building" class="w-6 h-6 text-violet-600"></i>
                </div>
                <div>
                    <h2 class="font-semibold text-slate-800"><?= getLang() === 'id' ? 'Informasi Masjid' : 'Mosque Information' ?></h2>
                    <p class="text-xs text-slate-500"><?= getLang() === 'id' ? 'Data lengkap' : 'Complete data' ?></p>
                </div>
            </div>
            
            <div class="space-y-3">
                <?php if (!empty($mosque['address'])): ?>
                <div class="flex items-start gap-3">
                    <i data-lucide="map-pin" class="w-5 h-5 text-slate-400 mt-0.5"></i>
                    <div class="flex-1">
                        <p class="text-xs text-slate-500 mb-0.5"><?= getLang() === 'id' ? 'Alamat' : 'Address' ?></p>
                        <p class="text-sm text-slate-700"><?= sanitize($mosque['address']) ?></p>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($mosque['phone'])): ?>
                <div class="flex items-center gap-3">
                    <i data-lucide="phone" class="w-5 h-5 text-slate-400"></i>
                    <div class="flex-1">
                        <p class="text-xs text-slate-500 mb-0.5"><?= getLang() === 'id' ? 'Telepon' : 'Phone' ?></p>
                        <p class="text-sm text-slate-700"><?= sanitize($mosque['phone']) ?></p>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($mosque['email'])): ?>
                <div class="flex items-center gap-3">
                    <i data-lucide="mail" class="w-5 h-5 text-slate-400"></i>
                    <div class="flex-1">
                        <p class="text-xs text-slate-500 mb-0.5"><?= getLang() === 'id' ? 'Email' : 'Email' ?></p>
                        <p class="text-sm text-slate-700"><?= sanitize($mosque['email']) ?></p>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="flex items-center gap-3">
                    <i data-lucide="calendar" class="w-5 h-5 text-slate-400"></i>
                    <div class="flex-1">
                        <p class="text-xs text-slate-500 mb-0.5"><?= getLang() === 'id' ? 'Dibuat' : 'Created' ?></p>
                        <p class="text-sm text-slate-700"><?= date('d M Y', strtotime($mosque['created_at'])) ?></p>
                    </div>
                </div>
            </div>
            
            <hr class="border-slate-100 my-4">
            
            <a href="<?= base_url('mosques/edit/' . $mosque['id']) ?>" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-violet-600 text-white rounded-xl font-medium hover:bg-violet-700 transition-all">
                <i data-lucide="pencil" class="w-4 h-4"></i>
                <?= getLang() === 'id' ? 'Edit Masjid' : 'Edit Mosque' ?>
            </a>
        </div>
        
        <!-- Statistics Card -->
        <div class="bg-gradient-to-br from-violet-600 to-purple-700 rounded-2xl shadow-lg p-6 text-white">
            <h3 class="font-semibold mb-4"><?= getLang() === 'id' ? 'Statistik' : 'Statistics' ?></h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-violet-100 text-sm"><?= getLang() === 'id' ? 'Total Pengguna' : 'Total Users' ?></span>
                    <span class="font-bold text-lg"><?= $stats['user_count'] ?? 0 ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-violet-100 text-sm"><?= getLang() === 'id' ? 'Total Akun' : 'Total Accounts' ?></span>
                    <span class="font-bold text-lg"><?= $stats['account_count'] ?? 0 ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-violet-100 text-sm"><?= getLang() === 'id' ? 'Total Transaksi' : 'Total Transactions' ?></span>
                    <span class="font-bold text-lg"><?= $stats['transaction_count'] ?? 0 ?></span>
                </div>
            </div>
            
            <hr class="border-white/20 my-4">
            
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-violet-100 text-sm"><?= getLang() === 'id' ? 'Total Pemasukan' : 'Total Income' ?></span>
                    <span class="font-bold"><?= formatCurrency($stats['total_income'] ?? 0) ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-violet-100 text-sm"><?= getLang() === 'id' ? 'Total Pengeluaran' : 'Total Expense' ?></span>
                    <span class="font-bold"><?= formatCurrency($stats['total_expense'] ?? 0) ?></span>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-white/20">
                    <span class="text-white font-medium"><?= getLang() === 'id' ? 'Saldo Bersih' : 'Net Balance' ?></span>
                    <span class="font-bold text-lg"><?= formatCurrency(($stats['total_income'] ?? 0) - ($stats['total_expense'] ?? 0)) ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right Column: Users List -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="font-semibold text-slate-800"><?= getLang() === 'id' ? 'Daftar Pengguna' : 'Users List' ?></h2>
                    <p class="text-xs text-slate-500 mt-0.5"><?= count($users) ?> <?= getLang() === 'id' ? 'pengguna terdaftar' : 'registered users' ?></p>
                </div>
            </div>
            
            <?php if (empty($users)): ?>
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="users" class="w-8 h-8 text-slate-400"></i>
                </div>
                <h3 class="text-sm font-semibold text-slate-800 mb-1"><?= getLang() === 'id' ? 'Belum ada pengguna' : 'No users yet' ?></h3>
                <p class="text-xs text-slate-500"><?= getLang() === 'id' ? 'Tambahkan pengguna melalui menu Pengguna' : 'Add users through the Users menu' ?></p>
            </div>
            <?php else: ?>
            <!-- Users Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase"><?= getLang() === 'id' ? 'Pengguna' : 'User' ?></th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase"><?= getLang() === 'id' ? 'Role' : 'Role' ?></th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase"><?= getLang() === 'id' ? 'Status' : 'Status' ?></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-violet-400 to-purple-500 flex items-center justify-center text-white font-bold text-sm">
                                        <?= strtoupper(substr($user['name'], 0, 2)) ?>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-800 text-sm"><?= sanitize($user['name']) ?></p>
                                        <p class="text-xs text-slate-500"><?= sanitize($user['email']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <?php
                                $roleBadges = [
                                    'admin' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Admin'],
                                    'accountant' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'label' => getLang() === 'id' ? 'Akuntan' : 'Accountant'],
                                    'treasurer' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => getLang() === 'id' ? 'Bendahara' : 'Treasurer'],
                                    'member' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => getLang() === 'id' ? 'Anggota' : 'Member']
                                ];
                                $badge = $roleBadges[$user['role']] ?? $roleBadges['member'];
                                ?>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?= $badge['bg'] ?> <?= $badge['text'] ?>">
                                    <?= $badge['label'] ?>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <?php if ($user['is_active']): ?>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span>
                                    <?= getLang() === 'id' ? 'Aktif' : 'Active' ?>
                                </span>
                                <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></span>
                                    <?= getLang() === 'id' ? 'Nonaktif' : 'Inactive' ?>
                                </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
