<?php
/**
 * Bismallah - Mosque Management Information System
 * Public Entry Point
 */

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Get the project root directory
$projectRoot = dirname(__DIR__);

// Define base paths
define('BASE_PATH', $projectRoot);
define('CONFIG_PATH', BASE_PATH . '/config');
define('CONTROLLER_PATH', BASE_PATH . '/controllers');
define('MODEL_PATH', BASE_PATH . '/app/models');
define('VIEW_PATH', BASE_PATH . '/views');
define('HELPER_PATH', BASE_PATH . '/helpers');
define('PUBLIC_PATH', __DIR__);
define('UPLOAD_PATH', __DIR__ . '/uploads');

// Load configuration
require_once CONFIG_PATH . '/config.php';

// Autoloader
spl_autoload_register(function ($class) {
    // Controllers
    $controllerFile = CONTROLLER_PATH . '/' . $class . '.php';
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        return;
    }
    
    // Core classes
    $coreFile = BASE_PATH . '/app/Core/' . $class . '.php';
    if (file_exists($coreFile)) {
        require_once $coreFile;
        return;
    }
    
    // Helpers
    $helperFile = HELPER_PATH . '/' . $class . '.php';
    if (file_exists($helperFile)) {
        require_once $helperFile;
        return;
    }
});

// Load helpers
require_once HELPER_PATH . '/functions.php';
require_once HELPER_PATH . '/auth.php';

// Get URL from query string
$url = isset($_GET['url']) ? $_GET['url'] : 'home';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Route mapping: URL segment -> Controller name
$routeMap = [
    'home' => 'HomeController',
    'accounts' => 'AccountController',
    'transactions' => 'TransactionController',
    'categories' => 'CategoryController',
    'budgets' => 'BudgetController',
    'goals' => 'GoalController',
    'reports' => 'ReportController',
    'calendar' => 'CalendarController',
    'users' => 'UserController',
    'auth' => 'AuthController',
    'dashboard' => 'DashboardController',
    'mosques' => 'MosqueController'
];

// Get controller name from URL or use default
$urlSegment = isset($url[0]) && !empty($url[0]) ? $url[0] : 'home';

// Check if there's a mapping, otherwise use default naming
if (isset($routeMap[$urlSegment])) {
    $controllerName = $routeMap[$urlSegment];
} else {
    $controllerName = ucfirst($urlSegment) . 'Controller';
}

$method = isset($url[1]) && !empty($url[1]) ? $url[1] : 'index';
$params = array_slice($url, 2);

// Debug: Check controller path
$controllerFile = CONTROLLER_PATH . '/' . $controllerName . '.php';
// error_log("Looking for controller: " . $controllerFile);

// Check if controller exists
if (!file_exists($controllerFile)) {
    // Controller not found - show 404
    http_response_code(404);
    die("Controller not found: " . $controllerName . " at " . $controllerFile);
}

// Public controllers that don't require login
$publicControllers = ['HomeController', 'AuthController'];

// Check if user is logged in (except for public controllers)
if (!isLoggedIn() && !in_array($controllerName, $publicControllers)) {
    redirect('auth/login');
}

// Initialize controller
$controller = new $controllerName();

// Call method with parameters
if (method_exists($controller, $method)) {
    call_user_func_array([$controller, $method], $params);
} else {
    // Default to index
    $controller->index();
}
