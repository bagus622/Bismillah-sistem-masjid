<?php 
// Default icons if not set
$defaultIncomeIcon = 'fa-arrow-up';
$defaultExpenseIcon = 'fa-arrow-down';
?>

<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900"><?= trans('categories') ?></h1>
            <p class="mt-1 text-sm text-gray-500">Manage your income & expense categories</p>
        </div>
        <?php if (can('categories.create')): ?>
        <div class="flex flex-wrap gap-3">
            <a href="<?= base_url('categories/create?type=income') ?>" class="inline-flex items-center px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200 transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <?= trans('add_category') ?> (<?= trans('income') ?>)
            </a>
            <a href="<?= base_url('categories/create?type=expense') ?>" class="inline-flex items-center px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200 transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <?= trans('add_category') ?> (<?= trans('expense') ?>)
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Total Income Categories -->
    <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Income Categories</p>
                <p class="text-3xl font-bold text-emerald-600"><?= $totalIncomeCategories ?></p>
            </div>
            <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Total Expense Categories -->
    <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Expense Categories</p>
                <p class="text-3xl font-bold text-red-600"><?= $totalExpenseCategories ?></p>
            </div>
            <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Search Bar -->
<div class="mb-6">
    <div class="relative max-w-md">
        <input type="text" id="searchInput" placeholder="Search categories..." class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200 text-gray-700 shadow-sm">
        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
    </div>
</div>

<!-- Tab Navigation -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 mb-8">
    <div class="flex gap-2">
        <button onclick="switchTab('income')" id="tab-income" class="flex-1 px-6 py-3 rounded-xl font-medium transition-all duration-200 flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
            </svg>
            💰 Income
        </button>
        <button onclick="switchTab('expense')" id="tab-expense" class="flex-1 px-6 py-3 rounded-xl font-medium transition-all duration-200 flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
            </svg>
            💸 Expense
        </button>
    </div>
</div>

<!-- Income Categories Section -->
<div id="income-section" class="category-section">
    <?php if (!empty($incomeCategories)): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($incomeCategories as $cat): ?>
        <div class="category-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200" data-name="<?= strtolower($cat['name']) ?>">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: <?= $cat['color'] ?>20;">
                        <i class="<?= $cat['icon'] ?: $defaultIncomeIcon ?> text-xl" style="color: <?= $cat['color'] ?>;"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900"><?= sanitize($cat['name']) ?></h3>
                        <p class="text-xs text-gray-500"><?= $cat['description'] ?: 'No description' ?></p>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?= $cat['transaction_count'] > 0 ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500' ?>">
                    <?= $cat['transaction_count'] ?> transactions
                </span>
                <?php if (can('categories.edit') || can('categories.delete')): ?>
                <div class="flex gap-2">
                    <?php if (can('categories.edit')): ?>
                    <a href="<?= base_url('categories/edit/' . $cat['id']) ?>" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-150" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                    <?php endif; ?>
                    <?php if (can('categories.delete')): ?>
                    <button onclick="confirmDelete(<?= $cat['id'] ?>, '<?= sanitize($cat['name']) ?>')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150" title="Delete">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <!-- Empty State -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12">
        <div class="text-center">
            <div class="w-32 h-32 mx-auto mb-6">
                <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="100" cy="100" r="90" fill="#FEF3C7"/>
                    <rect x="60" y="50" width="80" height="100" rx="8" fill="white" stroke="#F59E0B" stroke-width="3"/>
                    <line x1="75" y1="80" x2="125" y2="80" stroke="#F59E0B" stroke-width="2" stroke-linecap="round"/>
                    <line x1="75" y1="100" x2="125" y2="100" stroke="#F59E0B" stroke-width="2" stroke-linecap="round"/>
                    <line x1="75" y1="120" x2="105" y2="120" stroke="#F59E0B" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="140" cy="140" r="25" fill="#F59E0B" stroke="white" stroke-width="3"/>
                    <line x1="140" y1="128" x2="140" y2="152" stroke="white" stroke-width="3" stroke-linecap="round"/>
                    <line x1="128" y1="140" x2="152" y2="140" stroke="white" stroke-width="3" stroke-linecap="round"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No income categories yet</h3>
            <p class="text-gray-500 mb-6 max-w-sm mx-auto">Start by creating your first income category</p>
            <?php if (can('categories.create')): ?>
            <a href="<?= base_url('categories/create?type=income') ?>" class="inline-flex items-center px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <?= trans('add_category') ?>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Expense Categories Section -->
<div id="expense-section" class="category-section hidden">
    <?php if (!empty($expenseCategories)): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($expenseCategories as $cat): ?>
        <div class="category-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200" data-name="<?= strtolower($cat['name']) ?>">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: <?= $cat['color'] ?>20;">
                        <i class="<?= $cat['icon'] ?: $defaultExpenseIcon ?> text-xl" style="color: <?= $cat['color'] ?>;"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900"><?= sanitize($cat['name']) ?></h3>
                        <p class="text-xs text-gray-500"><?= $cat['description'] ?: 'No description' ?></p>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?= $cat['transaction_count'] > 0 ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500' ?>">
                    <?= $cat['transaction_count'] ?> transactions
                </span>
                <?php if (can('categories.edit') || can('categories.delete')): ?>
                <div class="flex gap-2">
                    <?php if (can('categories.edit')): ?>
                    <a href="<?= base_url('categories/edit/' . $cat['id']) ?>" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-150" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                    <?php endif; ?>
                    <?php if (can('categories.delete')): ?>
                    <button onclick="confirmDelete(<?= $cat['id'] ?>, '<?= sanitize($cat['name']) ?>')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150" title="Delete">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <!-- Empty State -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12">
        <div class="text-center">
            <div class="w-32 h-32 mx-auto mb-6">
                <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="100" cy="100" r="90" fill="#FEE2E2"/>
                    <rect x="60" y="50" width="80" height="100" rx="8" fill="white" stroke="#EF4444" stroke-width="3"/>
                    <line x1="75" y1="80" x2="125" y2="80" stroke="#EF4444" stroke-width="2" stroke-linecap="round"/>
                    <line x1="75" y1="100" x2="125" y2="100" stroke="#EF4444" stroke-width="2" stroke-linecap="round"/>
                    <line x1="75" y1="120" x2="105" y2="120" stroke="#EF4444" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="140" cy="140" r="25" fill="#EF4444" stroke="white" stroke-width="3"/>
                    <line x1="140" y1="128" x2="140" y2="152" stroke="white" stroke-width="3" stroke-linecap="round"/>
                    <line x1="128" y1="140" x2="152" y2="140" stroke="white" stroke-width="3" stroke-linecap="round"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No expense categories yet</h3>
            <p class="text-gray-500 mb-6 max-w-sm mx-auto">Start by creating your first expense category</p>
            <?php if (can('categories.create')): ?>
            <a href="<?= base_url('categories/create?type=expense') ?>" class="inline-flex items-center px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <?= trans('add_category') ?>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm hidden items-center justify-center z-50 transition-opacity duration-200 opacity-0">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform scale-95 transition-transform duration-200">
        <div class="text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Delete Category?</h3>
            <p class="text-gray-500 mb-2">Are you sure you want to delete <span id="categoryName" class="font-semibold text-gray-900"></span>?</p>
            <p class="text-sm text-amber-600 mb-6">⚠️ This action cannot be undone if the category has no transactions.</p>
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

<!-- JavaScript -->
<script>
    // Current active tab
    let activeTab = 'income';
    
    // Initialize - set income as active
    document.addEventListener('DOMContentLoaded', function() {
        switchTab('income');
    });
    
    // Tab switching
    function switchTab(tab) {
        activeTab = tab;
        
        // Update tab buttons
        const incomeBtn = document.getElementById('tab-income');
        const expenseBtn = document.getElementById('tab-expense');
        
        if (tab === 'income') {
            incomeBtn.classList.add('bg-white', 'shadow-sm', 'text-emerald-600', 'font-semibold');
            incomeBtn.classList.remove('text-gray-500', 'hover:bg-gray-50');
            expenseBtn.classList.remove('bg-white', 'shadow-sm', 'text-red-600', 'font-semibold');
            expenseBtn.classList.add('text-gray-500', 'hover:bg-gray-50');
            
            document.getElementById('income-section').classList.remove('hidden');
            document.getElementById('expense-section').classList.add('hidden');
        } else {
            expenseBtn.classList.add('bg-white', 'shadow-sm', 'text-red-600', 'font-semibold');
            expenseBtn.classList.remove('text-gray-500', 'hover:bg-gray-50');
            incomeBtn.classList.remove('bg-white', 'shadow-sm', 'text-emerald-600', 'font-semibold');
            incomeBtn.classList.add('text-gray-500', 'hover:bg-gray-50');
            
            document.getElementById('expense-section').classList.remove('hidden');
            document.getElementById('income-section').classList.add('hidden');
        }
        
        // Update search
        filterCategories();
    }
    
    // Search functionality
    document.getElementById('searchInput')?.addEventListener('input', filterCategories);
    
    function filterCategories() {
        const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
        
        // Filter current tab
        const currentSection = activeTab === 'income' ? 'income-section' : 'expense-section';
        const section = document.getElementById(currentSection);
        const cards = section?.querySelectorAll('.category-card');
        
        if (cards) {
            cards.forEach(card => {
                const name = card.getAttribute('data-name') || '';
                card.style.display = name.includes(searchTerm) ? '' : 'none';
            });
        }
    }
    
    // Delete modal
    function confirmDelete(id, name) {
        const modal = document.getElementById('deleteModal');
        const deleteLink = document.getElementById('deleteLink');
        const categoryName = document.getElementById('categoryName');
        
        deleteLink.href = '<?= base_url('categories/delete/') ?>' + id;
        categoryName.textContent = name;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('.transform').classList.remove('scale-95');
            modal.querySelector('.transform').classList.add('scale-100');
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
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            document.getElementById('searchInput')?.focus();
        }
    });
</script>

<style>
    /* Tab active states */
    #tab-income {
        background-color: #f3f4f6;
        color: #6b7280;
    }
    #tab-income.active, #tab-income.bg-white {
        background-color: white;
        color: #059669;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    
    #tab-expense {
        background-color: #f3f4f6;
        color: #6b7280;
    }
    #tab-expense.active, #tab-expense.bg-white {
        background-color: white;
        color: #dc2626;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    
    /* Smooth transitions */
    .category-card {
        transition: all 0.2s ease;
    }
    
    /* Input focus */
    input:focus {
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
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
</style>
