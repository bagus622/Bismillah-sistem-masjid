<!-- Hero Section -->
<section class="py-24 lg:py-32 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <!-- Left Content -->
            <div>
                <div class="inline-flex items-center px-3 py-1.5 bg-violet-50 text-violet-700 rounded-full text-sm font-medium mb-6">
                    <span class="w-2 h-2 bg-violet-600 rounded-full mr-2"></span>
                    Sistem Manajemen Keuangan Masjid
                </div>
                
                <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight tracking-tight">
                    Kelola Keuangan<br/>
                    Masjid dengan<br/>
                    <span class="text-violet-600">Transparan</span>
                </h1>
                
                <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                    Platform modern untuk mengelola pemasukan, pengeluaran, dan laporan keuangan masjid secara digital.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 mb-12">
                    <a href="<?= base_url('auth/register') ?>" class="inline-flex items-center justify-center px-6 py-3 bg-violet-600 text-white font-medium rounded-lg hover:bg-violet-700 transition-all">
                        Mulai Gratis
                        <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                    </a>
                    <a href="#features" class="inline-flex items-center justify-center px-6 py-3 bg-white border border-gray-200 text-gray-700 font-medium rounded-lg hover:border-gray-300 transition-all">
                        Lihat Demo
                    </a>
                </div>
                
                <div class="flex items-center gap-8 text-sm text-gray-600">
                    <div class="flex items-center">
                        <i data-lucide="check" class="w-4 h-4 text-violet-600 mr-2"></i>
                        Gratis selamanya
                    </div>
                    <div class="flex items-center">
                        <i data-lucide="check" class="w-4 h-4 text-violet-600 mr-2"></i>
                        Setup 5 menit
                    </div>
                </div>
            </div>
            
            <!-- Right Content - Dashboard Preview -->
            <div class="relative">
                <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-8">
                    <!-- Mock Dashboard -->
                    <div class="space-y-6">
                        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-violet-600 rounded-lg flex items-center justify-center text-white font-semibold">
                                    M
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Masjid Al-Ikhlas</p>
                                    <p class="text-sm text-gray-500">Dashboard Keuangan</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <p class="text-sm text-gray-600 font-medium mb-1">Pemasukan</p>
                                <p class="text-2xl font-bold text-gray-900">Rp 45.5M</p>
                                <p class="text-xs text-green-600 mt-1">↑ 12%</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <p class="text-sm text-gray-600 font-medium mb-1">Pengeluaran</p>
                                <p class="text-2xl font-bold text-gray-900">Rp 32.8M</p>
                                <p class="text-xs text-red-600 mt-1">↓ 5%</p>
                            </div>
                        </div>
                        
                        <div class="bg-violet-50 rounded-xl p-4 border border-violet-100">
                            <p class="text-sm text-violet-700 font-medium mb-1">Saldo Akhir</p>
                            <p class="text-3xl font-bold text-violet-600">Rp 12.7M</p>
                        </div>
                        
                        <div class="flex items-center justify-between pt-2">
                            <div class="flex -space-x-2">
                                <div class="w-8 h-8 bg-violet-500 rounded-full border-2 border-white"></div>
                                <div class="w-8 h-8 bg-gray-400 rounded-full border-2 border-white"></div>
                                <div class="w-8 h-8 bg-gray-300 rounded-full border-2 border-white"></div>
                            </div>
                            <p class="text-xs text-gray-500">3 admin aktif</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-16 bg-gray-50 border-y border-gray-100">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center">
                <p class="text-4xl font-bold text-gray-900 mb-2">500+</p>
                <p class="text-sm text-gray-600">Masjid Terdaftar</p>
            </div>
            <div class="text-center">
                <p class="text-4xl font-bold text-gray-900 mb-2">10K+</p>
                <p class="text-sm text-gray-600">Transaksi/Bulan</p>
            </div>
            <div class="text-center">
                <p class="text-4xl font-bold text-gray-900 mb-2">99.9%</p>
                <p class="text-sm text-gray-600">Uptime</p>
            </div>
            <div class="text-center">
                <p class="text-4xl font-bold text-gray-900 mb-2">24/7</p>
                <p class="text-sm text-gray-600">Support</p>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4 tracking-tight">
                Fitur Lengkap untuk Manajemen Keuangan
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Semua yang Anda butuhkan dalam satu platform
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="card rounded-2xl p-8">
                <div class="w-12 h-12 bg-violet-600 rounded-xl flex items-center justify-center mb-6">
                    <i data-lucide="wallet" class="w-6 h-6 text-white"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Manajemen Transaksi</h3>
                <p class="text-gray-600 leading-relaxed">
                    Catat pemasukan dan pengeluaran dengan mudah. Lengkap dengan bukti foto dan kategorisasi.
                </p>
            </div>
            
            <!-- Feature 2 -->
            <div class="card rounded-2xl p-8">
                <div class="w-12 h-12 bg-violet-600 rounded-xl flex items-center justify-center mb-6">
                    <i data-lucide="bar-chart-3" class="w-6 h-6 text-white"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Laporan Real-time</h3>
                <p class="text-gray-600 leading-relaxed">
                    Dashboard interaktif dengan grafik dan statistik keuangan yang update secara real-time.
                </p>
            </div>
            
            <!-- Feature 3 -->
            <div class="card rounded-2xl p-8">
                <div class="w-12 h-12 bg-violet-600 rounded-xl flex items-center justify-center mb-6">
                    <i data-lucide="users" class="w-6 h-6 text-white"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Multi User & Role</h3>
                <p class="text-gray-600 leading-relaxed">
                    Kelola tim dengan sistem role dan permission. Admin, bendahara, dan viewer.
                </p>
            </div>
            
            <!-- Feature 4 -->
            <div class="card rounded-2xl p-8">
                <div class="w-12 h-12 bg-violet-600 rounded-xl flex items-center justify-center mb-6">
                    <i data-lucide="calendar" class="w-6 h-6 text-white"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Kalender Keuangan</h3>
                <p class="text-gray-600 leading-relaxed">
                    Visualisasi transaksi dalam kalender. Lihat pola pemasukan dan pengeluaran per hari.
                </p>
            </div>
            
            <!-- Feature 5 -->
            <div class="card rounded-2xl p-8">
                <div class="w-12 h-12 bg-violet-600 rounded-xl flex items-center justify-center mb-6">
                    <i data-lucide="target" class="w-6 h-6 text-white"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Budget & Goals</h3>
                <p class="text-gray-600 leading-relaxed">
                    Tetapkan anggaran dan target keuangan. Monitor progress dengan notifikasi otomatis.
                </p>
            </div>
            
            <!-- Feature 6 -->
            <div class="card rounded-2xl p-8">
                <div class="w-12 h-12 bg-violet-600 rounded-xl flex items-center justify-center mb-6">
                    <i data-lucide="shield-check" class="w-6 h-6 text-white"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Keamanan Terjamin</h3>
                <p class="text-gray-600 leading-relaxed">
                    Data terenkripsi dengan standar bank. Backup otomatis dan audit trail lengkap.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4 tracking-tight">
                Pilih Paket yang Sesuai
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Mulai gratis, upgrade kapan saja
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <!-- Free Plan -->
            <div class="card rounded-2xl p-8">
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Gratis</h3>
                    <div class="mb-4">
                        <span class="text-5xl font-bold text-gray-900">Rp 0</span>
                        <span class="text-gray-600">/bulan</span>
                    </div>
                    <p class="text-gray-600">Untuk masjid kecil</p>
                </div>
                
                <ul class="space-y-3 mb-8">
                    <li class="flex items-start">
                        <i data-lucide="check" class="w-5 h-5 text-violet-600 mr-3 mt-0.5 flex-shrink-0"></i>
                        <span class="text-gray-700">50 transaksi/bulan</span>
                    </li>
                    <li class="flex items-start">
                        <i data-lucide="check" class="w-5 h-5 text-violet-600 mr-3 mt-0.5 flex-shrink-0"></i>
                        <span class="text-gray-700">1 admin user</span>
                    </li>
                    <li class="flex items-start">
                        <i data-lucide="check" class="w-5 h-5 text-violet-600 mr-3 mt-0.5 flex-shrink-0"></i>
                        <span class="text-gray-700">Laporan dasar</span>
                    </li>
                    <li class="flex items-start">
                        <i data-lucide="check" class="w-5 h-5 text-violet-600 mr-3 mt-0.5 flex-shrink-0"></i>
                        <span class="text-gray-700">Support email</span>
                    </li>
                </ul>
                
                <a href="<?= base_url('auth/register') ?>" class="block w-full text-center px-6 py-3 bg-gray-100 text-gray-900 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Mulai Gratis
                </a>
            </div>
            
            <!-- Pro Plan -->
            <div class="card rounded-2xl p-8 border-2 border-violet-600 relative">
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                    <span class="px-4 py-1.5 bg-violet-600 text-white text-sm font-medium rounded-full">
                        Populer
                    </span>
                </div>
                
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Pro</h3>
                    <div class="mb-4">
                        <span class="text-5xl font-bold text-violet-600">Rp 99K</span>
                        <span class="text-gray-600">/bulan</span>
                    </div>
                    <p class="text-gray-600">Untuk masjid menengah</p>
                </div>
                
                <ul class="space-y-3 mb-8">
                    <li class="flex items-start">
                        <i data-lucide="check" class="w-5 h-5 text-violet-600 mr-3 mt-0.5 flex-shrink-0"></i>
                        <span class="text-gray-700">Transaksi unlimited</span>
                    </li>
                    <li class="flex items-start">
                        <i data-lucide="check" class="w-5 h-5 text-violet-600 mr-3 mt-0.5 flex-shrink-0"></i>
                        <span class="text-gray-700">5 admin users</span>
                    </li>
                    <li class="flex items-start">
                        <i data-lucide="check" class="w-5 h-5 text-violet-600 mr-3 mt-0.5 flex-shrink-0"></i>
                        <span class="text-gray-700">Laporan lengkap</span>
                    </li>
                    <li class="flex items-start">
                        <i data-lucide="check" class="w-5 h-5 text-violet-600 mr-3 mt-0.5 flex-shrink-0"></i>
                        <span class="text-gray-700">Priority support</span>
                    </li>
                </ul>
                
                <a href="<?= base_url('auth/register') ?>" class="block w-full text-center px-6 py-3 bg-violet-600 text-white font-medium rounded-lg hover:bg-violet-700 transition-all">
                    Pilih Pro
                </a>
            </div>
            
            <!-- Ultra Pro Plan -->
            <div class="card rounded-2xl p-8">
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Ultra Pro</h3>
                    <div class="mb-4">
                        <span class="text-5xl font-bold text-gray-900">Rp 199K</span>
                        <span class="text-gray-600">/bulan</span>
                    </div>
                    <p class="text-gray-600">Untuk masjid besar</p>
                </div>
                
                <ul class="space-y-3 mb-8">
                    <li class="flex items-start">
                        <i data-lucide="check" class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0"></i>
                        <span class="text-gray-700">Semua fitur Pro</span>
                    </li>
                    <li class="flex items-start">
                        <i data-lucide="check" class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0"></i>
                        <span class="text-gray-700">Unlimited users</span>
                    </li>
                    <li class="flex items-start">
                        <i data-lucide="check" class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0"></i>
                        <span class="text-gray-700">Custom branding</span>
                    </li>
                    <li class="flex items-start">
                        <i data-lucide="check" class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0"></i>
                        <span class="text-gray-700">24/7 phone support</span>
                    </li>
                </ul>
                
                <a href="<?= base_url('auth/register') ?>" class="block w-full text-center px-6 py-3 bg-gray-900 text-white font-medium rounded-lg hover:bg-gray-800 transition-colors">
                    Pilih Ultra Pro
                </a>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-24 bg-violet-600">
    <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
        <h2 class="text-4xl lg:text-5xl font-bold text-white mb-6 tracking-tight">
            Siap Memulai?
        </h2>
        <p class="text-xl text-violet-100 mb-8">
            Bergabunglah dengan ratusan masjid yang sudah menggunakan Bismillah
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?= base_url('auth/register') ?>" class="inline-flex items-center justify-center px-8 py-4 bg-white text-violet-600 font-medium rounded-lg hover:bg-gray-50 transition-all">
                Daftar Gratis Sekarang
                <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
            </a>
            <a href="<?= base_url('auth/login') ?>" class="inline-flex items-center justify-center px-8 py-4 bg-transparent border-2 border-white text-white font-medium rounded-lg hover:bg-white hover:text-violet-600 transition-all">
                Sudah Punya Akun?
            </a>
        </div>
    </div>
</section>
