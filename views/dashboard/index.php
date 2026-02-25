<?php 
$stats = $stats ?? [
    'total_income' => 0,
    'total_expense' => 0,
    'balance' => 0,
    'goals' => 0
];

$recentTransactions = $recentTransactions ?? [];
$accounts = $accounts ?? [];
?>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 lg:gap-6 mb-6">
    <!-- Total Income -->
    <div class="bg-white rounded-2xl p-5 shadow-md card-hover border border-slate-100">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-slate-500 text-sm font-medium"><?= getLang() === 'id' ? 'Total Pemasukan' : 'Total Income' ?></p>
                <p class="text-2xl font-bold text-slate-800 mt-1"><?= formatCurrency($stats['total_income']) ?></p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                <i data-lucide="trending-up" class="w-6 h-6 text-emerald-600"></i>
            </div>
        </div>
        <div class="mt-3 flex items-center gap-1 text-emerald-600 text-sm">
            <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
            <span><?= getLang() === 'id' ? 'Semua waktu' : 'All time' ?></span>
        </div>
    </div>
    
    <!-- Total Expense -->
    <div class="bg-white rounded-2xl p-5 shadow-md card-hover border border-slate-100">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-slate-500 text-sm font-medium"><?= getLang() === 'id' ? 'Total Pengeluaran' : 'Total Expense' ?></p>
                <p class="text-2xl font-bold text-slate-800 mt-1"><?= formatCurrency($stats['total_expense']) ?></p>
            </div>
            <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center">
                <i data-lucide="trending-down" class="w-6 h-6 text-rose-600"></i>
            </div>
        </div>
        <div class="mt-3 flex items-center gap-1 text-rose-600 text-sm">
            <i data-lucide="arrow-down-right" class="w-4 h-4"></i>
            <span><?= getLang() === 'id' ? 'Semua waktu' : 'All time' ?></span>
        </div>
    </div>
    
    <!-- Balance -->
    <div class="bg-white rounded-2xl p-5 shadow-md card-hover border border-slate-100">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-slate-500 text-sm font-medium"><?= getLang() === 'id' ? 'Saldo Saat Ini' : 'Current Balance' ?></p>
                <p class="text-2xl font-bold text-slate-800 mt-1"><?= formatCurrency($stats['balance']) ?></p>
            </div>
            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                <i data-lucide="wallet" class="w-6 h-6 text-indigo-600"></i>
            </div>
        </div>
        <div class="mt-3 flex items-center gap-1 text-indigo-600 text-sm">
            <i data-lucide="piggy-bank" class="w-4 h-4"></i>
            <span><?= count($accounts) ?> <?= getLang() === 'id' ? 'akun' : 'accounts' ?></span>
        </div>
    </div>
    
    <!-- Goals -->
    <div class="bg-white rounded-2xl p-5 shadow-md card-hover border border-slate-100">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-slate-500 text-sm font-medium"><?= getLang() === 'id' ? 'Target' : 'Goals' ?></p>
                <p class="text-2xl font-bold text-slate-800 mt-1"><?= formatCurrency($stats['goals']) ?></p>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                <i data-lucide="target" class="w-6 h-6 text-amber-600"></i>
            </div>
        </div>
        <div class="mt-3 flex items-center gap-1 text-amber-600 text-sm">
            <i data-lucide="lightbulb" class="w-4 h-4"></i>
            <span><?= getLang() === 'id' ? 'Target donasi' : 'Donation targets' ?></span>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-6">
    <!-- Monthly Chart -->
    <div class="bg-white rounded-2xl p-5 shadow-md border border-slate-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-slate-800"><?= getLang() === 'id' ? 'Grafik Keuangan' : 'Financial Chart' ?></h3>
            <select class="text-sm border border-slate-200 rounded-lg px-3 py-1.5 text-slate-600 bg-slate-50">
                <option><?= getLang() === 'id' ? 'Tahun Ini' : 'This Year' ?></option>
            </select>
        </div>
        <div class="h-64">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>
    
    <!-- Category Chart -->
    <div class="bg-white rounded-2xl p-5 shadow-md border border-slate-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-slate-800"><?= getLang() === 'id' ? 'Kategori' : 'Categories' ?></h3>
        </div>
        <div class="h-64 flex items-center justify-center">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
</div>

<!-- Accounts & Recent Transactions -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
    <!-- Accounts List -->
    <div class="bg-white rounded-2xl p-5 shadow-md border border-slate-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-slate-800"><?= trans('accounts') ?></h3>
            <a href="<?= base_url('accounts/create') ?>" class="flex items-center gap-2 px-3 py-1.5 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700 transition-colors">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <?= getLang() === 'id' ? 'Tambah' : 'Add' ?>
            </a>
        </div>
        <div class="space-y-3 max-h-80 overflow-y-auto">
            <?php if (empty($accounts)): ?>
            <div class="text-center py-8 text-slate-400">
                <i data-lucide="wallet" class="w-12 h-12 mx-auto mb-2 opacity-50"></i>
                <p><?= getLang() === 'id' ? 'Belum ada akun' : 'No accounts yet' ?></p>
            </div>
            <?php else: ?>
                <?php foreach ($accounts as $account): ?>
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="wallet" class="w-5 h-5 text-indigo-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-slate-800"><?= $account['name'] ?></p>
                            <p class="text-xs text-slate-500"><?= $account['description'] ?? '' ?></p>
                        </div>
                    </div>
                    <p class="font-semibold text-slate-800"><?= formatCurrency($account['balance'] ?? 0) ?></p>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Recent Transactions -->
    <div class="bg-white rounded-2xl p-5 shadow-md border border-slate-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-slate-800"><?= getLang() === 'id' ? 'Transaksi Terbaru' : 'Recent Transactions' ?></h3>
            <a href="<?= base_url('transactions') ?>" class="text-indigo-600 text-sm hover:underline">
                <?= getLang() === 'id' ? 'Lihat semua' : 'View all' ?>
            </a>
        </div>
        <div class="space-y-3 max-h-80 overflow-y-auto">
            <?php if (empty($recentTransactions)): ?>
            <div class="text-center py-8 text-slate-400">
                <i data-lucide="receipt" class="w-12 h-12 mx-auto mb-2 opacity-50"></i>
                <p><?= getLang() === 'id' ? 'Belum ada transaksi' : 'No transactions yet' ?></p>
            </div>
            <?php else: ?>
                <?php foreach ($recentTransactions as $tx): ?>
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 <?= $tx['type'] === 'income' ? 'bg-emerald-100' : 'bg-rose-100' ?> rounded-lg flex items-center justify-center">
                            <i data-lucide="<?= $tx['type'] === 'income' ? 'arrow-down-left' : 'arrow-up-right' ?>" class="w-5 h-5 <?= $tx['type'] === 'income' ? 'text-emerald-600' : 'text-rose-600' ?>"></i>
                        </div>
                        <div>
                            <p class="font-medium text-slate-800"><?= $tx['description'] ?></p>
                            <p class="text-xs text-slate-500"><?= $tx['category_name'] ?? '' ?> • <?= date('d M', strtotime($tx['transaction_date'])) ?></p>
                        </div>
                    </div>
                    <p class="font-semibold <?= $tx['type'] === 'income' ? 'text-emerald-600' : 'text-rose-600' ?>">
                        <?= $tx['type'] === 'income' ? '+' : '-' ?><?= formatCurrency($tx['amount']) ?>
                    </p>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Chart Scripts -->
<script>
    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: '<?= getLang() === 'id' ? 'Pemasukan' : 'Income' ?>',
                data: [12000000, 19000000, 15000000, 22000000, 18000000, 25000000, 21000000, 28000000, 24000000, 30000000, 26000000, 32000000],
                backgroundColor: '#10b981',
                borderRadius: 6,
            }, {
                label: '<?= getLang() === 'id' ? 'Pengeluaran' : 'Expense' ?>',
                data: [8000000, 12000000, 10000000, 15000000, 11000000, 18000000, 14000000, 20000000, 16000000, 22000000, 18000000, 25000000],
                backgroundColor: '#f43f5e',
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f1f5f9'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
    
    // Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: ['<?= getLang() === 'id' ? 'Infaq' : 'Infaq' ?>', '<?= getLang() === 'id' ? 'Zakat' : 'Zakat' ?>', '<?= getLang() === 'id' ? 'Sedekah' : 'Charity' ?>', '<?= getLang() === 'id' ? 'Lainnya' : 'Others' ?>'],
            datasets: [{
                data: [45, 25, 20, 10],
                backgroundColor: [
                    '#4f46e5',
                    '#10b981',
                    '#f59e0b',
                    '#6b7280'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                }
            },
            cutout: '65%'
        }
    });
</script>
