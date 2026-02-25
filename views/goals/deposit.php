<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900"><?= $pageTitle ?></h1>
            <p class="mt-1 text-sm text-gray-500">Add deposit to: <?= htmlspecialchars($goal['name']) ?></p>
        </div>
        <a href="<?= base_url('goals') ?>" class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Goals
        </a>
    </div>
</div>

<!-- Goal Progress Card -->
<div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 mb-6 max-w-2xl">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($goal['name']) ?></h3>
        <?php if ($goal['is_completed']): ?>
            <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">Completed</span>
        <?php elseif ($goal['is_overdue']): ?>
            <span class="px-3 py-1 bg-red-100 text-red-700 text-sm font-medium rounded-full">Overdue</span>
        <?php endif; ?>
    </div>
    
    <div class="mb-2">
        <div class="flex justify-between text-sm mb-1">
            <span class="text-gray-600">Progress</span>
            <span class="font-medium text-gray-900">Rp <?= number_format($goal['current_amount'], 0, ',', '.') ?> / Rp <?= number_format($goal['target_amount'], 0, ',', '.') ?></span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="bg-emerald-500 h-3 rounded-full transition-all duration-500" style="width: <?= min(100, ($goal['current_amount'] / $goal['target_amount']) * 100) ?>%"></div>
        </div>
    </div>
    
    <?php if ($goal['target_date']): ?>
        <p class="text-sm text-gray-500 mt-2">Target: <?= date('d M Y', strtotime($goal['target_date'])) ?></p>
    <?php endif; ?>
</div>

<!-- Deposit Form -->
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8">
        <form action="<?= base_url('goals/storeDeposit') ?>" method="POST" class="space-y-6">
            <input type="hidden" name="goal_id" value="<?= $goal['id'] ?>">

            <!-- Account -->
            <div>
                <label for="account_id" class="block text-sm font-medium text-gray-700 mb-2">From Account <span class="text-red-500">*</span></label>
                <select name="account_id" id="account_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200" required>
                    <option value="">Select Account</option>
                    <?php foreach ($accounts as $account): ?>
                        <option value="<?= $account['id'] ?>">
                            <?= htmlspecialchars($account['name']) ?> - Rp <?= number_format($account['balance'], 0, ',', '.') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Amount -->
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Deposit Amount <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                    <input type="text" name="amount" id="amount" class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200" placeholder="0" required>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea name="notes" id="notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200" placeholder="Add any notes about this deposit..."></textarea>
            </div>

            <!-- Quick Amount Buttons -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Quick Amount</label>
                <div class="grid grid-cols-4 gap-2">
                    <?php 
                    $quickAmounts = [100000, 500000, 1000000, 5000000];
                    foreach ($quickAmounts as $qa): 
                    ?>
                        <button type="button" class="px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-600 hover:border-emerald-500 hover:text-emerald-600 transition-colors duration-200" onclick="setAmount(<?= $qa ?>)">
                            Rp <?= number_format($qa, 0, ',', '.') ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-4 pt-4">
                <a href="<?= base_url('goals') ?>" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl shadow-sm hover:shadow transition-all duration-200">
                    Add Deposit
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function setAmount(amount) {
    document.getElementById('amount').value = amount.toLocaleString('id-ID');
}
</script>
