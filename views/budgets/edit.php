<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900"><?= $pageTitle ?></h1>
            <p class="mt-1 text-sm text-gray-500">Update budget details</p>
        </div>
        <a href="<?= base_url('budgets') ?>" class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Budgets
        </a>
    </div>
</div>

<!-- Form Content -->
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8">
        <form action="<?= base_url('budgets/update/' . $budget['id']) ?>" method="POST" class="space-y-6">
            <!-- Category (Read-only) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium" style="background-color: <?= ($budget['category_color'] ?? '#4CAF50') ?>20; color: <?= $budget['category_color'] ?? '#4CAF50' ?>">
                        <?= htmlspecialchars($budget['category_name'] ?? 'Uncategorized') ?>
                    </span>
                </div>
            </div>

            <!-- Period (Read-only) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Period</label>
                <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl">
                    <span class="text-gray-700">
                        <?= $budget['month'] ? getMonthName($budget['month']) . ' ' : '' ?><?= $budget['year'] ?>
                    </span>
                </div>
            </div>

            <!-- Amount -->
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Budget Amount <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                    <input type="text" name="amount" id="amount" value="<?= number_format($budget['amount'], 0, ',', '.') ?>" class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200" placeholder="0" required>
                </div>
            </div>

            <!-- Budget Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-sm text-blue-700 font-medium">Budget Information</p>
                        <p class="text-sm text-blue-600 mt-1">Budgeted: Rp <?= number_format($budget['amount'] ?? 0, 0, ',', '.') ?></p>
                        <p class="text-sm text-blue-600">Realized: Rp <?= number_format($budget['realized'] ?? 0, 0, ',', '.') ?></p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-4 pt-4">
                <a href="<?= base_url('budgets') ?>" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl shadow-sm hover:shadow transition-all duration-200">
                    Update Budget
                </button>
            </div>
        </form>
    </div>
</div>
