<?php 
// Helper function to calculate percentage
function calculatePercent($realized, $budget) {
    if ($budget <= 0) return 0;
    return min(($realized / $budget) * 100, 100);
}

function getProgressColor($percent) {
    if ($percent >= 100) return 'bg-red-500';
    if ($percent >= 80) return 'bg-amber-500';
    return 'bg-emerald-500';
}

function getStatusBadge($realized, $budget) {
    $percent = calculatePercent($realized, $budget);
    if ($percent >= 100) {
        return ['label' => 'Over Budget', 'class' => 'bg-red-100 text-red-700 border-red-200'];
    }
    if ($percent >= 80) {
        return ['label' => 'Near Limit', 'class' => 'bg-amber-100 text-amber-700 border-amber-200'];
    }
    return ['label' => 'On Track', 'class' => 'bg-emerald-100 text-emerald-700 border-emerald-200'];
}

// Get month name
function getMonthNameByNum($num) {
    $months = ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    return $months[$num] ?? '';
}
?>

<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Budget Management</h1>
            <p class="mt-1 text-sm text-gray-500">Track and manage expense budgets by category</p>
        </div>
        
        <!-- Year Selector -->
        <div class="flex items-center gap-3">
            <div class="flex items-center bg-white rounded-xl shadow-sm border border-gray-200">
                <a href="<?= base_url('budgets?year=' . ($year - 1)) ?>" class="p-2.5 text-gray-600 hover:bg-gray-50 rounded-l-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div class="px-4 py-2 font-semibold text-gray-900 min-w-[80px] text-center">
                    <?= $year ?>
                </div>
                <a href="<?= base_url('budgets?year=' . ($year + 1)) ?>" class="p-2.5 text-gray-600 hover:bg-gray-50 rounded-r-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            
            <?php if (can('budgets.create')): ?>
            <a href="<?= base_url('budgets/create') ?>" class="inline-flex items-center px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md hover:scale-105 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Add Budget
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <!-- Total Budget -->
    <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total Budget</p>
                <p class="text-3xl font-bold text-gray-900"><?= formatCurrency($totalBudget) ?></p>
            </div>
            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Realized -->
    <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Realized</p>
                <p class="text-3xl font-bold text-amber-600"><?= formatCurrency($totalRealized) ?></p>
            </div>
            <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Remaining -->
    <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Remaining</p>
                <p class="text-3xl font-bold <?= ($totalBudget - $totalRealized) >= 0 ? 'text-emerald-600' : 'text-red-600' ?>"><?= formatCurrency($totalBudget - $totalRealized) ?></p>
            </div>
            <div class="w-14 h-14 <?= ($totalBudget - $totalRealized) >= 0 ? 'bg-emerald-100' : 'bg-red-100' ?> rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 <?= ($totalBudget - $totalRealized) >= 0 ? 'text-emerald-600' : 'text-red-600' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Budget List -->
<?php if (!empty($budgets)): ?>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <?php foreach ($budgets as $budget): ?>
    <?php 
        $percent = calculatePercent($budget['realized'], $budget['amount']);
        $status = getStatusBadge($budget['realized'], $budget['amount']);
        $remaining = $budget['amount'] - $budget['realized'];
    ?>
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
        <!-- Header -->
        <div class="p-5 border-b border-gray-100">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: <?= $budget['category_color'] ?>20;">
                        <span class="text-lg" style="color: <?= $budget['category_color'] ?>;">●</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900"><?= $budget['category_name'] ?></h3>
                        <p class="text-sm text-gray-500">
                            <?= $budget['month'] ? getMonthNameByNum($budget['month']) : 'Annual' ?> <?= $budget['year'] ?>
                        </p>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?= $status['class'] ?> border">
                    <?= $status['label'] ?>
                </span>
            </div>
        </div>
        
        <!-- Progress -->
        <div class="p-5">
            <div class="flex justify-between items-end mb-2">
                <div>
                    <p class="text-xs text-gray-500">Realized</p>
                    <p class="text-lg font-bold text-gray-900"><?= formatCurrency($budget['realized']) ?></p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500">Budget</p>
                    <p class="text-lg font-bold text-gray-600"><?= formatCurrency($budget['amount']) ?></p>
                </div>
            </div>
            
            <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full <?= getProgressColor($percent) ?> rounded-full transition-all duration-1000" style="width: <?= $percent ?>%"></div>
            </div>
            
            <div class="flex justify-between mt-2">
                <span class="text-sm font-medium <?= $percent >= 100 ? 'text-red-600' : 'text-gray-600' ?>"><?= number_format($percent, 1) ?>% used</span>
                <span class="text-sm font-medium <?= $remaining >= 0 ? 'text-emerald-600' : 'text-red-600' ?>"><?= formatCurrency(abs($remaining)) ?> <?= $remaining >= 0 ? 'left' : 'over' ?></span>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
            <?php if (can('budgets.edit')): ?>
            <a href="<?= base_url('budgets/edit/' . $budget['id']) ?>" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Edit Budget</a>
            <?php else: ?>
            <span></span>
            <?php endif; ?>
            
            <?php if (can('budgets.delete')): ?>
            <button onclick="confirmDelete(<?= $budget['id'] ?>, '<?= $budget['category_name'] ?>')" class="text-sm text-red-600 hover:text-red-700 font-medium">Delete</button>
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
                <!-- Background Circle -->
                <circle cx="100" cy="100" r="90" fill="#EEF2FF"/>
                <!-- Clipboard -->
                <rect x="60" y="40" width="80" height="100" rx="8" fill="white" stroke="#6366F1" stroke-width="3"/>
                <rect x="80" y="25" width="40" height="20" rx="4" fill="#6366F1"/>
                <!-- Lines -->
                <line x1="75" y1="70" x2="125" y2="70" stroke="#6366F1" stroke-width="2" stroke-linecap="round"/>
                <line x1="75" y1="95" x2="125" y2="95" stroke="#6366F1" stroke-width="2" stroke-linecap="round"/>
                <line x1="75" y1="120" x2="100" y2="120" stroke="#6366F1" stroke-width="2" stroke-linecap="round"/>
                <!-- Plus Icon -->
                <circle cx="160" cy="150" r="25" fill="#6366F1" stroke="white" stroke-width="3"/>
                <line x1="160" y1="138" x2="160" y2="162" stroke="white" stroke-width="3" stroke-linecap="round"/>
                <line x1="148" y1="150" x2="172" y2="150" stroke="white" stroke-width="3" stroke-linecap="round"/>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">No budgets created yet</h3>
        <p class="text-gray-500 mb-6 max-w-sm mx-auto">Start by creating expense budgets to track your spending</p>
        <?php if (can('budgets.create')): ?>
        <a href="<?= base_url('budgets/create') ?>" class="inline-flex items-center px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Create Budget
        </a>
        <?php endif; ?>
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
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Delete Budget?</h3>
            <p class="text-gray-500 mb-6">Are you sure you want to delete the budget for <span id="categoryName" class="font-semibold text-gray-900"></span>?</p>
            <div class="flex gap-3 justify-center">
                <button onclick="closeDeleteModal()" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-colors duration-200">
                    Cancel
                </button>
                <a id="deleteLink" href="#" class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                    Delete
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
    function confirmDelete(id, name) {
        const modal = document.getElementById('deleteModal');
        const deleteLink = document.getElementById('deleteLink');
        const categoryName = document.getElementById('categoryName');
        
        deleteLink.href = '<?= base_url('budgets/delete/') ?>' + id;
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
    
    document.getElementById('deleteModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>

<style>
    .budget-card {
        transition: all 0.2s ease;
    }
</style>
