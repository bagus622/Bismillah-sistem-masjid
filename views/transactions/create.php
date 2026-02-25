<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                <?= $type === 'income' ? 'Tambah Pemasukan' : 'Tambah Pengeluaran' ?>
            </h1>
            <p class="text-slate-600 mt-1">Catat transaksi <?= $type === 'income' ? 'pemasukan' : 'pengeluaran' ?> baru</p>
        </div>
        <a href="<?= base_url('transactions') ?>" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition-colors flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali
        </a>
    </div>
    
    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
        <form action="<?= base_url('transactions/store') ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="type" value="<?= $type ?>">
            
            <!-- Amount -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Nominal (Rp) <span class="text-red-500">*</span>
                </label>
                <input type="text" name="amount" id="amount" required
                       class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all text-lg font-semibold"
                       placeholder="0">
            </div>
            
            <!-- Account -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Akun <span class="text-red-500">*</span>
                </label>
                <select name="account_id" required
                        class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all">
                    <option value="">Pilih Akun</option>
                    <?php foreach ($accounts as $account): ?>
                    <option value="<?= $account['id'] ?>"><?= htmlspecialchars($account['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Category -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Kategori <span class="text-red-500">*</span>
                </label>
                <select name="category_id" required
                        class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all">
                    <option value="">Pilih Kategori</option>
                    <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Description -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Deskripsi <span class="text-red-500">*</span>
                </label>
                <textarea name="description" required rows="3"
                          class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all"
                          placeholder="Deskripsi transaksi"></textarea>
            </div>
            
            <!-- Date -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Tanggal Transaksi <span class="text-red-500">*</span>
                </label>
                <input type="date" name="transaction_date" value="<?= date('Y-m-d') ?>" required
                       class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all">
            </div>
            
            <!-- Reference Number -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    No. Referensi
                </label>
                <input type="text" name="reference_number"
                       class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all"
                       placeholder="No. kwitansi, invoice, dll (opsional)">
            </div>
            
            <!-- Photo Attachment (REQUIRED) -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Foto Bukti Transaksi <span class="text-red-500">*</span>
                </label>
                <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-indigo-500 transition-colors">
                    <input type="file" name="attachment" id="attachment" accept="image/*,.pdf" required
                           class="hidden" onchange="previewFile(this)">
                    <label for="attachment" class="cursor-pointer">
                        <div id="preview-container" class="mb-4">
                            <i data-lucide="upload-cloud" class="w-12 h-12 text-slate-400 mx-auto mb-2"></i>
                            <p class="text-sm font-semibold text-slate-700">Klik untuk upload foto</p>
                            <p class="text-xs text-slate-500 mt-1">JPG, PNG, atau PDF (Max 5MB)</p>
                        </div>
                        <div id="file-info" class="hidden">
                            <img id="preview-image" class="max-w-xs mx-auto rounded-lg mb-2" style="max-height: 200px;">
                            <p id="file-name" class="text-sm font-semibold text-slate-700"></p>
                            <p id="file-size" class="text-xs text-slate-500"></p>
                        </div>
                    </label>
                </div>
                <p class="text-xs text-slate-500 mt-2">
                    <i data-lucide="info" class="w-3 h-3 inline"></i>
                    Foto bukti transaksi wajib diupload untuk validasi
                </p>
            </div>
            
            <!-- Upcoming -->
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_upcoming" value="1" id="is_upcoming"
                       class="w-5 h-5 text-indigo-600 rounded focus:ring-2 focus:ring-indigo-500">
                <label for="is_upcoming" class="text-sm text-slate-700">
                    Transaksi akan datang (jadwalkan untuk masa depan)
                </label>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Simpan Transaksi
                </button>
                <a href="<?= base_url('transactions') ?>" 
                   class="px-6 py-3 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
        
        // Format amount input
        const amountInput = document.getElementById('amount');
        amountInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            if (value) {
                value = parseInt(value).toLocaleString('id-ID');
            }
            e.target.value = value;
        });
    });
    
    function previewFile(input) {
        const file = input.files[0];
        if (file) {
            const previewContainer = document.getElementById('preview-container');
            const fileInfo = document.getElementById('file-info');
            const previewImage = document.getElementById('preview-image');
            const fileName = document.getElementById('file-name');
            const fileSize = document.getElementById('file-size');
            
            previewContainer.classList.add('hidden');
            fileInfo.classList.remove('hidden');
            
            fileName.textContent = file.name;
            fileSize.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                previewImage.classList.add('hidden');
            }
            
            lucide.createIcons();
        }
    }
</script>
