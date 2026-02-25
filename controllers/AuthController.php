<?php
/**
 * Auth Controller
 * Authentication and user management
 */

class AuthController extends Controller {
    
    // Login page
    public function login() {
        if (isLoggedIn()) {
            $this->redirect('dashboard');
        }
        
        $pageTitle = trans('login');
        require VIEW_PATH . '/auth/login.php';
    }
    
    // Process login
    public function doLogin() {
        if (!isPost()) {
            $this->redirect('auth/login');
        }
        
        $email = $this->input('email');
        $password = $this->input('password');
        
        // Validate
        if (empty($email) || empty($password)) {
            setError(trans('login_failed'));
            $this->redirect('auth/login');
        }
        
        // Check user
        $user = $this->db->first("SELECT * FROM users WHERE email = ? AND is_active = 1", [$email]);
        
        if (!$user || !verifyPassword($password, $user['password'])) {
            setError(trans('login_failed'));
            $this->redirect('auth/login');
        }
        
        // Login success
        login($user['id'], $user);
        
        // Update last login
        $this->db->execute("UPDATE users SET last_login = NOW() WHERE id = ?", [$user['id']]);
        
        setSuccess(trans('login_success'));
        $this->redirect('dashboard');
    }
    
    // Logout
    public function logout() {
        logout();
        setSuccess(trans('logout_success'));
        $this->redirect('auth/login');
    }
    
    // Register
    public function register() {
        if (isLoggedIn()) {
            $this->redirect('dashboard');
        }
        
        $pageTitle = trans('register');
        require VIEW_PATH . '/auth/register.php';
    }
    
    // Process register
    public function doRegister() {
        // Set header for JSON response
        header('Content-Type: application/json');
        
        if (!isPost()) {
            jsonError('Invalid request method');
        }
        
        try {
            $mosqueName = trim($this->input('mosque_name'));
            $mosqueAddress = trim($this->input('mosque_address'));
            $mosquePhone = trim($this->input('mosque_phone'));
            $mosqueEmail = trim($this->input('mosque_email'));
            
            $adminName = trim($this->input('admin_name'));
            $adminEmail = trim($this->input('admin_email'));
            $adminPhone = trim($this->input('admin_phone'));
            $password = $this->input('password');
            $confirmPassword = $this->input('confirm_password');
            
            // Validate
            if (empty($mosqueName)) {
                jsonError('Nama masjid wajib diisi');
            }
            
            if (empty($adminName)) {
                jsonError('Nama admin wajib diisi');
            }
            
            if (empty($adminEmail)) {
                jsonError('Email admin wajib diisi');
            }
            
            if (empty($password)) {
                jsonError('Password wajib diisi');
            }
            
            if (!filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                jsonError('Format email tidak valid');
            }
            
            if (strlen($password) < 6) {
                jsonError('Password minimal 6 karakter');
            }
            
            if ($password !== $confirmPassword) {
                jsonError('Password tidak cocok');
            }
            
            // Check if email exists
            $exists = $this->db->first("SELECT id FROM users WHERE email = ?", [$adminEmail]);
            
            if ($exists) {
                jsonError('Email sudah terdaftar');
            }
            
            // Start transaction
            $this->db->beginTransaction();
            
            // Create mosque
            $mosqueData = [
                'name' => $mosqueName,
                'address' => $mosqueAddress ?: null,
                'phone' => $mosquePhone ?: null,
                'email' => $mosqueEmail ?: null,
                'is_active' => 1,
                'created_at' => now()
            ];
            
            $mosqueId = $this->db->insert('mosques', $mosqueData);
            
            if (!$mosqueId) {
                throw new Exception('Gagal membuat data masjid');
            }
            
            // Create admin user
            $userData = [
                'mosque_id' => $mosqueId,
                'name' => $adminName,
                'email' => $adminEmail,
                'phone' => $adminPhone ?: null,
                'password' => hashPassword($password),
                'role' => 'admin',
                'is_active' => 1,
                'created_at' => now()
            ];
            
            $userId = $this->db->insert('users', $userData);
            
            if (!$userId) {
                throw new Exception('Gagal membuat user admin');
            }
            
            // Create default categories for this mosque
            $this->createDefaultCategories($mosqueId);
            
            // Create default accounts for this mosque
            $this->createDefaultAccounts($mosqueId);
            
            // Create default settings for this mosque
            $this->createDefaultSettings($mosqueId);
            
            // Commit transaction
            $this->db->commit();
            
            // Auto login
            $user = $this->db->first("SELECT * FROM users WHERE id = ?", [$userId]);
            login($userId, $user);
            
            jsonSuccess('Registrasi berhasil! Selamat datang di Bismillah', [
                'redirect' => base_url('dashboard')
            ]);
            
        } catch (Exception $e) {
            if ($this->db->getPdo()->inTransaction()) {
                $this->db->rollback();
            }
            
            // Log error for debugging
            error_log('Registration error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            
            jsonError('Registrasi gagal: ' . $e->getMessage());
        }
    }
    
    // Create default categories for new mosque
    private function createDefaultCategories($mosqueId) {
        try {
            // Income categories
            $incomeCategories = [
                ['name' => 'Zakat', 'icon' => 'hand-holding-heart', 'color' => '#4CAF50'],
                ['name' => 'Infaq', 'icon' => 'donate', 'color' => '#2196F3'],
                ['name' => 'Sedekah', 'icon' => 'hands-helping', 'color' => '#9C27B0'],
                ['name' => 'Donasi', 'icon' => 'gift', 'color' => '#FF9800'],
            ];
            
            foreach ($incomeCategories as $cat) {
                $this->db->insert('categories', [
                    'mosque_id' => $mosqueId,
                    'name' => $cat['name'],
                    'type' => 'income',
                    'icon' => $cat['icon'],
                    'color' => $cat['color'],
                    'is_active' => 1
                ]);
            }
            
            // Expense categories
            $expenseCategories = [
                ['name' => 'Operasional', 'icon' => 'cogs', 'color' => '#F44336'],
                ['name' => 'Perlengkapan', 'icon' => 'box', 'color' => '#E91E63'],
                ['name' => 'Pengajian', 'icon' => 'book-quran', 'color' => '#00BCD4'],
                ['name' => 'Santunan', 'icon' => 'hands-holding-child', 'color' => '#795548'],
                ['name' => 'Pembangunan', 'icon' => 'mosque', 'color' => '#607D8B'],
            ];
            
            foreach ($expenseCategories as $cat) {
                $this->db->insert('categories', [
                    'mosque_id' => $mosqueId,
                    'name' => $cat['name'],
                    'type' => 'expense',
                    'icon' => $cat['icon'],
                    'color' => $cat['color'],
                    'is_active' => 1
                ]);
            }
        } catch (Exception $e) {
            throw new Exception('Gagal membuat kategori default: ' . $e->getMessage());
        }
    }
    
    // Create default accounts for new mosque
    private function createDefaultAccounts($mosqueId) {
        try {
            $accounts = [
                ['name' => 'Kas Kecil', 'type' => 'cash', 'description' => 'Uang tunai di masjid'],
                ['name' => 'Kas Besar', 'type' => 'cash', 'description' => 'Simpanan uang tunai'],
            ];
            
            foreach ($accounts as $acc) {
                $this->db->insert('accounts', [
                    'mosque_id' => $mosqueId,
                    'name' => $acc['name'],
                    'type' => $acc['type'],
                    'description' => $acc['description'],
                    'initial_balance' => 0,
                    'is_active' => 1
                ]);
            }
        } catch (Exception $e) {
            throw new Exception('Gagal membuat akun default: ' . $e->getMessage());
        }
    }
    
    // Create default settings for new mosque
    private function createDefaultSettings($mosqueId) {
        try {
            $settings = [
                ['key' => 'language', 'value' => 'id'],
                ['key' => 'currency', 'value' => 'IDR'],
                ['key' => 'currency_symbol', 'value' => 'Rp'],
                ['key' => 'date_format', 'value' => 'd/m/Y'],
                ['key' => 'timezone', 'value' => 'Asia/Jakarta'],
                ['key' => 'fiscal_year_start', 'value' => '1'],
            ];
            
            foreach ($settings as $setting) {
                // Use backticks for 'key' column because it's a reserved keyword in MySQL
                $sql = "INSERT INTO settings (mosque_id, `key`, value) VALUES (?, ?, ?)";
                $result = $this->db->execute($sql, [
                    $mosqueId,
                    $setting['key'],
                    $setting['value']
                ]);
                
                if (!$result) {
                    throw new Exception('Gagal insert setting: ' . $setting['key']);
                }
            }
        } catch (Exception $e) {
            throw new Exception('Gagal membuat settings default: ' . $e->getMessage());
        }
    }
    
    // Set language
    public function setlang($lang = null) {
        // Get language from parameter or POST
        if (!$lang) {
            $lang = $this->input('lang', 'id');
        }
        
        // Validate language
        if (!in_array($lang, AVAILABLE_LANGUAGES)) {
            $lang = 'id';
        }
        
        setLang($lang);
        
        // Get referer or default to dashboard
        $referer = $_SERVER['HTTP_REFERER'] ?? base_url('dashboard');
        
        // Redirect back
        header('Location: ' . $referer);
        exit;
    }
    
    // Profile
    public function profile() {
        $this->requirePermission('users.view');
        
        $user = getUser();
        $pageTitle = trans('profile');
        
        $this->view('auth/profile', compact('user', 'pageTitle'));
    }
    
    // Settings
    public function settings() {
        $this->requirePermission('settings.view');
        
        $mosqueId = $this->getMosqueId();
        
        // Get mosque settings
        $mosque = $this->db->first("SELECT * FROM mosques WHERE id = ?", [$mosqueId]);
        
        $pageTitle = trans('settings');
        $this->view('auth/settings', compact('pageTitle', 'mosque'));
    }
    
    // Update profile
    public function updateProfile() {
        if (!isPost()) {
            $this->redirect('auth/profile');
        }
        
        $this->requirePermission('users.edit');
        
        $userId = $this->getUserId();
        $name = $this->input('name');
        $phone = $this->input('phone');
        
        $data = [
            'name' => $name,
            'phone' => $phone,
            'updated_at' => now()
        ];
        
        $this->db->update('users', $data, 'id = ?', [$userId]);
        
        // Update session
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['phone'] = $phone;
        
        setSuccess('Profile updated successfully');
        $this->redirect('auth/profile');
    }
    
    // Change password
    public function changePassword() {
        if (!isPost()) {
            $this->redirect('auth/profile');
        }
        
        $currentPassword = $this->input('current_password');
        $newPassword = $this->input('new_password');
        $confirmPassword = $this->input('confirm_password');
        
        // Validate
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            setError('All fields are required');
            $this->redirect('auth/profile');
        }
        
        if ($newPassword !== $confirmPassword) {
            setError('New passwords do not match');
            $this->redirect('auth/profile');
        }
        
        // Check current password
        $user = $this->db->first("SELECT password FROM users WHERE id = ?", [$this->getUserId()]);
        
        if (!verifyPassword($currentPassword, $user['password'])) {
            setError('Current password is incorrect');
            $this->redirect('auth/profile');
        }
        
        // Update password
        $this->db->update('users', ['password' => hashPassword($newPassword)], 'id = ?', [$this->getUserId()]);
        
        setSuccess('Password changed successfully');
        $this->redirect('auth/profile');
    }
}
