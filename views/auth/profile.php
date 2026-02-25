<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900"><?= trans('profile') ?></h1>
            <p class="mt-1 text-sm text-gray-500">Manage your account settings and preferences</p>
        </div>
    </div>
</div>

<!-- Profile Content -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Profile Info Card -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Profile Information
            </h3>
        </div>
        
        <form action="<?= base_url('auth/updateProfile') ?>" method="POST" class="p-6 space-y-5">
            <!-- Avatar Display -->
            <div class="flex items-center gap-4 pb-5 border-b border-gray-100">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-emerald-400 to-blue-500 flex items-center justify-center text-white text-xl font-bold">
                    <?= strtoupper(substr($user['name'], 0, 2)) ?>
                </div>
                <div>
                    <p class="font-semibold text-gray-900"><?= sanitize($user['name']) ?></p>
                    <p class="text-sm text-gray-500"><?= $user['email'] ?></p>
                </div>
            </div>
            
            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                <input type="text" name="name" value="<?= sanitize($user['name']) ?>" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
            </div>
            
            <!-- Email (readonly) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" value="<?= sanitize($user['email']) ?>" readonly class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-500 cursor-not-allowed">
                <p class="text-xs text-gray-500 mt-1">Email cannot be changed</p>
            </div>
            
            <!-- Phone -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                <input type="text" name="phone" value="<?= sanitize($user['phone'] ?? '') ?>" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
            </div>
            
            <!-- Role (readonly) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <input type="text" value="<?= getRoleName($user['role']) ?>" readonly class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-500 cursor-not-allowed">
            </div>
            
            <!-- Submit -->
            <div class="pt-2">
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
    
    <!-- Change Password Card -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
                Change Password
            </h3>
        </div>
        
        <form action="<?= base_url('auth/changePassword') ?>" method="POST" class="p-6 space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                <input type="password" name="current_password" required class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                <input type="password" name="new_password" required class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                <input type="password" name="confirm_password" required class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>
            <div class="pt-2">
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Footer -->
<div class="text-center py-6">
    <p class="text-sm text-gray-400">© 2026 Bismillah — Financial Management System</p>
</div>
