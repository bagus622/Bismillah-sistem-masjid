<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Bismillah - Sistem Manajemen Keuangan Masjid' ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Custom Styles -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        body {
            background: #ffffff;
        }
        
        .btn-primary {
            background: #7c3aed;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .btn-primary:hover {
            background: #6d28d9;
            transform: translateY(-1px);
            box-shadow: 0 10px 25px -5px rgba(124, 58, 237, 0.3);
        }
        
        .card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card:hover {
            border-color: #e2e8f0;
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.08);
            transform: translateY(-4px);
        }
        
        .text-primary {
            color: #7c3aed;
        }
        
        .bg-primary {
            background: #7c3aed;
        }
        
        .border-primary {
            border-color: #7c3aed;
        }
    </style>
</head>
<body class="bg-white">
    
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 bg-white/80 backdrop-blur-xl border-b border-gray-100 z-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="<?= base_url('home') ?>" class="flex items-center space-x-3">
                        <div class="w-9 h-9 bg-violet-600 rounded-lg flex items-center justify-center text-white text-lg font-semibold">
                            B
                        </div>
                        <span class="text-lg font-semibold text-gray-900">Bismillah</span>
                    </a>
                </div>
                
                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="<?= base_url('home') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors">Beranda</a>
                    <a href="<?= base_url('home/features') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors">Fitur</a>
                    <a href="<?= base_url('home/pricing') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors">Harga</a>
                    <a href="<?= base_url('home/about') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors">Tentang</a>
                </div>
                
                <!-- CTA Buttons -->
                <div class="flex items-center space-x-3">
                    <a href="<?= base_url('auth/login') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                        Masuk
                    </a>
                    <a href="<?= base_url('auth/register') ?>" class="px-5 py-2 bg-violet-600 text-white text-sm font-medium rounded-lg hover:bg-violet-700 transition-all">
                        Daftar Gratis
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-16">
        <?php include VIEW_PATH . '/' . $view . '.php'; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-50 border-t border-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- About -->
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-9 h-9 bg-violet-600 rounded-lg flex items-center justify-center text-white text-lg font-semibold">
                            B
                        </div>
                        <span class="text-lg font-semibold text-gray-900">Bismillah</span>
                    </div>
                    <p class="text-sm text-gray-600">
                        Sistem manajemen keuangan masjid yang modern dan terpercaya.
                    </p>
                </div>
                
                <!-- Product -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Produk</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="<?= base_url('home/features') ?>" class="hover:text-gray-900 transition-colors">Fitur</a></li>
                        <li><a href="<?= base_url('home/pricing') ?>" class="hover:text-gray-900 transition-colors">Harga</a></li>
                        <li><a href="#" class="hover:text-gray-900 transition-colors">Demo</a></li>
                    </ul>
                </div>
                
                <!-- Company -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Perusahaan</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="<?= base_url('home/about') ?>" class="hover:text-gray-900 transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-gray-900 transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-gray-900 transition-colors">Kontak</a></li>
                    </ul>
                </div>
                
                <!-- Legal -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Legal</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-gray-900 transition-colors">Privasi</a></li>
                        <li><a href="#" class="hover:text-gray-900 transition-colors">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-200 pt-8 text-center text-sm text-gray-500">
                <p>&copy; 2026 Bismillah. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Page Loader -->
    <script src="<?= base_url('public/js/page-loader.js') ?>"></script>
    
    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
