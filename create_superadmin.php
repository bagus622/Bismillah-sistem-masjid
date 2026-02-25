<?php
/**
 * Create Superadmin Account
 * Run this file once to create superadmin account
 */

// Load dependencies
require_once 'config/config.php';
require_once 'app/Core/Database.php';
require_once 'helpers/auth.php';

echo "<!DOCTYPE html>";
echo "<html><head><title>Create Superadmin</title>";
echo "<style>body{font-family:Arial;padding:20px;max-width:800px;margin:0 auto;}";
echo ".success{color:green;}.error{color:red;}.info{color:blue;}";
echo "pre{background:#f5f5f5;padding:10px;border-radius:5px;}</style>";
echo "</head><body>";

echo "<h1>🔧 Create Superadmin Account</h1>";
echo "<hr>";

try {
    // Connect to database
    $db = Database::getInstance();
    echo "<p class='success'>✅ Database connected</p>";
    
    // Check if superadmin already exists
    $existing = $db->first("SELECT * FROM users WHERE email = ?", ['superadmin@bismillah.com']);
    
    if ($existing) {
        echo "<p class='info'>ℹ️ Superadmin already exists. Deleting old account...</p>";
        $db->execute("DELETE FROM users WHERE email = ?", ['superadmin@bismillah.com']);
        echo "<p class='success'>✅ Old account deleted</p>";
    }
    
    // Create new superadmin
    echo "<p class='info'>ℹ️ Creating new superadmin account...</p>";
    
    $password = 'password123';
    $hashedPassword = hashPassword($password);
    
    $data = [
        'mosque_id' => null,
        'name' => 'superadmin',
        'email' => 'superadmin@bismillah.com',
        'password' => $hashedPassword,
        'phone' => null,
        'role' => 'superadmin',
        'is_active' => 1,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    $userId = $db->insert('users', $data);
    
    if ($userId) {
        echo "<p class='success'>✅ Superadmin created successfully!</p>";
        echo "<hr>";
        echo "<h2>📋 Account Details</h2>";
        echo "<table border='1' cellpadding='10' style='border-collapse:collapse;'>";
        echo "<tr><td><strong>User ID</strong></td><td>$userId</td></tr>";
        echo "<tr><td><strong>Name</strong></td><td>superadmin</td></tr>";
        echo "<tr><td><strong>Email</strong></td><td><strong>superadmin@bismillah.com</strong></td></tr>";
        echo "<tr><td><strong>Password</strong></td><td><strong>password123</strong></td></tr>";
        echo "<tr><td><strong>Role</strong></td><td>superadmin</td></tr>";
        echo "<tr><td><strong>Mosque ID</strong></td><td>NULL (can access all mosques)</td></tr>";
        echo "</table>";
        
        echo "<hr>";
        echo "<h2>🔐 Login Now</h2>";
        echo "<p>You can now login with the credentials above:</p>";
        echo "<p><a href='" . base_url('auth/login') . "' style='display:inline-block;background:#4f46e5;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;'>Go to Login Page</a></p>";
        
        echo "<hr>";
        echo "<h2>✅ Verification</h2>";
        $verify = $db->first("SELECT * FROM users WHERE id = ?", [$userId]);
        echo "<pre>";
        print_r($verify);
        echo "</pre>";
        
        // Test password
        echo "<h2>🔍 Password Test</h2>";
        if (password_verify($password, $verify['password'])) {
            echo "<p class='success'>✅ Password verification: PASSED</p>";
        } else {
            echo "<p class='error'>❌ Password verification: FAILED</p>";
        }
        
    } else {
        echo "<p class='error'>❌ Failed to create superadmin</p>";
        echo "<p>Please check database permissions and try again.</p>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<p><small>⚠️ Delete this file (create_superadmin.php) after creating the account for security reasons.</small></p>";
echo "</body></html>";
?>
