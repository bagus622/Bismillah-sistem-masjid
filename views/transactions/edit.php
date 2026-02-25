<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900"><?= $pageTitle ?></h1>
            <p class="mt-1 text-sm text-gray-500">Update transaction details</p>
        </div>
        <a href="<?= base_url('transactions') ?>" class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Transactions
        </a>
    </div>
</div>

<!-- Form Content -->
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8">
        <form action="<?= base_url('transactions/update/' . $transaction['id']) ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <!-- Type Indicator -->
            <div class="flex items-center space-x-4 mb-6">
                <span class="px-4 py-1.5 rounded-full text-sm font-medium <?= $transaction['type'] === 'income' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' ?>">
                    <?= ucfirst($transaction['type']) ?>
                </span>
            </div>

            <!-- Transaction Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Type</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="peer-checked:border-emerald-500 peer-checked:bg-emerald-50 relative flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 border-gray-200 hover:border-gray-300">
                        <input type="radio" name="type" value="income" class="peer sr-only" <?= $transaction['type'] === 'income' ? 'checked' : '' ?> required>
                        <svg class="peer-checked:text-emerald-600 w-6 h-6 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="peer-checked:text-emerald-700 text-gray-600 font-medium">Income</span>
                    </label>
                    <label class="peer-checked:border-red-500 peer-checked:bg-red-50 relative flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 border-gray-200 hover:border-gray-300">
                        <input type="radio" name="type" value="expense" class="peer sr-only" <?= $transaction['type'] === 'expense' ? 'checked' : '' ?> required>
                        <svg class="peer-checked:text-red-600 w-6 h-6 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                        <span class="peer-checked:text-red-700 text-gray-600 font-medium">Expense</span>
                    </label>
                </div>
            </div>

            <!-- Account -->
            <div>
                <label for="account_id" class="block text-sm font-medium text-gray-700 mb-2">Account <span class="text-red-500">*</span></label>
                <select name="account_id" id="account_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200" required>
                    <option value="">Select Account</option>
                    <?php foreach ($accounts as $account): ?>
                        <option value="<?= $account['id'] ?>" <?= $transaction['account_id'] == $account['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($account['name']) ?> (<?= ucfirst($account['type']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Category -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
                <select name="category_id" id="category_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= $transaction['category_id'] == $category['id'] ? 'selected' : '' ?> data-type="<?= $category['type'] ?>">
                            <?= htmlspecialchars($category['name']) ?> (<?= ucfirst($category['type']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Amount -->
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                    <input type="text" name="amount" id="amount" value="<?= number_format($transaction['amount'], 0, ',', '.') ?>" class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200" placeholder="0" required>
                </div>
            </div>

            <!-- Date -->
            <div>
                <label for="transaction_date" class="block text-sm font-medium text-gray-700 mb-2">Transaction Date <span class="text-red-500">*</span></label>
                <input type="date" name="transaction_date" id="transaction_date" value="<?= $transaction['transaction_date'] ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200" required>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200" placeholder="Enter description..."><?= htmlspecialchars($transaction['description'] ?? '') ?></textarea>
            </div>

            <!-- Reference Number -->
            <div>
                <label for="reference_number" class="block text-sm font-medium text-gray-700 mb-2">Reference Number</label>
                <input type="text" name="reference_number" id="reference_number" value="<?= htmlspecialchars($transaction['reference_number'] ?? '') ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200" placeholder="e.g., INV/2026/001">
            </div>

            <!-- Current Attachments -->
            <?php if (!empty($attachments)): ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Foto Bukti Saat Ini</label>
                <div class="grid grid-cols-2 gap-4">
                    <?php foreach ($attachments as $attachment): ?>
                    <div class="border-2 border-gray-200 rounded-xl overflow-hidden">
                        <?php if (in_array(strtolower(pathinfo($attachment['file_name'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif'])): ?>
                        <a href="<?= base_url($attachment['file_path']) ?>" target="_blank" class="block">
                            <img src="<?= base_url($attachment['file_path']) ?>" 
                                 alt="<?= htmlspecialchars($attachment['file_name']) ?>"
                                 class="w-full h-32 object-cover">
                        </a>
                        <?php else: ?>
                        <a href="<?= base_url($attachment['file_path']) ?>" target="_blank" 
                           class="flex items-center justify-center h-32 bg-gray-50 hover:bg-gray-100 transition-colors">
                            <div class="text-center">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-xs font-medium text-gray-600">PDF</p>
                            </div>
                        </a>
                        <?php endif; ?>
                        <div class="p-2 bg-gray-50">
                            <p class="text-xs font-medium text-gray-700 truncate"><?= htmlspecialchars($attachment['file_name']) ?></p>
                            <p class="text-xs text-gray-500"><?= number_format($attachment['file_size'] / 1024, 1) ?> KB</p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <p class="mt-2 text-xs text-gray-500">Upload foto baru di bawah untuk mengganti foto yang ada</p>
            </div>
            <?php endif; ?>

            <!-- Upload New Photo -->
            <div>
                <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">
                    Upload Foto Bukti Baru (Opsional)
                </label>
                <div class="mt-2">
                    <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-emerald-500 transition-colors duration-200 cursor-pointer bg-gray-50 hover:bg-emerald-50">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">
                            <label for="attachment" class="font-medium text-emerald-600 hover:text-emerald-500 cursor-pointer">
                                Klik untuk upload
                            </label>
                            atau drag & drop
                        </p>
                        <p class="mt-1 text-xs text-gray-500">JPG, PNG, atau PDF (Max 5MB)</p>
                        <input type="file" name="attachment" id="attachment" accept="image/jpeg,image/png,application/pdf" class="hidden">
                    </div>
                    
                    <!-- Preview -->
                    <div id="preview" class="mt-4 hidden">
                        <div class="flex items-center justify-between p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <div>
                                    <p id="fileName" class="text-sm font-medium text-gray-900"></p>
                                    <p id="fileSize" class="text-xs text-gray-500"></p>
                                </div>
                            </div>
                            <button type="button" onclick="clearFile()" class="text-red-600 hover:text-red-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <p class="mt-2 text-xs text-gray-500">
                    <svg class="inline w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Jika upload foto baru, foto lama akan diganti
                </p>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-4 pt-4">
                <a href="<?= base_url('transactions') ?>" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl shadow-sm hover:shadow transition-all duration-200">
                    Update Transaction
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // File upload handling
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('attachment');
    const preview = document.getElementById('preview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');

    // Click to upload
    dropZone.addEventListener('click', () => fileInput.click());

    // Drag and drop
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-emerald-500', 'bg-emerald-50');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-emerald-500', 'bg-emerald-50');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-emerald-500', 'bg-emerald-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            showPreview(files[0]);
        }
    });

    // File input change
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            showPreview(e.target.files[0]);
        }
    });

    function showPreview(file) {
        fileName.textContent = file.name;
        fileSize.textContent = (file.size / 1024).toFixed(2) + ' KB';
        preview.classList.remove('hidden');
        dropZone.classList.add('hidden');
    }

    function clearFile() {
        fileInput.value = '';
        preview.classList.add('hidden');
        dropZone.classList.remove('hidden');
    }

    // Amount formatting
    const amountInput = document.getElementById('amount');
    amountInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        e.target.value = new Intl.NumberFormat('id-ID').format(value);
    });
</script>
