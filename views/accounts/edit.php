<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900"><?= $pageTitle ?></h1>
            <p class="mt-1 text-sm text-gray-500">Update account details</p>
        </div>
        <a href="<?= base_url('accounts') ?>" class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Accounts
        </a>
    </div>
</div>

<!-- Form Content -->
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8">
        <form action="<?= base_url('accounts/update/' . $account['id']) ?>" method="POST" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <!-- Account Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Account Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($account['name']) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200" placeholder="e.g., Kas Utama" required>
            </div>

            <!-- Account Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Account Type <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-3 gap-4">
                    <label class="peer-checked:border-emerald-500 peer-checked:bg-emerald-50 relative flex flex-col items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 border-gray-200 hover:border-gray-300">
                        <input type="radio" name="type" value="cash" class="peer sr-only" <?= $account['type'] === 'cash' ? 'checked' : '' ?> required>
                        <svg class="peer-checked:text-emerald-600 w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="peer-checked:text-emerald-700 text-gray-600 font-medium">Cash</span>
                    </label>
                    <label class="peer-checked:border-emerald-500 peer-checked:bg-emerald-50 relative flex flex-col items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 border-gray-200 hover:border-gray-300">
                        <input type="radio" name="type" value="bank" class="peer sr-only" <?= $account['type'] === 'bank' ? 'checked' : '' ?> required>
                        <svg class="peer-checked:text-emerald-600 w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <span class="peer-checked:text-emerald-700 text-gray-600 font-medium">Bank</span>
                    </label>
                    <label class="peer-checked:border-emerald-500 peer-checked:bg-emerald-50 relative flex flex-col items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 border-gray-200 hover:border-gray-300">
                        <input type="radio" name="type" value="e_wallet" class="peer sr-only" <?= $account['type'] === 'e_wallet' ? 'checked' : '' ?> required>
                        <svg class="peer-checked:text-emerald-600 w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <span class="peer-checked:text-emerald-700 text-gray-600 font-medium">E-Wallet</span>
                    </label>
                </div>
            </div>

            <!-- Account Number -->
            <div>
                <label for="account_number" class="block text-sm font-medium text-gray-700 mb-2">Account Number</label>
                <input type="text" name="account_number" id="account_number" value="<?= htmlspecialchars($account['account_number'] ?? '') ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200" placeholder="e.g., 1234567890">
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200" placeholder="Enter account description..."><?= htmlspecialchars($account['description'] ?? '') ?></textarea>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-sm text-blue-700 font-medium">Initial Balance</p>
                        <p class="text-sm text-blue-600 mt-1">The initial balance (Rp <?= number_format($account['initial_balance'], 0, ',', '.') ?>) cannot be changed after creation. To adjust, please delete and recreate this account.</p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-4 pt-4">
                <a href="<?= base_url('accounts') ?>" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl shadow-sm hover:shadow transition-all duration-200">
                    Update Account
                </button>
            </div>
        </form>
    </div>
</div>
