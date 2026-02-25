<?php 
// Helper function to determine status based on is_upcoming
function getTransactionStatus($isUpcoming) {
    if ($isUpcoming) {
        return [
            'label' => trans('pending'),
            'class' => 'bg-amber-100 text-amber-700 border-amber-200',
            'dot' => 'bg-amber-500'
        ];
    }
    return [
        'label' => trans('completed'),
        'class' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
        'dot' => 'bg-emerald-500'
    ];
}
?>

<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900"><?= trans('transactions') ?></h1>
            <p class="mt-1 text-sm text-gray-500"><?= trans('welcome_message') ?> — <?= can('transactions.create') ? 'Manage and track your financial records' : 'View your financial records' ?></p>
        </div>
        <?php if (can('transactions.create')): ?>
        <div class="flex flex-wrap gap-3">
            <a href="<?= base_url('transactions/create?type=income') ?>" class="inline-flex items-center px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200 transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <?= trans('add_income') ?>
            </a>
            <a href="<?= base_url('transactions/create?type=expense') ?>" class="inline-flex items-center px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200 transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                </svg>
                <?= trans('add_expense') ?>
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Income -->
    <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1"><?= trans('total') ?> <?= trans('income') ?></p>
                <p class="text-2xl font-bold text-emerald-600"><?= formatCurrency($totalIncome) ?></p>
            </div>
            <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Total Expense -->
    <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1"><?= trans('total') ?> <?= trans('expense') ?></p>
                <p class="text-2xl font-bold text-red-600"><?= formatCurrency($totalExpense) ?></p>
            </div>
            <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Balance -->
    <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1"><?= trans('balance') ?></p>
                <p class="text-2xl font-bold <?= $balance >= 0 ? 'text-blue-600' : 'text-red-600' ?>"><?= formatCurrency($balance) ?></p>
            </div>
            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 mb-8">
    <form method="GET" action="<?= base_url('transactions') ?>" class="space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Status/Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2"><?= trans('status') ?></label>
                <select name="type" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200 text-gray-700">
                    <option value="">All Types</option>
                    <option value="income" <?= $type === 'income' ? 'selected' : '' ?>><?= trans('income') ?></option>
                    <option value="expense" <?= $type === 'expense' ? 'selected' : '' ?>><?= trans('expense') ?></option>
                </select>
            </div>
            
            <!-- Category -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2"><?= trans('category') ?></label>
                <select name="category" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200 text-gray-700">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $categoryId == $cat['id'] ? 'selected' : '' ?>>
                        <?= $cat['name'] ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Account -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2"><?= trans('account') ?></label>
                <select name="account" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200 text-gray-700">
                    <option value="">All Accounts</option>
                    <?php foreach ($accounts as $acc): ?>
                    <option value="<?= $acc['id'] ?>" <?= $accountId == $acc['id'] ? 'selected' : '' ?>>
                        <?= $acc['name'] ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- From Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2"><?= trans('from_date') ?></label>
                <input type="date" name="from_date" value="<?= $fromDate ?>" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200 text-gray-700">
            </div>
            
            <!-- To Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2"><?= trans('to_date') ?></label>
                <input type="date" name="to_date" value="<?= $toDate ?>" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200 text-gray-700">
            </div>
        </div>
        
        <div class="flex flex-wrap gap-3 pt-2">
            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <?= trans('filter') ?>
            </button>
            <a href="<?= base_url('transactions') ?>" class="inline-flex items-center px-5 py-2.5 bg-white hover:bg-gray-50 text-gray-700 font-medium rounded-xl border border-gray-300 shadow-sm hover:shadow-md transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Reset
            </a>
            <div class="ml-auto">
                <button type="button" onclick="exportToCSV()" class="inline-flex items-center px-5 py-2.5 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <?= trans('export') ?> CSV
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Tabs Navigation -->
<div class="mb-6" x-data="{ activeTab: '<?= $type ?: 'all' ?>' }">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-1.5 inline-flex gap-1">
        <button @click="activeTab = 'all'; filterByType('all')" 
                :class="activeTab === 'all' ? 'bg-violet-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50'"
                class="px-6 py-2.5 rounded-lg font-medium transition-all duration-200 flex items-center gap-2">
            <i data-lucide="list" class="w-4 h-4"></i>
            Semua Transaksi
        </button>
        <button @click="activeTab = 'income'; filterByType('income')" 
                :class="activeTab === 'income' ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50'"
                class="px-6 py-2.5 rounded-lg font-medium transition-all duration-200 flex items-center gap-2">
            <i data-lucide="trending-up" class="w-4 h-4"></i>
            Pemasukan
        </button>
        <button @click="activeTab = 'expense'; filterByType('expense')" 
                :class="activeTab === 'expense' ? 'bg-red-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50'"
                class="px-6 py-2.5 rounded-lg font-medium transition-all duration-200 flex items-center gap-2">
            <i data-lucide="trending-down" class="w-4 h-4"></i>
            Pengeluaran
        </button>
    </div>
</div>

<!-- Search Bar -->
<div class="mb-6">
    <div class="relative max-w-md">
        <input type="text" id="searchInput" placeholder="Search transactions..." class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-colors duration-200 text-gray-700 shadow-sm">
        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
    </div>
</div>

<!-- Transactions Table -->
<?php if (!empty($result['data'])): ?>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="w-full" id="transactionsTable">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"><?= trans('date') ?></th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"><?= trans('description') ?></th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"><?= trans('account') ?></th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"><?= trans('category') ?></th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider"><?= trans('amount') ?></th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"><?= trans('status') ?></th>
                    <?php if (can('transactions.view')): ?>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"><?= trans('action') ?></th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($result['data'] as $index => $t): ?>
                <tr class="hover:bg-gray-50 transition-colors duration-150 <?= $index % 2 === 0 ? 'bg-white' : 'bg-gray-50/30' ?>">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <?= formatDate($t['transaction_date']) ?>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900"><?= sanitize($t['description']) ?></div>
                        <?php if ($t['reference_number']): ?>
                        <div class="text-xs text-gray-500 mt-0.5">Ref: <?= $t['reference_number'] ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <?= $t['account_name'] ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium <?= $t['category_type'] === 'income' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-red-50 text-red-700 border border-red-100' ?>">
                            <?= $t['category_name'] ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <span class="text-lg font-bold <?= $t['category_type'] === 'income' ? 'text-emerald-600' : 'text-red-600' ?>">
                            <?= $t['category_type'] === 'income' ? '+' : '-' ?><?= formatCurrency($t['amount']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <?php $status = getTransactionStatus($t['is_upcoming']); ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?= $status['class'] ?>">
                            <span class="w-1.5 h-1.5 <?= $status['dot'] ?> rounded-full mr-1.5"></span>
                            <?= $status['label'] ?>
                        </span>
                    </td>
                    <?php if (can('transactions.view')): ?>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="<?= base_url('transactions/detail/' . $t['id']) ?>" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors duration-150" title="Lihat Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <?php if (can('transactions.edit')): ?>
                            <a href="<?= base_url('transactions/edit/' . $t['id']) ?>" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-150" title="<?= trans('edit') ?>">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <?php endif; ?>
                            <?php if (can('transactions.delete')): ?>
                            <button onclick="confirmDelete(<?= $t['id'] ?>)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150" title="<?= trans('delete') ?>">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                            <?php endif; ?>
                        </div>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<?php if ($result['total_pages'] > 1): ?>
<nav class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-8">
    <p class="text-sm text-gray-500">
        Showing page <?= $result['page'] ?> of <?= $result['total_pages'] ?>
    </p>
    <div class="flex items-center gap-1">
        <?php for ($i = 1; $i <= $result['total_pages']; $i++): ?>
        <a href="<?= base_url('transactions?page=' . $i . '&type=' . $type . '&category=' . $categoryId . '&account=' . $accountId . '&from_date=' . $fromDate . '&to_date=' . $toDate) ?>" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-150 <?= $i == $result['page'] ? 'bg-emerald-500 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' ?>">
            <?= $i ?>
        </a>
        <?php endfor; ?>
    </div>
</nav>
<?php endif; ?>

<?php else: ?>
<!-- Empty State -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 mb-8">
    <div class="text-center">
        <!-- SVG Illustration -->
        <div class="w-32 h-32 mx-auto mb-6">
            <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Background Circle -->
                <circle cx="100" cy="100" r="90" fill="#F0FDF4"/>
                <!-- Document -->
                <rect x="60" y="50" width="80" height="100" rx="8" fill="white" stroke="#10B981" stroke-width="3"/>
                <!-- Lines -->
                <line x1="75" y1="80" x2="125" y2="80" stroke="#10B981" stroke-width="2" stroke-linecap="round"/>
                <line x1="75" y1="100" x2="125" y2="100" stroke="#10B981" stroke-width="2" stroke-linecap="round"/>
                <line x1="75" y1="120" x2="105" y2="120" stroke="#10B981" stroke-width="2" stroke-linecap="round"/>
                <!-- Plus Icon -->
                <circle cx="140" cy="140" r="25" fill="#10B981" stroke="white" stroke-width="3"/>
                <line x1="140" y1="128" x2="140" y2="152" stroke="white" stroke-width="3" stroke-linecap="round"/>
                <line x1="128" y1="140" x2="152" y2="140" stroke="white" stroke-width="3" stroke-linecap="round"/>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">No transactions yet</h3>
        <p class="text-gray-500 mb-6 max-w-sm mx-auto">Start by adding your first income or expense to track your financial flow</p>
        <div class="flex flex-wrap justify-center gap-3">
            <a href="<?= base_url('transactions/create?type=income') ?>" class="inline-flex items-center px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <?= trans('add_income') ?>
            </a>
            <a href="<?= base_url('transactions/create?type=expense') ?>" class="inline-flex items-center px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 font-medium rounded-xl border border-gray-300 shadow-sm hover:shadow-md transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                </svg>
                <?= trans('add_expense') ?>
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm hidden items-center justify-center z-50 transition-opacity duration-200 opacity-0">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform scale-95 transition-transform duration-200">
        <div class="text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2"><?= trans('confirm_delete') ?></h3>
            <p class="text-gray-500 mb-6">This action cannot be undone. The transaction will be permanently deleted.</p>
            <div class="flex gap-3 justify-center">
                <button onclick="closeDeleteModal()" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-colors duration-200">
                    <?= trans('cancel') ?>
                </button>
                <a id="deleteLink" href="#" class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                    <?= trans('delete') ?>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="text-center py-6">
    <p class="text-sm text-gray-400">© 2026 Bismillah — Financial Management System</p>
</div>

<!-- JavaScript for interactivity -->
<script>
    // Filter by type function
    function filterByType(type) {
        const url = new URL(window.location.href);
        if (type === 'all') {
            url.searchParams.delete('type');
        } else {
            url.searchParams.set('type', type);
        }
        url.searchParams.set('page', '1'); // Reset to page 1
        window.location.href = url.toString();
    }

    // Search functionality
    document.getElementById('searchInput')?.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const table = document.getElementById('transactionsTable');
        const rows = table?.querySelectorAll('tbody tr');
        
        if (rows) {
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        }
    });

    // Delete modal functions
    function confirmDelete(id) {
        const modal = document.getElementById('deleteModal');
        const deleteLink = document.getElementById('deleteLink');
        
        deleteLink.href = '<?= base_url('transactions/delete/') ?>' + id;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Animate in
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('.transform').classList.remove('scale-95');
            modal.querySelector('.transform').classList.add('scale-100');
        }, 10);
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        
        // Animate out
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

    // Export to CSV
    function exportToCSV() {
        const table = document.getElementById('transactionsTable');
        if (!table) return;

        let csv = [];
        const rows = table.querySelectorAll('tr');
        
        rows.forEach(row => {
            const cols = row.querySelectorAll('td, th');
            const rowData = [];
            cols.forEach(col => {
                rowData.push('"' + col.innerText.replace(/"/g, '""') + '"');
            });
            csv.push(rowData.join(','));
        });

        const csvFile = new Blob([csv.join('\n')], {type: 'text/csv'});
        const downloadLink = document.createElement('a');
        downloadLink.download = 'transactions_<?= date('Y-m-d') ?>.csv';
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = 'none';
        document.body.appendChild(downloadLink);
        downloadLink.click();
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Escape to close modal
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
        // Ctrl/Cmd + F to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            document.getElementById('searchInput')?.focus();
        }
    });

    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>

<style>
    /* Smooth scrolling */
    html {
        scroll-behavior: smooth;
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }

    /* Input focus animation */
    select, input[type="date"], input[type="text"] {
        transition: all 0.2s ease;
    }

    select:focus, input:focus {
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    /* Table row hover */
    tbody tr {
        transition: background-color 0.15s ease;
    }

    /* Loading skeleton animation */
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }
</style>
