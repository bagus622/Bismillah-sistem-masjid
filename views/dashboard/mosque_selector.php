<!DOCTYPE html>
<html lang="<?= getLang() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - Bismillah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gray-50 font-['Inter']">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-4xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Pilih Masjid</h1>
                <p class="text-gray-600">Sebagai Super Admin, pilih masjid yang ingin Anda kelola</p>
            </div>

            <!-- Search Bar -->
            <div class="mb-6">
                <div class="relative max-w-2xl mx-auto">
                    <input type="text" 
                           id="searchMosque" 
                           placeholder="Cari masjid berdasarkan nama, alamat, telepon, atau email..." 
                           class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-all duration-200 text-gray-700 shadow-sm">
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                        <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                    </div>
                    <div id="searchCount" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-sm text-gray-500 hidden">
                        <span id="resultCount">0</span> hasil
                    </div>
                </div>
            </div>

            <!-- Flash Messages -->
            <?php if ($error = flash('error')): ?>
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                    <span><?= $error ?></span>
                </div>
            <?php endif; ?>

            <?php if ($success = flash('success')): ?>
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    <span><?= $success ?></span>
                </div>
            <?php endif; ?>

            <!-- Mosque Grid -->
            <div id="mosqueGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (empty($mosques)): ?>
                    <div class="col-span-full text-center py-12">
                        <i data-lucide="building" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                        <p class="text-gray-600">Belum ada masjid terdaftar</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($mosques as $mosque): ?>
                        <form method="POST" action="<?= base_url('dashboard/switchMosque') ?>" class="mosque-card bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow border border-gray-200"
                              data-mosque-name="<?= strtolower(sanitize($mosque['name'])) ?>"
                              data-mosque-address="<?= strtolower(sanitize($mosque['address'] ?? '')) ?>"
                              data-mosque-phone="<?= strtolower(sanitize($mosque['phone'] ?? '')) ?>"
                              data-mosque-email="<?= strtolower(sanitize($mosque['email'] ?? '')) ?>">
                            <input type="hidden" name="mosque_id" value="<?= $mosque['id'] ?>">
                            
                            <button type="submit" class="w-full p-6 text-left">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 bg-violet-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="building" class="w-6 h-6 text-violet-600"></i>
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-gray-900 mb-1 truncate"><?= sanitize($mosque['name']) ?></h3>
                                        
                                        <?php if (!empty($mosque['address'])): ?>
                                            <p class="text-sm text-gray-600 mb-2 line-clamp-2">
                                                <i data-lucide="map-pin" class="w-3 h-3 inline"></i>
                                                <?= sanitize($mosque['address']) ?>
                                            </p>
                                        <?php endif; ?>
                                        
                                        <div class="flex flex-col gap-1 text-xs text-gray-500">
                                            <?php if (!empty($mosque['phone'])): ?>
                                                <span>
                                                    <i data-lucide="phone" class="w-3 h-3 inline"></i>
                                                    <?= sanitize($mosque['phone']) ?>
                                                </span>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($mosque['email'])): ?>
                                                <span>
                                                    <i data-lucide="mail" class="w-3 h-3 inline"></i>
                                                    <?= sanitize($mosque['email']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <span class="text-violet-600 text-sm font-medium flex items-center gap-2">
                                        Pilih Masjid
                                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                    </span>
                                </div>
                            </button>
                        </form>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- No Results Message -->
            <div id="noResults" class="hidden text-center py-12">
                <i data-lucide="search-x" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak ada hasil</h3>
                <p class="text-gray-600">Tidak ditemukan masjid yang sesuai dengan pencarian Anda</p>
                <button onclick="clearSearch()" class="mt-4 text-violet-600 hover:text-violet-700 font-medium">
                    Hapus pencarian
                </button>
            </div>

            <!-- Logout Button -->
            <div class="mt-8 text-center">
                <a href="<?= base_url('auth/logout') ?>" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        const searchInput = document.getElementById('searchMosque');
        const mosqueCards = document.querySelectorAll('.mosque-card');
        const mosqueGrid = document.getElementById('mosqueGrid');
        const noResults = document.getElementById('noResults');
        const searchCount = document.getElementById('searchCount');
        const resultCount = document.getElementById('resultCount');

        function performSearch() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            let visibleCount = 0;

            mosqueCards.forEach(card => {
                const name = card.dataset.mosqueName || '';
                const address = card.dataset.mosqueAddress || '';
                const phone = card.dataset.mosquePhone || '';
                const email = card.dataset.mosqueEmail || '';

                // Check if search term matches any field
                const matches = name.includes(searchTerm) || 
                               address.includes(searchTerm) || 
                               phone.includes(searchTerm) || 
                               email.includes(searchTerm);

                if (matches || searchTerm === '') {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Show/hide no results message
            if (visibleCount === 0 && searchTerm !== '') {
                mosqueGrid.style.display = 'none';
                noResults.classList.remove('hidden');
                searchCount.classList.add('hidden');
            } else {
                mosqueGrid.style.display = 'grid';
                noResults.classList.add('hidden');
                
                // Show result count if searching
                if (searchTerm !== '') {
                    resultCount.textContent = visibleCount;
                    searchCount.classList.remove('hidden');
                } else {
                    searchCount.classList.add('hidden');
                }
            }
        }

        function clearSearch() {
            searchInput.value = '';
            performSearch();
            searchInput.focus();
        }

        // Event listeners
        searchInput.addEventListener('input', performSearch);
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                clearSearch();
            }
        });

        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>
</html>
