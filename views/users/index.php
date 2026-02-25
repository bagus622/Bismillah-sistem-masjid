<?php 
// Helper function to get role badge styling
function getRoleBadge($role) {
    $badges = [
        'super_admin' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'label' => 'Super Admin'],
        'admin' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'label' => 'Admin'],
        'accountant' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'label' => 'Akuntan'],
        'treasurer' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'border' => 'border-orange-200', 'label' => 'Bendahara'],
        'member' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'border' => 'border-gray-200', 'label' => 'Member']
    ];
    return $badges[$role] ?? $badges['member'];
}

// Get initials from name
function getInitials($name) {
    $words = explode(' ', trim($name));
    if (count($words) >= 2) {
        return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
    }
    return strtoupper(substr($name, 0, 2));
}
?>

<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">User Management</h1>
            <p class="mt-1 text-sm text-gray-500">Manage system users and roles</p>
        </div>
        <?php if (can('users.create')): ?>
        <a href="<?= base_url('users/create') ?>" class="inline-flex items-center px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md hover:scale-105 transition-all duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <?= trans('add_user') ?>
        </a>
        <?php endif; ?>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <!-- Total Users -->
    <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total Users</p>
                <p class="text-3xl font-bold text-gray-900"><?= $totalUsers ?></p>
            </div>
            <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Active Users -->
    <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Active Users</p>
                <p class="text-3xl font-bold text-emerald-600"><?= $activeUsers ?></p>
            </div>
            <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Admin Users -->
    <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Admin Users</p>
                <p class="text-3xl font-bold text-blue-600"><?= $adminUsers ?></p>
            </div>
            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filter -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
    <div class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Search by name or email..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="w-48">
            <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
            <select id="roleFilter" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                <option value="">All Roles</option>
                <option value="super_admin">Super Admin</option>
                <option value="admin">Admin</option>
                <option value="accountant">Akuntan</option>
                <option value="treasurer">Bendahara</option>
                <option value="member">Member</option>
            </select>
        </div>
        <div class="w-40">
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select id="statusFilter" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                <option value="">All</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <button onclick="resetFilters()" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-colors">
            Reset
        </button>
    </div>
</div>

<!-- Users Table -->
<?php if (!empty($users)): ?>
<div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="w-full" id="usersTable">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"><?= trans('role') ?></th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"><?= trans('status') ?></th>
                    <?php if (can('users.edit') || can('users.delete')): ?>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"><?= trans('action') ?></th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($users as $index => $user): ?>
                <?php $roleBadge = getRoleBadge($user['role']); ?>
                <tr class="user-row hover:bg-gray-50 transition-colors duration-150 <?= $index % 2 === 0 ? 'bg-white' : 'bg-gray-50/30' ?>" 
                    data-name="<?= strtolower($user['name']) ?>" 
                    data-email="<?= strtolower($user['email']) ?>"
                    data-role="<?= $user['role'] ?>"
                    data-status="<?= $user['is_active'] ?>">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <!-- Avatar -->
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-blue-500 flex items-center justify-center text-white font-bold text-sm">
                                <?= getInitials($user['name']) ?>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900"><?= sanitize($user['name']) ?></p>
                                <p class="text-sm text-gray-500"><?= $user['email'] ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?= $roleBadge['bg'] ?> <?= $roleBadge['text'] ?> border <?= $roleBadge['border'] ?>">
                            <?= $roleBadge['label'] ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <?php if ($user['is_active']): ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span>
                            Active
                        </span>
                        <?php else: ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></span>
                            Inactive
                        </span>
                        <?php endif; ?>
                    </td>
                    <?php if (can('users.edit') || can('users.delete')): ?>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <?php if (can('users.edit')): ?>
                            <a href="<?= base_url('users/edit/' . $user['id']) ?>" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-150" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <a href="<?= base_url('users/change-password/' . $user['id']) ?>" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors duration-150" title="Reset Password">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                            </a>
                            <?php endif; ?>
                            <?php if (can('users.delete') && $user['id'] != getUserId()): ?>
                            <button onclick="confirmDelete(<?= $user['id'] ?>, '<?= sanitize($user['name']) ?>')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                            <?php endif; ?>
                        </div>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php else: ?>
<!-- Empty State -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 mb-8">
    <div class="text-center">
        <div class="w-32 h-32 mx-auto mb-6">
            <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Background Circle -->
                <circle cx="100" cy="100" r="90" fill="#F3F4F6"/>
                <!-- User Icon -->
                <circle cx="100" cy="70" r="35" fill="#D1D5DB" stroke="#9CA3AF" stroke-width="3"/>
                <path d="M50 160c0-27.614 22.386-50 50-50s50 22.386 50 50" fill="#D1D5DB" stroke="#9CA3AF" stroke-width="3"/>
                <!-- Plus Icon -->
                <circle cx="160" cy="150" r="25" fill="#10B981" stroke="white" stroke-width="3"/>
                <line x1="160" y1="138" x2="160" y2="162" stroke="white" stroke-width="3" stroke-linecap="round"/>
                <line x1="148" y1="150" x2="172" y2="150" stroke="white" stroke-width="3" stroke-linecap="round"/>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">No users found</h3>
        <p class="text-gray-500 mb-6 max-w-sm mx-auto">Add your first system user to get started</p>
        <?php if (can('users.create')): ?>
        <a href="<?= base_url('users/create') ?>" class="inline-flex items-center px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Create User
        </a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm hidden items-center justify-center z-50 transition-opacity duration-200 opacity-0">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform scale-95 transition-transform duration-200">
        <div class="text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Delete User?</h3>
            <p class="text-gray-500 mb-6">Are you sure you want to delete <span id="userName" class="font-semibold text-gray-900"></span>? This action cannot be undone.</p>
            <div class="flex gap-3 justify-center">
                <button onclick="closeDeleteModal()" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-colors duration-200">
                    <?= trans('cancel') ?>
                </button>
                <a id="deleteLink" href="#" class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                    <?= trans('delete') ?>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="text-center py-6">
    <p class="text-sm text-gray-400">© 2026 Bismillah — Financial Management System</p>
</div>

<!-- JavaScript -->
<script>
    // Search functionality
    document.getElementById('searchInput')?.addEventListener('input', filterUsers);
    document.getElementById('roleFilter')?.addEventListener('change', filterUsers);
    document.getElementById('statusFilter')?.addEventListener('change', filterUsers);
    
    function filterUsers() {
        const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
        const roleFilter = document.getElementById('roleFilter')?.value || '';
        const statusFilter = document.getElementById('statusFilter')?.value || '';
        
        const rows = document.querySelectorAll('.user-row');
        
        rows.forEach(row => {
            const name = row.getAttribute('data-name') || '';
            const email = row.getAttribute('data-email') || '';
            const role = row.getAttribute('data-role') || '';
            const status = row.getAttribute('data-status') || '';
            
            const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
            const matchesRole = !roleFilter || role === roleFilter;
            const matchesStatus = !statusFilter || status === statusFilter;
            
            row.style.display = (matchesSearch && matchesRole && matchesStatus) ? '' : 'none';
        });
    }
    
    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('roleFilter').value = '';
        document.getElementById('statusFilter').value = '';
        filterUsers();
    }
    
    // Delete modal
    function confirmDelete(id, name) {
        const modal = document.getElementById('deleteModal');
        const deleteLink = document.getElementById('deleteLink');
        const userName = document.getElementById('userName');
        
        deleteLink.href = '<?= base_url('users/delete/') ?>' + id;
        userName.textContent = name;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('.transform').classList.remove('scale-95');
            modal.querySelector('.transform').classList.add('scale-100');
        }, 10);
    }
    
    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        
        modal.classList.add('opacity-0');
        modal.querySelector('.transform').classList.remove('scale-100');
        modal.querySelector('.transform').classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }
    
    // Close modal on backdrop click
    document.getElementById('deleteModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            document.getElementById('searchInput')?.focus();
        }
    });
</script>

<style>
    /* Smooth transitions */
    .user-row {
        transition: background-color 0.15s ease;
    }
    
    /* Input focus */
    input:focus, select:focus {
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
</style>
