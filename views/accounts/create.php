<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900"><?= $pageTitle ?></h1>
            <p class="mt-1 text-sm text-gray-500"><?= trans('welcome_message') ?></p>
        </div>
        <a href="<?= base_url('accounts') ?>" class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>
    </div>
</div>

<!-- Form Card -->
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <form action="<?= base_url('accounts/store') ?>" method="POST" class="p-6 space-y-5">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            
            <!-- Account Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2"><?= trans('account_name') ?> *</label>
                <input type="text" name="name" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors" required>
            </div>
            
            <!-- Account Type & Number -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"><?= trans('account_type') ?> *</label>
                    <select name="type" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors" required>
                        <option value="cash"><?= trans('cash') ?></option>
                        <option value="bank"><?= trans('bank') ?></option>
                        <option value="e_wallet"><?= trans('e_wallet') ?></option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"><?= trans('account_number') ?></label>
                    <input type="text" name="account_number" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                </div>
            </div>
            
            <!-- Initial Balance -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2"><?= trans('initial_balance') ?></label>
                <input type="text" name="initial_balance" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors currency-input" value="0">
            </div>
            
            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2"><?= trans('description') ?></label>
                <textarea name="description" rows="3" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"></textarea>
            </div>
            
            <!-- Buttons -->
            <div class="flex flex-wrap gap-3 pt-2">
                <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <?= trans('save') ?>
                </button>
                <a href="<?= base_url('accounts') ?>" class="inline-flex items-center px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors">
                    <?= trans('cancel') ?>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Footer -->
<div class="text-center py-6">
    <p class="text-sm text-gray-400">© 2026 Bismillah — Financial Management System</p>
</div>
