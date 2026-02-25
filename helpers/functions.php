<?php
/**
 * Helper Functions
 * Utility functions for Bismallah
 */

// Format currency
function formatCurrency($amount, $symbol = true) {
    $amount = floatval($amount);
    $formatted = number_format($amount, 0, ',', '.');
    
    if (!$symbol) {
        return $formatted;
    }
    
    return CURRENCY_SYMBOL . ' ' . $formatted;
}

// Format date
function formatDate($date, $format = null) {
    if (empty($date)) {
        return '-';
    }
    
    if ($date instanceof DateTime) {
        return $date->format($format ?? DATE_FORMAT);
    }
    
    try {
        $date = new DateTime($date);
        return $date->format($format ?? DATE_FORMAT);
    } catch (Exception $e) {
        return $date;
    }
}

// Format datetime
function formatDateTime($datetime, $format = null) {
    if (empty($datetime)) {
        return '-';
    }
    
    if ($datetime instanceof DateTime) {
        return $datetime->format($format ?? DATETIME_FORMAT);
    }
    
    try {
        $datetime = new DateTime($datetime);
        return $datetime->format($format ?? DATETIME_FORMAT);
    } catch (Exception $e) {
        return $datetime;
    }
}

// Get current date
function today() {
    return date('Y-m-d');
}

// Get current datetime
function now() {
    return date('Y-m-d H:i:s');
}

// Redirect
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

// Get base URL
function base_url($path = '') {
    $url = APP_URL;
    if (!empty($path)) {
        $url .= '/' . ltrim($path, '/');
    }
    return $url;
}

// Get asset URL
function asset($path = '') {
    return base_url('public/assets/' . ltrim($path, '/'));
}

// Get current URL
function current_url() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

// Generate random string
function generateRandomString($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

// CSRF Token
function csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = generateRandomString();
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF
function verifyCsrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Flash messages
function flash($key, $value = null) {
    if ($value === null) {
        $flash = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $flash;
    }
    
    $_SESSION['flash'][$key] = $value;
    return null;
}

function setSuccess($message) {
    flash('success', $message);
}

function setError($message) {
    flash('error', $message);
}

function setWarning($message) {
    flash('warning', $message);
}

function setInfo($message) {
    flash('info', $message);
}

// Check request type
function isAjax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

function isPost() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function isGet() {
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

// Get input
function input($key, $default = '') {
    $value = $_POST[$key] ?? $_GET[$key] ?? $default;
    return is_array($value) ? $value : trim($value);
}

// Get old input
function old($key, $default = '') {
    return $_SESSION['old'][$key] ?? $default;
}

// Set old input
function setOld($data) {
    $_SESSION['old'] = $data;
}

// Clear old input
function clearOld() {
    unset($_SESSION['old']);
}

// Sanitize
function sanitize($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Generate slug
function slug($string) {
    $string = preg_replace('/[^a-zA-Z0-9\s-]/', '', $string);
    $string = preg_replace('/[\s-]+/', '-', $string);
    return strtolower(trim($string, '-'));
}

// Get file extension
function getExtension($filename) {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

// Generate unique filename
function generateFilename($originalName) {
    $ext = getExtension($originalName);
    return time() . '_' . generateRandomString(8) . '.' . $ext;
}

// Upload file
function uploadFile($file, $directory, $allowedExtensions = []) {
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'message' => 'Invalid file upload'];
    }
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'File upload error'];
    }
    
    if ($file['size'] > UPLOAD_MAX_SIZE) {
        return ['success' => false, 'message' => 'File size exceeds maximum'];
    }
    
    $ext = getExtension($file['name']);
    
    if (!empty($allowedExtensions) && !in_array($ext, $allowedExtensions)) {
        return ['success' => false, 'message' => 'File type not allowed'];
    }
    
    $filename = generateFilename($file['name']);
    $targetPath = $directory . '/' . $filename;
    
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => true, 'filename' => $filename, 'path' => $targetPath];
    }
    
    return ['success' => false, 'message' => 'Failed to move uploaded file'];
}

// Delete file
function deleteFile($path) {
    if (file_exists($path) && is_file($path)) {
        return unlink($path);
    }
    return false;
}

// Calculate percentage
function percentage($value, $total, $decimals = 2) {
    if ($total == 0) {
        return 0;
    }
    return round(($value / $total) * 100, $decimals);
}

// Get month name
function getMonthName($month, $locale = 'id') {
    $months = [
        'id' => ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        'en' => ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        'es' => ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        'tr' => ['', 'Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık']
    ];
    
    return $months[$locale][$month] ?? $month;
}

// Get day name
function getDayName($day, $locale = 'id') {
    $days = [
        'id' => ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
        'en' => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        'es' => ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        'tr' => ['Pazar', 'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi']
    ];
    
    return $days[$locale][$day] ?? $day;
}

// JSON response
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function jsonSuccess($message = 'Success', $data = []) {
    jsonResponse([
        'success' => true,
        'message' => $message,
        'data' => $data
    ]);
}

function jsonError($message = 'Error', $errors = [], $statusCode = 400) {
    jsonResponse([
        'success' => false,
        'message' => $message,
        'errors' => $errors
    ], $statusCode);
}

// Validation
function validate($data, $rules) {
    $errors = [];
    
    foreach ($rules as $field => $rule) {
        $value = $data[$field] ?? null;
        $ruleList = explode('|', $rule);
        
        foreach ($ruleList as $r) {
            if ($r === 'required' && empty($value)) {
                $errors[$field][] = ucfirst($field) . ' is required';
            } elseif ($r === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$field][] = ucfirst($field) . ' must be a valid email';
            } elseif ($r === 'numeric' && !is_numeric($value)) {
                $errors[$field][] = ucfirst($field) . ' must be numeric';
            } elseif (strpos($r, 'min:') === 0) {
                $min = (int) substr($r, 4);
                if (strlen($value) < $min) {
                    $errors[$field][] = ucfirst($field) . ' must be at least ' . $min . ' characters';
                }
            }
        }
    }
    
    return empty($errors) ? true : $errors;
}

// Get current language
function getLang() {
    return $_SESSION['lang'] ?? DEFAULT_LANGUAGE;
}

// Set language
function setLang($lang) {
    if (in_array($lang, AVAILABLE_LANGUAGES)) {
        $_SESSION['lang'] = $lang;
    }
}

// Translation helper
function trans($key, $replace = []) {
    global $LANG;
    
    $lang = getLang();
    $translations = [];
    
    // Load translations
    $langFile = BASE_PATH . '/lang/' . $lang . '.php';
    if (file_exists($langFile)) {
        $translations = require $langFile;
    }
    
    $message = $translations[$key] ?? $key;
    
    // Replace placeholders
    foreach ($replace as $placeholder => $value) {
        $message = str_replace(':' . $placeholder, $value, $message);
    }
    
    return $message;
}

// Get user role name
function getRoleName($role) {
    global $ROLES;
    return $ROLES[$role] ?? $role;
}

// Check permission
function hasPermission($permission) {
    if (!isLoggedIn()) {
        return false;
    }
    
    $user = getUser();
    
    // Super admin has all permissions
    if ($user['role'] === 'superadmin') {
        return true;
    }
    
    // Check permission in session or database
    $permissions = $_SESSION['permissions'] ?? [];
    
    return in_array($permission, $permissions);
}

// Get current mosque
function getMosque() {
    if (!isLoggedIn()) {
        return null;
    }
    
    $user = getUser();
    
    // Super Admin: Get selected mosque
    if ($user['role'] === 'superadmin') {
        $mosqueId = $_SESSION['selected_mosque_id'] ?? null;
        
        if (!$mosqueId) {
            return null;
        }
        
        // Check if mosque data already in session
        if (isset($_SESSION['mosque']) && $_SESSION['mosque']['id'] == $mosqueId) {
            return $_SESSION['mosque'];
        }
        
        // Load mosque data from database
        $db = Database::getInstance();
        $mosque = $db->first("SELECT * FROM mosques WHERE id = ?", [$mosqueId]);
        
        if ($mosque) {
            $_SESSION['mosque'] = $mosque;
            return $mosque;
        }
        
        return null;
    }
    
    // Non-Super Admin: Get their assigned mosque
    // Check if mosque data already in session
    if (isset($_SESSION['mosque']) && !empty($_SESSION['mosque'])) {
        return $_SESSION['mosque'];
    }
    
    // Get mosque_id from session
    $mosqueId = $_SESSION['mosque_id'] ?? null;
    
    if (!$mosqueId) {
        return null;
    }
    
    // Load mosque data from database
    $db = Database::getInstance();
    $mosque = $db->first("SELECT * FROM mosques WHERE id = ?", [$mosqueId]);
    
    if ($mosque) {
        $_SESSION['mosque'] = $mosque;
        return $mosque;
    }
    
    return null;
}

// Active menu
function activeMenu($url) {
    $currentUrl = $_GET['url'] ?? 'dashboard';
    // Get first segment of URL for matching
    $currentSegment = explode('/', $currentUrl)[0];
    return $currentSegment === $url ? 'active' : '';
}
