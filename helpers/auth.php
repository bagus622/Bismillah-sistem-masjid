<?php
/**
 * Authentication Helper
 * Functions for user authentication and authorization
 */

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Get current user
function getUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    if (!isset($_SESSION['user'])) {
        $db = new Database();
        $user = $db->query("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);
        $_SESSION['user'] = $user ? $user[0] : null;
    }
    
    return $_SESSION['user'];
}

// Get current user ID
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Get current user role
function getUserRole() {
    return $_SESSION['role'] ?? null;
}

// Login user
function login($userId, $user) {
    $_SESSION['user_id'] = $userId;
    $_SESSION['user'] = $user;
    $_SESSION['role'] = $user['role'];
    $_SESSION['login_time'] = time();
    
    // Super Admin doesn't have a fixed mosque_id
    // They can switch between mosques
    if ($user['role'] !== 'superadmin') {
        $_SESSION['mosque_id'] = $user['mosque_id'];
        
        // Load mosque data for non-super admin users
        if ($user['mosque_id']) {
            $db = Database::getInstance();
            $mosque = $db->first("SELECT * FROM mosques WHERE id = ?", [$user['mosque_id']]);
            if ($mosque) {
                $_SESSION['mosque'] = $mosque;
            }
        }
    } else {
        // Super Admin: Don't set mosque_id in session yet
        // They will select a mosque from dashboard
        $_SESSION['mosque_id'] = null;
        $_SESSION['selected_mosque_id'] = null;
    }
    
    // Load user permissions
    loadPermissions($user['role']);
}

// Load user permissions
function loadPermissions($role) {
    $db = new Database();
    
    // Get permissions for role
    $permissions = $db->query("
        SELECT p.name 
        FROM permissions p
        JOIN role_permissions rp ON p.id = rp.permission_id
        WHERE rp.role = ?
    ", [$role]);
    
    $permissionNames = array_column($permissions, 'name');
    $_SESSION['permissions'] = $permissionNames;
}

// Logout user
function logout() {
    // Destroy session
    session_unset();
    session_destroy();
    
    // Start new session for flash messages
    session_start();
}

// Check if user has permission
function can($permission) {
    if (!isLoggedIn()) {
        return false;
    }
    
    $role = getUserRole();
    
    // Super admin has all permissions
    if ($role === 'superadmin') {
        return true;
    }
    
    // Check permission
    $permissions = $_SESSION['permissions'] ?? [];
    
    return in_array($permission, $permissions);
}

// Require permission
function requirePermission($permission) {
    if (!can($permission)) {
        setError('Anda tidak memiliki izin untuk mengakses halaman ini.');
        redirect('dashboard');
    }
}

// Hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => HASH_COST]);
}

// Verify password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Generate remember token
function generateRememberToken() {
    return bin2hex(random_bytes(32));
}

// Set remember me cookie
function setRememberMe($userId) {
    $token = generateRememberToken();
    $expiry = time() + (86400 * 30); // 30 days
    
    // Store token in database
    $db = new Database();
    $db->query("UPDATE users SET remember_token = ?, token_expiry = ? WHERE id = ?", 
        [$token, date('Y-m-d H:i:s', $expiry), $userId]);
    
    // Set cookie
    setcookie('remember_token', $token, $expiry, '/');
    
    return $token;
}

// Check remember me
function checkRememberMe() {
    if (!isset($_COOKIE['remember_token'])) {
        return false;
    }
    
    $token = $_COOKIE['remember_token'];
    
    $db = new Database();
    $user = $db->query("SELECT * FROM users WHERE remember_token = ? AND token_expiry > NOW()", [$token]);
    
    if ($user) {
        login($user[0]['id'], $user[0]);
        return true;
    }
    
    return false;
}

// Is admin
function isAdmin() {
    $role = getUserRole();
    return in_array($role, ['superadmin', 'admin']);
}

// Is treasurer
function isTreasurer() {
    $role = getUserRole();
    return in_array($role, ['superadmin', 'admin', 'treasurer']);
}

// Is accountant
function isAccountant() {
    $role = getUserRole();
    return in_array($role, ['superadmin', 'admin', 'accountant', 'treasurer']);
}

// Is super admin
function isSuperAdmin() {
    $role = getUserRole();
    return $role === 'superadmin';
}
