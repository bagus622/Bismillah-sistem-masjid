<!DOCTYPE html>
<html lang="<?= getLang() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - <?= $pageTitle ?? 'Dashboard' ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Currency Formatter -->
    <script src="<?= base_url('public/js/currency-formatter.js') ?>"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        body {
            background-color: #f8fafc;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Sidebar Transition */
        .sidebar-transition {
            transition: all 300ms ease-in-out;
        }
        
        /* Card Hover Effect */
        .card-hover {
            transition: all 300ms ease-in-out;
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }
        
        /* Menu Active */
        .menu-active {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-left: 3px solid white;
        }
        
        /* Dropdown Animation */
        [x-cloak] {
            display: none !important;
        }
        
        /* Page Content Transition */
        .page-content {
            transition: margin-left 300ms ease-in-out;
        }
        
        /* Custom Cursor - High Contrast Design */
        .custom-cursor {
            position: fixed;
            width: 24px;
            height: 24px;
            border: 2px solid #1e293b;
            border-radius: 50%;
            pointer-events: none;
            z-index: 9999;
            transform: translate(-50%, -50%);
            transition: width 0.15s ease, height 0.15s ease, background-color 0.15s ease, border-color 0.15s ease;
            mix-blend-mode: difference;
        }
        
        .custom-cursor.hover {
            width: 14px;
            height: 14px;
            background-color: #4f46e5;
            border-color: #4f46e5;
            mix-blend-mode: normal;
        }
        
        .custom-cursor.hover::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 3px;
            height: 3px;
            background: white;
            border-radius: 50%;
            transform: translate(-50%, -50%);
        }
        
        /* Hide default cursor on interactive elements */
        a[href], button, [role="button"], input, select, textarea, [onclick], .cursor-pointer {
            cursor: none !important;
        }
    </style>
</head>
<body class="bg-slate-50" x-data="sidebarState()">
    <?php if (isLoggedIn()): ?>
    
    <!-- Mobile Overlay -->
    <div x-show="mobileOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         class="fixed inset-0 bg-black/50 z-40 lg:hidden"
         x-cloak>
    </div>
    
    <!-- Sidebar -->
    <aside 
        class="fixed top-0 left-0 z-50 h-full bg-gradient-to-br from-indigo-600 to-purple-700 sidebar-transition"
        :class="[
            collapsed ? 'w-20' : 'w-64',
            mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
        ]"
    >
        <!-- Logo -->
        <div class="flex items-center justify-between h-16 px-4 border-b border-white/10">
            <div class="flex items-center gap-3" x-show="!collapsed">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <i data-lucide="mosque" class="w-5 h-5 text-white"></i>
                </div>
                <span class="text-white font-semibold text-lg">Bismillah</span>
            </div>
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center" x-show="collapsed">
                <i data-lucide="mosque" class="w-5 h-5 text-white"></i>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="p-3 space-y-1 overflow-y-auto h-[calc(100vh-4rem)]">
            <a href="<?= base_url('dashboard') ?>" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:bg-white/10 hover:text-white transition-all duration-200 <?= activeMenu('dashboard') ? 'menu-active' : '' ?>">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span x-show="!collapsed"><?= trans('dashboard') ?></span>
            </a>
            
            <?php if (can('accounts.view')): ?>
            <a href="<?= base_url('accounts') ?>" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:bg-white/10 hover:text-white transition-all duration-200 <?= activeMenu('accounts') ? 'menu-active' : '' ?>">
                <i data-lucide="wallet" class="w-5 h-5"></i>
                <span x-show="!collapsed"><?= trans('accounts') ?></span>
            </a>
            <?php endif; ?>
            
            <?php if (can('transactions.view')): ?>
            <a href="<?= base_url('transactions') ?>" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:bg-white/10 hover:text-white transition-all duration-200 <?= activeMenu('transactions') ? 'menu-active' : '' ?>">
                <i data-lucide="wallet" class="w-5 h-5"></i>
                <span x-show="!collapsed"><?= trans('transactions') ?></span>
            </a>
            <?php endif; ?>
            
            <?php if (can('categories.view')): ?>
            <a href="<?= base_url('categories') ?>" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:bg-white/10 hover:text-white transition-all duration-200 <?= activeMenu('categories') ? 'menu-active' : '' ?>">
                <i data-lucide="tags" class="w-5 h-5"></i>
                <span x-show="!collapsed"><?= trans('categories') ?></span>
            </a>
            <?php endif; ?>
            
            <?php if (can('budgets.view')): ?>
            <a href="<?= base_url('budgets') ?>" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:bg-white/10 hover:text-white transition-all duration-200 <?= activeMenu('budgets') ? 'menu-active' : '' ?>">
                <i data-lucide="calculator" class="w-5 h-5"></i>
                <span x-show="!collapsed"><?= trans('budgets') ?></span>
            </a>
            <?php endif; ?>
            
            <?php if (can('goals.view')): ?>
            <a href="<?= base_url('goals') ?>" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:bg-white/10 hover:text-white transition-all duration-200 <?= activeMenu('goals') ? 'menu-active' : '' ?>">
                <i data-lucide="target" class="w-5 h-5"></i>
                <span x-show="!collapsed"><?= trans('goals') ?></span>
            </a>
            <?php endif; ?>
            
            <?php if (can('reports.view')): ?>
            <a href="<?= base_url('reports') ?>" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:bg-white/10 hover:text-white transition-all duration-200 <?= activeMenu('reports') ? 'menu-active' : '' ?>">
                <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                <span x-show="!collapsed"><?= trans('reports') ?></span>
            </a>
            <a href="<?= base_url('calendar') ?>" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:bg-white/10 hover:text-white transition-all duration-200 <?= activeMenu('calendar') ? 'menu-active' : '' ?>">
                <i data-lucide="calendar-days" class="w-5 h-5"></i>
                <span x-show="!collapsed"><?= trans('calendar') ?></span>
            </a>
            <?php endif; ?>
            
            <?php if (can('users.view')): ?>
            <a href="<?= base_url('users') ?>" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:bg-white/10 hover:text-white transition-all duration-200 <?= activeMenu('users') ? 'menu-active' : '' ?>">
                <i data-lucide="users" class="w-5 h-5"></i>
                <span x-show="!collapsed"><?= trans('users') ?></span>
            </a>
            <?php endif; ?>
            
            <?php if (isSuperAdmin()): ?>
            <a href="<?= base_url('mosques') ?>" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:bg-white/10 hover:text-white transition-all duration-200 <?= activeMenu('mosques') ? 'menu-active' : '' ?>">
                <i data-lucide="building" class="w-5 h-5"></i>
                <span x-show="!collapsed"><?= getLang() === 'id' ? 'Masjid' : 'Mosques' ?></span>
            </a>
            <?php endif; ?>
        </nav>
        
        <!-- Collapse Button (Desktop) -->
        <button @click="toggleCollapse()" 
                class="hidden lg:flex absolute -right-3 top-20 w-6 h-6 bg-white rounded-full shadow-lg items-center justify-center text-indigo-600 hover:bg-indigo-50 transition-colors">
            <i data-lucide="chevron-left" class="w-4 h-4 transition-transform" :class="collapsed ? 'rotate-180' : ''"></i>
        </button>
    </aside>
    
    <!-- Main Content -->
    <div class="page-content min-h-screen" 
         :class="collapsed ? 'lg:ml-20' : 'lg:ml-64'">
        
        <!-- Top Navbar -->
        <header class="sticky top-0 z-30 bg-white shadow-sm rounded-b-2xl mx-2 mt-2">
            <div class="flex items-center justify-between px-4 py-3">
                <!-- Left: Menu Button + Title -->
                <div class="flex items-center gap-3">
                    <button @click="toggleMobile()" class="lg:hidden p-2 rounded-xl hover:bg-slate-100 transition-colors">
                        <i data-lucide="menu" class="w-6 h-6 text-slate-600"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-slate-800"><?= $pageTitle ?? 'Dashboard' ?></h1>
                </div>
                
                <!-- Right: Actions -->
                <div class="flex items-center gap-2">
                    <!-- Super Admin: Mosque Switcher -->
                    <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'superadmin'): ?>
                        <?php if (isset($_SESSION['selected_mosque_id']) && $mosque = getMosque()): ?>
                            <div x-data="{ mosqueOpen: false }" class="relative">
                                <button @click="mosqueOpen = !mosqueOpen" class="flex items-center gap-2 px-3 py-1.5 bg-violet-100 rounded-lg text-violet-700 text-sm hover:bg-violet-200 transition-colors">
                                    <i data-lucide="building" class="w-4 h-4"></i>
                                    <span class="hidden md:inline"><?= sanitize($mosque['name']) ?></span>
                                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                </button>
                                <div x-show="mosqueOpen" @click.outside="mosqueOpen = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-cloak
                                     class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-slate-100 py-1">
                                    <div class="px-4 py-2 border-b border-slate-100">
                                        <p class="text-xs text-slate-500">Masjid Aktif</p>
                                        <p class="text-sm font-medium text-slate-700"><?= sanitize($mosque['name']) ?></p>
                                    </div>
                                    <a href="<?= base_url('dashboard/clearMosque') ?>" class="flex items-center gap-3 px-4 py-2 text-slate-700 hover:bg-slate-50">
                                        <i data-lucide="refresh-cw" class="w-4 h-4"></i> Ganti Masjid
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <!-- Date -->
                    <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-slate-100 rounded-lg text-slate-600 text-sm">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                        <span><?= date('d M Y') ?></span>
                    </div>
                    
                    <!-- Language -->
                    <div x-data="{ langOpen: false }" class="relative">
                        <button @click="langOpen = !langOpen" class="p-2 rounded-xl hover:bg-slate-100 transition-colors">
                            <i data-lucide="globe" class="w-5 h-5 text-slate-600"></i>
                        </button>
                        <div x-show="langOpen" @click.outside="langOpen = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-cloak
                             class="absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-lg border border-slate-100 py-1">
                            <a href="<?= base_url('auth/setlang?lang=id') ?>" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-slate-50">
                                <span class="text-lg">🇮🇩</span> Indonesia
                            </a>
                            <a href="<?= base_url('auth/setlang?lang=en') ?>" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-slate-50">
                                <span class="text-lg">🇺🇸</span> English
                            </a>
                        </div>
                    </div>
                    
                    <!-- User Menu -->
                    <div x-data="{ userOpen: false }" class="relative">
                        <button @click="userOpen = !userOpen" class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-slate-100 transition-colors">
                            <div class="w-9 h-9 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-medium">
                                <?= strtoupper(substr($user['name'] ?? 'U', 0, 2)) ?>
                            </div>
                            <span class="hidden sm:block text-sm font-medium text-slate-700"><?= $user['name'] ?? 'User' ?></span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400"></i>
                        </button>
                        <div x-show="userOpen" @click.outside="userOpen = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-cloak
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1">
                            <a href="<?= base_url('auth/profile') ?>" class="flex items-center gap-3 px-4 py-2 text-slate-700 hover:bg-slate-50">
                                <i data-lucide="user" class="w-4 h-4"></i> <?= trans('profile') ?>
                            </a>
                            <a href="<?= base_url('auth/settings') ?>" class="flex items-center gap-3 px-4 py-2 text-slate-700 hover:bg-slate-50">
                                <i data-lucide="settings" class="w-4 h-4"></i> <?= trans('settings') ?>
                            </a>
                            <hr class="my-1 border-slate-100">
                            <a href="<?= base_url('auth/logout') ?>" class="flex items-center gap-3 px-4 py-2 text-red-600 hover:bg-red-50">
                                <i data-lucide="log-out" class="w-4 h-4"></i> <?= trans('logout') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Page Content -->
        <main class="p-4 lg:p-6">
            <!-- Flash Messages (Hidden - will be shown as toast) -->
            <?php if (flash('success')): ?>
            <div data-flash-success="<?= flash('success') ?>" style="display:none;"></div>
            <?php endif; ?>
            
            <?php if (flash('error')): ?>
            <div data-flash-error="<?= flash('error') ?>" style="display:none;"></div>
            <?php endif; ?>
            
            <?php if (flash('warning')): ?>
            <div data-flash-warning="<?= flash('warning') ?>" style="display:none;"></div>
            <?php endif; ?>
            
            <?php if (flash('info')): ?>
            <div data-flash-info="<?= flash('info') ?>" style="display:none;"></div>
            <?php endif; ?>
    <?php endif; ?>
    
    <script>
        function sidebarState() {
            return {
                collapsed: false,
                mobileOpen: false,
                init() {
                    // Check screen size on load
                    if (window.innerWidth < 1024) {
                        this.collapsed = true;
                    }
                },
                toggleCollapse() {
                    this.collapsed = !this.collapsed;
                },
                toggleMobile() {
                    this.mobileOpen = !this.mobileOpen;
                }
            }
        }
        
        // Custom Cursor JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            // Create cursor element
            const cursor = document.createElement('div');
            cursor.className = 'custom-cursor';
            document.body.appendChild(cursor);
            
            let mouseX = 0, mouseY = 0;
            let cursorX = 0, cursorY = 0;
            
            // Update mouse position
            document.addEventListener('mousemove', function(e) {
                mouseX = e.clientX;
                mouseY = e.clientY;
            });
            
            // Smooth cursor movement
            function animateCursor() {
                const dx = mouseX - cursorX;
                const dy = mouseY - cursorY;
                
                cursorX += dx * 0.15;
                cursorY += dy * 0.15;
                
                cursor.style.left = cursorX + 'px';
                cursor.style.top = cursorY + 'px';
                
                requestAnimationFrame(animateCursor);
            }
            animateCursor();
            
            // Handle hover states
            const hoverElements = document.querySelectorAll('a[href], button, [role="button"], input, select, textarea, [onclick], .cursor-pointer, .card-hover');
            
            hoverElements.forEach(function(el) {
                el.addEventListener('mouseenter', function() {
                    cursor.classList.add('hover');
                });
                el.addEventListener('mouseleave', function() {
                    cursor.classList.remove('hover');
                });
            });
            
            // Re-check hover elements on DOM changes (for dynamic content)
            const observer = new MutationObserver(function(mutations) {
                const newElements = document.querySelectorAll('a[href], button, [role="button"], input, select, textarea, [onclick], .cursor-pointer, .card-hover');
                newElements.forEach(function(el) {
                    if (!el.classList.contains('cursor-processed')) {
                        el.classList.add('cursor-processed');
                        el.addEventListener('mouseenter', function() {
                            cursor.classList.add('hover');
                        });
                        el.addEventListener('mouseleave', function() {
                            cursor.classList.remove('hover');
                        });
                    }
                });
            });
            
            observer.observe(document.body, { childList: true, subtree: true });
        });
        
        // Initialize Lucide icons
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
    
    <!-- Notifications System -->
    <script src="<?= base_url('public/js/notifications.js') ?>?v=<?= time() ?>"></script>
    
    <!-- Page Transition Loader -->
    <script src="<?= base_url('public/js/page-loader.js') ?>?v=<?= time() ?>"></script>
