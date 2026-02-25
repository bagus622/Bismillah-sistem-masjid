<?php 
// Helper function to get progress bar color
function getProgressColor($percent, $isCompleted, $isOverdue) {
    if ($isCompleted) return 'bg-gradient-to-r from-emerald-400 to-green-500';
    if ($isOverdue) return 'bg-gradient-to-r from-red-400 to-red-500';
    if ($percent >= 80) return 'bg-gradient-to-r from-emerald-400 to-green-500';
    if ($percent >= 50) return 'bg-gradient-to-r from-blue-400 to-blue-500';
    return 'bg-gradient-to-r from-amber-400 to-yellow-500';
}

// Helper function to get status badge
function getGoalStatus($isCompleted, $isOverdue) {
    if ($isCompleted) {
        return ['label' => 'Completed', 'class' => 'bg-emerald-100 text-emerald-700 border-emerald-200'];
    }
    if ($isOverdue) {
        return ['label' => 'Overdue', 'class' => 'bg-red-100 text-red-700 border-red-200'];
    }
    return ['label' => 'In Progress', 'class' => 'bg-amber-100 text-amber-700 border-amber-200'];
}
?>

<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900"><?= trans('goals') ?></h1>
                <p class="mt-1 text-sm text-gray-500">Track and manage your financial targets</p>
            </div>
        </div>
        
        <!-- Grand Total Progress Ring (Desktop) -->
        <?php if ($totalTarget > 0): ?>
        <div class="hidden lg:flex items-center gap-3 bg-white rounded-2xl p-4 shadow-md border border-gray-100">
            <div class="relative w-16 h-16">
                <svg class="w-16 h-16 transform -rotate-90">
                    <circle cx="32" cy="32" r="28" stroke="#e5e7eb" stroke-width="6" fill="none"/>
                    <circle cx="32" cy="32" r="28" stroke="url(#progressGradient)" stroke-width="6" fill="none" stroke-linecap="round" stroke-dasharray="<?= ($totalPercent / 100) * 176 ?>, 176"/>
                </svg>
                <defs>
                    <linearGradient id="progressGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#10b981"/>
                        <stop offset="100%" stop-color="#34d399"/>
                    </linearGradient>
                </defs>
                <span class="absolute inset-0 flex items-center justify-center text-sm font-bold text-gray-700"><?= number_format($totalPercent, 0) ?>%</span>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total Progress</p>
                <p class="text-sm font-semibold text-gray-900"><?= formatCurrency($totalCurrent) ?></p>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (can('goals.create')): ?>
        <a href="<?= base_url('goals/create') ?>" class="inline-flex items-center px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md hover:scale-105 transition-all duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <?= trans('add_goal') ?>
        </a>
        <?php endif; ?>
    </div>
</div>

<!-- Grand Total Progress Card (Mobile & Main) -->
<?php if ($totalTarget > 0): ?>
<div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl shadow-md border border-emerald-100 p-8 mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div>
            <p class="text-sm font-medium text-emerald-600 mb-1">Total Raised</p>
            <p class="text-4xl font-bold text-gray-900"><?= formatCurrency($totalCurrent) ?></p>
            <p class="text-sm text-gray-500 mt-1">of <?= formatCurrency($totalTarget) ?> target</p>
        </div>
        <div class="flex-1 max-w-md">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-gray-600">Progress</span>
                <span class="text-lg font-bold text-emerald-600"><?= number_format($totalPercent, 1) ?>%</span>
            </div>
            <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-emerald-400 to-green-500 rounded-full transition-all duration-1000 ease-out" style="width: <?= min($totalPercent, 100) ?>%"></div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Goals Grid -->
<?php if (!empty($goals)): ?>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <?php foreach ($goals as $goal): ?>
    <?php 
        $progress = floatval($goal['progress_percent']);
        $isCompleted = $goal['is_completed'];
        $isOverdue = $goal['is_overdue'];
        $status = getGoalStatus($isCompleted, $isOverdue);
        $progressColor = getProgressColor($progress, $isCompleted, $isOverdue);
    ?>
    <div class="goal-card bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-200 overflow-hidden">
        <!-- Header -->
        <div class="p-6 pb-4">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-lg font-bold text-gray-900"><?= sanitize($goal['name']) ?></h3>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?= $status['class'] ?> border">
                            <?= $status['label'] ?>
                        </span>
                    </div>
                    <?php if ($goal['target_date']): ?>
                    <p class="text-sm text-gray-500 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Target: <?= formatDate($goal['target_date']) ?>
                    </p>
                    <?php endif; ?>
                </div>
                
                <!-- Dropdown Menu -->
                <div class="relative">
                    <button onclick="toggleDropdown(<?= $goal['id'] ?>)" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors duration-150">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                        </svg>
                    </button>
                    <div id="dropdown-<?= $goal['id'] ?>" class="hidden absolute right-0 top-full mt-1 bg-white rounded-xl shadow-lg border border-gray-100 py-2 min-w-[160px] z-10">
                        <?php if (can('goals.deposit') && !$goal['is_completed']): ?>
                        <a href="<?= base_url('goals/deposit/' . $goal['id']) ?>" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add Deposit
                        </a>
                        <?php endif; ?>
                        <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            View Details
                        </a>
                        <?php if (can('goals.delete')): ?>
                        <button onclick="confirmDelete(<?= $goal['id'] ?>, '<?= sanitize($goal['name']) ?>')" class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors w-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Progress Section -->
        <div class="px-6 pb-4">
            <div class="mb-3">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-600"><?= trans('progress') ?></span>
                    <span class="text-lg font-bold <?= $isCompleted ? 'text-emerald-600' : ($isOverdue ? 'text-red-600' : 'text-blue-600') ?>">
                        <?= number_format(min($progress, 100), 1) ?>%
                    </span>
                </div>
                <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full <?= $progressColor ?> rounded-full transition-all duration-1000 ease-out" style="width: <?= min($progress, 100) ?>%"></div>
                </div>
            </div>
            
            <!-- Amounts -->
            <div class="flex justify-between items-end pt-4 border-t border-gray-100">
                <div>
                    <p class="text-xs text-gray-500 mb-1"><?= trans('current_amount') ?></p>
                    <p class="text-xl font-bold text-emerald-600"><?= formatCurrency($goal['current_amount']) ?></p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500 mb-1"><?= trans('target_amount') ?></p>
                    <p class="text-lg font-semibold text-gray-700"><?= formatCurrency($goal['target_amount']) ?></p>
                </div>
            </div>
            
            <!-- Description -->
            <?php if ($goal['description']): ?>
            <p class="mt-4 text-sm text-gray-500 line-clamp-2"><?= sanitize($goal['description']) ?></p>
            <?php endif; ?>
        </div>
        
        <!-- Footer Actions -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
            <?php if (can('goals.deposit') && !$goal['is_completed']): ?>
            <a href="<?= base_url('goals/deposit/' . $goal['id']) ?>" class="inline-flex items-center px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <?= trans('add_deposit') ?>
            </a>
            <?php else: ?>
            <div></div>
            <?php endif; ?>
            
            <?php if ($progress >= 100 && !$goal['is_completed']): ?>
            <span class="inline-flex items-center px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-medium rounded-full">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Goal Reached!
            </span>
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
                <circle cx="100" cy="100" r="90" fill="#ECFDF5"/>
                <!-- Target/Circle -->
                <circle cx="100" cy="100" r="50" fill="white" stroke="#10B981" stroke-width="4" stroke-dasharray="8 4"/>
                <circle cx="100" cy="100" r="30" fill="white" stroke="#10B981" stroke-width="3"/>
                <circle cx="100" cy="100" r="10" fill="#10B981"/>
                <!-- Darts -->
                <path d="M140 60 L155 45 M140 60 L150 65 M140 60 L135 55" stroke="#6B7280" stroke-width="2" stroke-linecap="round"/>
                <circle cx="100" cy="40" r="4" fill="#EF4444"/>
                <!-- Plus Icon -->
                <circle cx="160" cy="150" r="25" fill="#10B981" stroke="white" stroke-width="3"/>
                <line x1="160" y1="138" x2="160" y2="162" stroke="white" stroke-width="3" stroke-linecap="round"/>
                <line x1="148" y1="150" x2="172" y2="150" stroke="white" stroke-width="3" stroke-linecap="round"/>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">No goals created yet</h3>
        <p class="text-gray-500 mb-6 max-w-sm mx-auto">Start planning your first financial target and track your fundraising progress</p>
        <?php if (can('goals.create')): ?>
        <a href="<?= base_url('goals/create') ?>" class="inline-flex items-center px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Create Goal
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
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Delete Goal?</h3>
            <p class="text-gray-500 mb-2">Are you sure you want to delete <span id="goalName" class="font-semibold text-gray-900"></span>?</p>
            <p class="text-sm text-amber-600 mb-6">⚠️ All deposits associated with this goal will also be deleted.</p>
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
    // Dropdown toggle
    let currentDropdown = null;
    
    function toggleDropdown(id) {
        const dropdown = document.getElementById('dropdown-' + id);
        
        // Close current dropdown if open
        if (currentDropdown && currentDropdown !== dropdown) {
            currentDropdown.classList.add('hidden');
        }
        
        dropdown.classList.toggle('hidden');
        currentDropdown = dropdown.classList.contains('hidden') ? null : dropdown;
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.relative')) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
            currentDropdown = null;
        }
    });
    
    // Delete modal
    function confirmDelete(id, name) {
        const modal = document.getElementById('deleteModal');
        const deleteLink = document.getElementById('deleteLink');
        const goalName = document.getElementById('goalName');
        
        deleteLink.href = '<?= base_url('goals/delete/') ?>' + id;
        goalName.textContent = name;
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
            document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        }
    });
</script>

<style>
    /* Progress bar animation on load */
    @keyframes progressFill {
        from { width: 0%; }
    }
    
    .goal-card .h-full {
        animation: progressFill 1s ease-out forwards;
    }
    
    /* Line clamp */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Smooth transitions */
    .goal-card {
        transition: all 0.2s ease;
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
</style>
