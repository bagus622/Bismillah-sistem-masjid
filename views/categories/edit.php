<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900"><?= $pageTitle ?></h1>
            <p class="mt-1 text-sm text-gray-500">Update category details</p>
        </div>
        <a href="<?= base_url('categories') ?>" class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Categories
        </a>
    </div>
</div>

<!-- Form Content -->
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8">
        <form action="<?= base_url('categories/update/' . $category['id']) ?>" method="POST" class="space-y-6">
            <!-- Type Indicator -->
            <div class="flex items-center space-x-4 mb-6">
                <span class="px-4 py-1.5 rounded-full text-sm font-medium <?= $category['type'] === 'income' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' ?>">
                    <?= ucfirst($category['type']) ?>
                </span>
            </div>

            <!-- Category Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Category Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($category['name']) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200" placeholder="e.g., Zakat Fitrah" required>
            </div>

            <!-- Icon Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Icon</label>
                <div class="grid grid-cols-6 gap-3 max-h-48 overflow-y-auto p-2 border border-gray-200 rounded-xl">
                    <?php $icons = ['fa-hand-holding-heart', 'fa-donate', 'fa-hands-helping', 'fa-gift', 'fa-cogs', 'fa-box', 'fa-book-quran', 'fa-hands-holding-child', 'fa-mosque', 'fa-home', 'fa-car', 'fa-utensils', 'fa-graduation-cap', 'fa-heart', 'fa-star', 'fa-users', 'fa-chart-line', 'fa-wallet', 'fa-money-bill-wave', 'fa-university']; ?>
                    <?php foreach ($icons as $icon): ?>
                        <label class="cursor-pointer">
                            <input type="radio" name="icon" value="fa <?= $icon ?>" class="sr-only peer" <?= $category['icon'] === 'fa ' . $icon || $category['icon'] === $icon ? 'checked' : '' ?>>
                            <div class="flex items-center justify-center w-12 h-12 rounded-lg border-2 border-gray-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:border-gray-300 transition-all duration-200">
                                <i class="fa <?= $icon ?> text-lg text-gray-600 peer-checked:text-emerald-600"></i>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Color Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                <div class="flex flex-wrap gap-3">
                    <?php $colors = ['#4CAF50', '#2196F3', '#9C27B0', '#FF9800', '#F44336', '#E91E63', '#00BCD4', '#795548', '#607D8B', '#3F51B5', '#009688', '#673AB7']; ?>
                    <?php foreach ($colors as $color): ?>
                        <label class="cursor-pointer">
                            <input type="radio" name="color" value="<?= $color ?>" class="sr-only peer" <?= $category['color'] === $color ? 'checked' : '' ?>>
                            <div class="w-10 h-10 rounded-full border-2 border-gray-200 peer-checked:border-gray-900 peer-checked:scale-110 transition-all duration-200" style="background-color: <?= $color ?>"></div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200" placeholder="Enter category description..."><?= htmlspecialchars($category['description'] ?? '') ?></textarea>
            </div>

            <!-- Transaction Count Warning -->
            <?php
            $transactionCount = $category['transaction_count'] ?? 0;
            if ($transactionCount > 0): 
            ?>
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-amber-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <p class="text-sm text-amber-700 font-medium">Active Category</p>
                        <p class="text-sm text-amber-600 mt-1">This category has <?= $transactionCount ?> transaction(s). Deleting this category will affect those records.</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-4 pt-4">
                <a href="<?= base_url('categories') ?>" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl shadow-sm hover:shadow transition-all duration-200">
                    Update Category
                </button>
            </div>
        </form>
    </div>
</div>
