<!DOCTYPE html>
<html lang="<?= getLang() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - Register</title>
    
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
                    <?= getLang() === 'id' ? 'Mulai Kelola Keuangan Masjid' : 'Start Managing Mosque Finances' ?>
                </h1>
                <p class="text-lg text-violet-100 mb-12">
                    <?= getLang() === 'id' ? 'Daftar sekarang dan dapatkan akses gratis selamanya' : 'Register now and get free access forever' ?>
                </p>
                
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                            <i data-lucide="check" class="w-5 h-5"></i>
                        </div>
                        <span class="text-violet-50"><?= getLang() === 'id' ? 'Gratis selamanya' : 'Free forever' ?></span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                            <i data-lucide="check" class="w-5 h-5"></i>
                        </div>
                        <span class="text-violet-50"><?= getLang() === 'id' ? 'Setup dalam 5 menit' : 'Setup in 5 minutes' ?></span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                            <i data-lucide="check" class="w-5 h-5"></i>
                        </div>
                        <span class="text-violet-50"><?= getLang() === 'id' ? 'Tanpa kartu kredit' : 'No credit card required' ?></span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                            <i data-lucide="check" class="w-5 h-5"></i>
                        </div>
                        <span class="text-violet-50"><?= getLang() === 'id' ? 'Data aman & terenkripsi' : 'Secure & encrypted data' ?></span>
                    </div>
                </div>
            </div>
            
            <div class="text-violet-100 text-sm">
                © 2026 Bismillah. All rights reserved.
            </div>
        </div>
        
        <!-- Right Side - Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 overflow-y-auto">
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
                
                <!-- Progress Steps -->
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-2">
                        <div id="step1-indicator" class="w-8 h-8 bg-violet-600 text-white rounded-full flex items-center justify-center text-sm font-semibold">1</div>
                        <span id="step1-text" class="text-sm font-medium text-violet-600"><?= getLang() === 'id' ? 'Masjid' : 'Mosque' ?></span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 mx-3">
                        <div id="progress-bar" class="h-full bg-violet-600 transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div id="step2-indicator" class="w-8 h-8 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center text-sm font-semibold">2</div>
                        <span id="step2-text" class="text-sm font-medium text-gray-400"><?= getLang() === 'id' ? 'Admin' : 'Admin' ?></span>
                    </div>
                </div>
                
                <!-- Alert -->
                <div id="alert" class="hidden mb-6"></div>
                
                <!-- Form -->
                <form id="registerForm" class="space-y-6">
                    <!-- Step 1: Mosque Data -->
                    <div id="step1">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">
                            <?= getLang() === 'id' ? 'Data Masjid' : 'Mosque Information' ?>
                        </h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="mosque_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    <?= getLang() === 'id' ? 'Nama Masjid' : 'Mosque Name' ?> <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="mosque_name"
                                       name="mosque_name" 
                                       placeholder="<?= getLang() === 'id' ? 'Masjid Al-Ikhlas' : 'Al-Ikhlas Mosque' ?>"
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-600 focus:border-transparent transition-all"
                                       required>
                            </div>
                            
                            <div>
                                <label for="mosque_address" class="block text-sm font-medium text-gray-700 mb-2">
                                    <?= getLang() === 'id' ? 'Alamat' : 'Address' ?>
                                </label>
                                <textarea id="mosque_address"
                                          name="mosque_address" 
                                          rows="3"
                                          placeholder="<?= getLang() === 'id' ? 'Jl. Masjid No. 123' : '123 Mosque Street' ?>"
                                          class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-600 focus:border-transparent transition-all"></textarea>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="mosque_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        <?= getLang() === 'id' ? 'Telepon' : 'Phone' ?>
                                    </label>
                                    <input type="tel" 
                                           id="mosque_phone"
                                           name="mosque_phone" 
                                           placeholder="021-1234567"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-600 focus:border-transparent transition-all">
                                </div>
                                
                                <div>
                                    <label for="mosque_email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email
                                    </label>
                                    <input type="email" 
                                           id="mosque_email"
                                           name="mosque_email" 
                                           placeholder="info@masjid.com"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-600 focus:border-transparent transition-all">
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" 
                                onclick="nextStep()"
                                class="w-full mt-6 px-6 py-3 bg-violet-600 text-white font-medium rounded-lg hover:bg-violet-700 transition-all flex items-center justify-center">
                            <?= getLang() === 'id' ? 'Lanjutkan' : 'Continue' ?>
                            <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                        </button>
                    </div>
                    
                    <!-- Step 2: Admin Data -->
                    <div id="step2" class="hidden">
                        <div class="flex items-center mb-6">
                            <button type="button" 
                                    onclick="prevStep()" 
                                    class="p-2 hover:bg-gray-100 rounded-lg transition-colors mr-3">
                                <i data-lucide="arrow-left" class="w-5 h-5 text-gray-600"></i>
                            </button>
                            <h2 class="text-2xl font-bold text-gray-900">
                                <?= getLang() === 'id' ? 'Data Administrator' : 'Administrator Data' ?>
                            </h2>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="admin_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    <?= getLang() === 'id' ? 'Nama Lengkap' : 'Full Name' ?> <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="admin_name"
                                       name="admin_name" 
                                       placeholder="<?= getLang() === 'id' ? 'Ahmad Fauzi' : 'John Doe' ?>"
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-600 focus:border-transparent transition-all"
                                       required>
                            </div>
                            
                            <div>
                                <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       id="admin_email"
                                       name="admin_email" 
                                       placeholder="admin@masjid.com"
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-600 focus:border-transparent transition-all"
                                       required>
                            </div>
                            
                            <div>
                                <label for="admin_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    <?= getLang() === 'id' ? 'Telepon' : 'Phone' ?>
                                </label>
                                <input type="tel" 
                                       id="admin_phone"
                                       name="admin_phone" 
                                       placeholder="08123456789"
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-600 focus:border-transparent transition-all">
                            </div>
                            
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" 
                                       id="password"
                                       name="password" 
                                       placeholder="<?= getLang() === 'id' ? 'Minimal 6 karakter' : 'Minimum 6 characters' ?>"
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-600 focus:border-transparent transition-all"
                                       required>
                            </div>
                            
                            <div>
                                <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    <?= getLang() === 'id' ? 'Konfirmasi Password' : 'Confirm Password' ?> <span class="text-red-500">*</span>
                                </label>
                                <input type="password" 
                                       id="confirm_password"
                                       name="confirm_password" 
                                       placeholder="<?= getLang() === 'id' ? 'Ulangi password' : 'Repeat password' ?>"
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-600 focus:border-transparent transition-all"
                                       required>
                            </div>
                            
                            <div class="p-4 bg-blue-50 border border-blue-100 rounded-lg flex items-start">
                                <i data-lucide="info" class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0"></i>
                                <p class="text-sm text-blue-800">
                                    <?= getLang() === 'id' ? 'Akun ini akan menjadi Super Admin dengan akses penuh' : 'This account will be Super Admin with full access' ?>
                                </p>
                            </div>
                        </div>
                        
                        <button type="submit" 
                                id="submitBtn"
                                class="w-full mt-6 px-6 py-3 bg-violet-600 text-white font-medium rounded-lg hover:bg-violet-700 transition-all flex items-center justify-center">
                            <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i>
                            <?= getLang() === 'id' ? 'Daftar Sekarang' : 'Register Now' ?>
                        </button>
                    </div>
                </form>
                
                <!-- Login Link -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-600">
                        <?= getLang() === 'id' ? 'Sudah punya akun?' : 'Already have an account?' ?>
                        <a href="<?= base_url('auth/login') ?>" class="text-violet-600 font-medium hover:text-violet-700 transition-colors">
                            <?= getLang() === 'id' ? 'Masuk' : 'Sign In' ?>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Page Loader -->
    <script src="<?= base_url('public/js/page-loader.js') ?>"></script>
    
    <script>
        let currentStep = 1;
        
        function nextStep() {
            const mosqueName = document.getElementById('mosque_name').value;
            if (!mosqueName) {
                showAlert('error', '<?= getLang() === 'id' ? 'Nama masjid wajib diisi' : 'Mosque name is required' ?>');
                return;
            }
            
            currentStep = 2;
            updateStepUI();
        }
        
        function prevStep() {
            currentStep = 1;
            updateStepUI();
        }
        
        function updateStepUI() {
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');
            const step1Indicator = document.getElementById('step1-indicator');
            const step2Indicator = document.getElementById('step2-indicator');
            const step1Text = document.getElementById('step1-text');
            const step2Text = document.getElementById('step2-text');
            const progressBar = document.getElementById('progress-bar');
            
            if (currentStep === 1) {
                step1.classList.remove('hidden');
                step2.classList.add('hidden');
                step1Indicator.className = 'w-8 h-8 bg-violet-600 text-white rounded-full flex items-center justify-center text-sm font-semibold';
                step2Indicator.className = 'w-8 h-8 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center text-sm font-semibold';
                step1Text.className = 'text-sm font-medium text-violet-600';
                step2Text.className = 'text-sm font-medium text-gray-400';
                progressBar.style.width = '0%';
                step1Indicator.innerHTML = '1';
            } else {
                step1.classList.add('hidden');
                step2.classList.remove('hidden');
                step1Indicator.className = 'w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-semibold';
                step2Indicator.className = 'w-8 h-8 bg-violet-600 text-white rounded-full flex items-center justify-center text-sm font-semibold';
                step1Text.className = 'text-sm font-medium text-green-600';
                step2Text.className = 'text-sm font-medium text-violet-600';
                progressBar.style.width = '100%';
                step1Indicator.innerHTML = '<i data-lucide="check" class="w-4 h-4"></i>';
                lucide.createIcons();
            }
        }
        
        function showAlert(type, message) {
            const alert = document.getElementById('alert');
            const colors = {
                error: 'bg-red-50 border-red-100 text-red-800',
                success: 'bg-green-50 border-green-100 text-green-800',
                info: 'bg-blue-50 border-blue-100 text-blue-800'
            };
            const icons = {
                error: 'alert-circle',
                success: 'check-circle',
                info: 'info'
            };
            
            alert.className = `mb-6 p-4 border rounded-lg flex items-start ${colors[type]}`;
            alert.innerHTML = `
                <i data-lucide="${icons[type]}" class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0"></i>
                <span class="text-sm">${message}</span>
            `;
            alert.classList.remove('hidden');
            lucide.createIcons();
            alert.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
        
        function hideAlert() {
            document.getElementById('alert').classList.add('hidden');
        }
        
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            hideAlert();
            
            const submitBtn = document.getElementById('submitBtn');
            const originalContent = submitBtn.innerHTML;
            
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                showAlert('error', '<?= getLang() === 'id' ? 'Password tidak cocok' : 'Passwords do not match' ?>');
                return;
            }
            
            if (password.length < 6) {
                showAlert('error', '<?= getLang() === 'id' ? 'Password minimal 6 karakter' : 'Password must be at least 6 characters' ?>');
                return;
            }
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
            `;
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('<?= base_url('auth/doRegister') ?>', {
                    method: 'POST',
                    body: formData
                });
                
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('<?= getLang() === 'id' ? 'Server mengembalikan response yang tidak valid' : 'Server returned invalid response' ?>');
                }
                
                const result = await response.json();
                
                if (result.success) {
                    showAlert('success', result.message);
                    setTimeout(() => {
                        window.location.href = result.data.redirect;
                    }, 1500);
                } else {
                    showAlert('error', result.message);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalContent;
                    lucide.createIcons();
                }
            } catch (error) {
                showAlert('error', '<?= getLang() === 'id' ? 'Terjadi kesalahan: ' : 'An error occurred: ' ?>' + error.message);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalContent;
                lucide.createIcons();
            }
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
</body>
</html>
