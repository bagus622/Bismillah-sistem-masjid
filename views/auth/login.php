<!DOCTYPE html>
<html lang="<?= getLang() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - Login</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
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
    </style>
</head>
<body class="bg-white">
    <div class="min-h-screen flex">
        <!-- Left Side - Brand -->
        <div class="hidden lg:flex lg:w-1/2 bg-violet-600 p-12 flex-col justify-between">
            <div>
                <a href="<?= base_url('home') ?>" class="flex items-center space-x-3 text-white">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center text-white text-lg font-semibold backdrop-blur-sm">
                        B
                    </div>
                    <span class="text-2xl font-bold">Bismillah</span>
                </a>
            </div>
            
            <div class="text-white">
                <h1 class="text-4xl font-bold mb-4 leading-tight">
                    <?= getLang() === 'id' ? 'Kelola Keuangan Masjid dengan Mudah' : 'Manage Mosque Finances Easily' ?>
                </h1>
                <p class="text-lg text-violet-100 mb-12">
                    <?= getLang() === 'id' ? 'Platform modern untuk manajemen keuangan masjid yang transparan dan akurat' : 'Modern platform for transparent and accurate mosque financial management' ?>
                </p>
                
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                            <i data-lucide="check" class="w-5 h-5"></i>
                        </div>
                        <span class="text-violet-50"><?= getLang() === 'id' ? 'Manajemen transaksi lengkap' : 'Complete transaction management' ?></span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                            <i data-lucide="check" class="w-5 h-5"></i>
                        </div>
                        <span class="text-violet-50"><?= getLang() === 'id' ? 'Laporan real-time' : 'Real-time reports' ?></span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                            <i data-lucide="check" class="w-5 h-5"></i>
                        </div>
                        <span class="text-violet-50"><?= getLang() === 'id' ? 'Multi user & role' : 'Multi user & role' ?></span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                            <i data-lucide="check" class="w-5 h-5"></i>
                        </div>
                        <span class="text-violet-50"><?= getLang() === 'id' ? 'Keamanan terjamin' : 'Guaranteed security' ?></span>
                    </div>
                </div>
            </div>
            
            <div class="text-violet-100 text-sm">
                © 2026 Bismillah. All rights reserved.
            </div>
        </div>
        
        <!-- Right Side - Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden mb-8">
                    <a href="<?= base_url('home') ?>" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-violet-600 rounded-lg flex items-center justify-center text-white text-lg font-semibold">
                            B
                        </div>
                        <span class="text-2xl font-bold text-gray-900">Bismillah</span>
                    </a>
                </div>
                
                <!-- Language Selector -->
                <div class="flex items-center justify-between mb-8">
                    <div class="flex gap-2">
                        <a href="<?= base_url('auth/setlang?lang=id') ?>" 
                           class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors <?= getLang() === 'id' ? 'bg-violet-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">
                            ID
                        </a>
                        <a href="<?= base_url('auth/setlang?lang=en') ?>" 
                           class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors <?= getLang() === 'en' ? 'bg-violet-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">
                            EN
                        </a>
                    </div>
                    <a href="<?= base_url('home') ?>" class="text-sm text-gray-600 hover:text-gray-900 flex items-center">
                        <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                        <?= getLang() === 'id' ? 'Kembali' : 'Back' ?>
                    </a>
                </div>
                
                <!-- Header -->
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">
                        <?= getLang() === 'id' ? 'Selamat Datang' : 'Welcome Back' ?>
                    </h2>
                    <p class="text-gray-600">
                        <?= getLang() === 'id' ? 'Masuk ke akun Anda untuk melanjutkan' : 'Sign in to your account to continue' ?>
                    </p>
                </div>
                
                <!-- Flash Messages -->
                <?php if (flash('error')): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-lg flex items-start">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 mr-3 mt-0.5 flex-shrink-0"></i>
                    <span class="text-sm text-red-800"><?= flash('error') ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (flash('success')): ?>
                <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-lg flex items-start">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600 mr-3 mt-0.5 flex-shrink-0"></i>
                    <span class="text-sm text-green-800"><?= flash('success') ?></span>
                </div>
                <?php endif; ?>
                
                <!-- Form -->
                <form action="<?= base_url('auth/doLogin') ?>" method="POST" class="space-y-6">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            <?= getLang() === 'id' ? 'Email' : 'Email' ?>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               placeholder="name@example.com"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-600 focus:border-transparent transition-all"
                               required 
                               autofocus>
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <?= getLang() === 'id' ? 'Password' : 'Password' ?>
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               placeholder="••••••••"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-600 focus:border-transparent transition-all"
                               required>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="remember" 
                                   class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-600">
                            <span class="ml-2 text-sm text-gray-600">
                                <?= getLang() === 'id' ? 'Ingat saya' : 'Remember me' ?>
                            </span>
                        </label>
                    </div>
                    
                    <button type="submit" 
                            class="w-full px-6 py-3 bg-violet-600 text-white font-medium rounded-lg hover:bg-violet-700 transition-all focus:outline-none focus:ring-2 focus:ring-violet-600 focus:ring-offset-2">
                        <?= getLang() === 'id' ? 'Masuk' : 'Sign In' ?>
                    </button>
                </form>
                
                <!-- Register Link -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-600">
                        <?= getLang() === 'id' ? 'Belum punya akun?' : "Don't have an account?" ?>
                        <a href="<?= base_url('auth/register') ?>" class="text-violet-600 font-medium hover:text-violet-700 transition-colors">
                            <?= getLang() === 'id' ? 'Daftar Sekarang' : 'Register Now' ?>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Page Loader -->
    <script src="<?= base_url('public/js/page-loader.js') ?>"></script>
    
    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
