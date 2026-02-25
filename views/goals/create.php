<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900"><?= $pageTitle ?></h1>
            <p class="mt-1 text-sm text-gray-500">Create a new fundraising goal</p>
        </div>
        <a href="<?= base_url('goals') ?>" class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Goals
        </a>
    </div>
</div>

<!-- Form Content -->
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8">
        <form action="<?= base_url('goals/store') ?>" method="POST" class="space-y-6">
            <!-- Goal Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Goal Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200" placeholder="e.g., Pembangunan Masjid" required>
            </div>

            <!-- Target Amount -->
            <div>
                <label for="target_amount" class="block text-sm font-medium text-gray-700 mb-2">Target Amount <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                    <input type="text" name="target_amount" id="target_amount" class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200" placeholder="0" required>
                </div>
            </div>

            <!-- Target Date -->
            <div>
                <label for="target_date" class="block text-sm font-medium text-gray-700 mb-2">Target Date</label>
                <input type="date" name="target_date" id="target_date" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200">
                <p class="mt-1 text-sm text-gray-500">Optional: Set a deadline for this goal</p>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200" placeholder="Describe the goal and its purpose..."></textarea>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-4 pt-4">
                <a href="<?= base_url('goals') ?>" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl shadow-sm hover:shadow transition-all duration-200">
                    Create Goal
                </button>
            </div>
        </form>
    </div>
</div>
