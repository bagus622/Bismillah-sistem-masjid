<?php
/**
 * Controller Base Class
 */

class Controller {
    // Database instance
    protected $db;
    
    // Current user
    protected $user;
    
    // Current mosque
    protected $mosque;
    
    // View data
    protected $data = [];
    
    // Constructor
    public function __construct() {
        $this->db = Database::getInstance();
        $this->user = getUser();
        $this->mosque = getMosque();
    }
    
    // Render view
    protected function view($view, $data = [], $layout = 'default') {
        // Extract data to variables
        extract(array_merge($this->data, $data));
        
        // Build view path
        $viewPath = VIEW_PATH . '/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            die("View not found: {$view}");
        }
        
        // Use different layout based on parameter
        if ($layout === 'landing') {
            // Landing page layout (no header/footer, uses landing.php)
            require VIEW_PATH . '/layouts/landing.php';
        } else {
            // Default dashboard layout
            // Load header
            require VIEW_PATH . '/layouts/header.php';
            
            // Load view
            require $viewPath;
            
            // Load footer
            require VIEW_PATH . '/layouts/footer.php';
        }
    }
    
    // Render JSON response
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    // Success response
    protected function success($message = 'Success', $data = []) {
        $this->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
    }
    
    // Error response
    protected function error($message = 'Error', $errors = [], $statusCode = 400) {
        $this->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }
    
    // Redirect
    protected function redirect($url) {
        header('Location: ' . base_url($url));
        exit;
    }
    
    // Set flash message
    protected function setSuccess($message) {
        setSuccess($message);
    }
    
    // Set error message
    protected function setError($message) {
        setError($message);
    }
    
    // Get input
    protected function input($key, $default = '') {
        return input($key, $default);
    }
    
    // Check permission
    protected function can($permission) {
        return can($permission);
    }
    
    // Require permission
    protected function requirePermission($permission) {
        requirePermission($permission);
    }
    
    // Upload file
    protected function upload($file, $directory, $allowedExtensions = []) {
        return uploadFile($file, $directory, $allowedExtensions);
    }
    
    // Paginate
    protected function paginate($sql, $params = [], $page = 1, $perPage = PER_PAGE) {
        return $this->db->paginate($sql, $params, $page, $perPage);
    }
    
    // Get current mosque ID
    protected function getMosqueId() {
        // Super Admin can switch between mosques
        if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'superadmin') {
            // Check if super admin has selected a mosque
            if (isset($_SESSION['selected_mosque_id']) && !empty($_SESSION['selected_mosque_id'])) {
                return $_SESSION['selected_mosque_id'];
            }
            // If no mosque selected, return null (super admin needs to select)
            return null;
        }
        
        // For other roles, get from session first
        if (isset($_SESSION['mosque_id']) && !empty($_SESSION['mosque_id'])) {
            return $_SESSION['mosque_id'];
        }
        
        // Get from user data
        if (isset($this->user['mosque_id']) && !empty($this->user['mosque_id'])) {
            return $this->user['mosque_id'];
        }
        
        // Get from mosque array
        if (isset($this->mosque['id']) && !empty($this->mosque['id'])) {
            return $this->mosque['id'];
        }
        
        // If still not found and not super admin, redirect to login
        if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'superadmin') {
            $this->redirect('auth/logout');
        }
        
        return null;
    }
    
    // Get current user ID
    protected function getUserId() {
        return $this->user['id'] ?? $_SESSION['user_id'];
    }
}
