<?php
/**
 * Test Superadmin Login
 * Script untuk test dan debug login superadmin
 */

// Load config
require_once 'config/config.php';
require_once 'app/Core/Database.php';
require_once 'helpers/auth.php';

echo "<h1>Test Superadmin Login</h1>";
echo "<hr>";

// Test 1: Check database connection
echo "<h2>1. Database Connection</h2>";
try {
    $db = Database::getInstance();
    echo "✅ Database connected successfully<br>";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "<br>";
    die();
}

// Test 2: Check if superadmin exists
echo "<h2>2. Check Superadmin User</h2>";
$email = 'superadmin@bismillah.com';
$user = $db->first("SELECT * FROM users WHERE email = ?", [$email]);

if ($user) {
    echo "✅ Superadmin user found<br>";
    echo "<pre>";
    echo "ID: " . $user['id'] . "\n";
    echo "Name: " . $user['name'] . "\n";
    echo "Email: " . $user['email'] . "\n";
    echo "Role: " . $user['role'] . "\n";
    echo "Is Active: " . ($user['is_active'] ? 'Yes' : 'No') . "\n";
    echo "Mosque ID: " . ($user['mosque_id'] ?? 'NULL') . "\n";
    echo "</pre>";
} else {
    echo "❌ Superadmin user NOT found<br>";
    echo "<p style='color: red;'>Please run: database/create_superadmin.sql</p>";
    die();
}

// Test 3: Check password
echo "<h2>3. Password Verification</h2>";
$password = 'password123';
$passwordHash = $user['password'];

echo "Testing password: <strong>$password</strong><br>";
echo "Stored hash: <code>$passwordHash</code><br>";

if (password_verify($password, $passwordHash)) {
    echo "✅ Password is correct<br>";
} else {
    echo "❌ Password verification failed<br>";
    echo "<p style='color: red;'>Password hash might be incorrect. Generating new hash...</p>";
    
    $newHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
    echo "<p>New hash: <code>$newHash</code></p>";
    echo "<p>Run this SQL to update:</p>";
    echo "<pre>UPDATE users SET password = '$newHash' WHERE email = '$email';</pre>";
}

// Test 4: Check role enum
echo "<h2>4. Check Role Enum</h2>";
$result = $db->query("SHOW COLUMNS FROM users LIKE 'role'");
if ($result) {
    echo "✅ Role column found<br>";
    echo "<pre>";
    print_r($result[0]);
    echo "</pre>";
    
    // Check if 'superadmin' is in enum
    $type = $result[0]['Type'];
    if (strpos($type, 'superadmin') !== false) {
        echo "✅ 'superadmin' role exists in enum<br>";
    } else {
        echo "❌ 'superadmin' role NOT in enum<br>";
        echo "<p style='color: red;'>Please run: database/migration_superadmin_rename.sql</p>";
    }
}

// Test 5: Simulate login
echo "<h2>5. Simulate Login Process</h2>";
if ($user && password_verify($password, $passwordHash)) {
    echo "✅ Login would succeed<br>";
    echo "<p>You can now login with:</p>";
    echo "<ul>";
    echo "<li>Email: <strong>$email</strong></li>";
    echo "<li>Password: <strong>$password</strong></li>";
    echo "</ul>";
} else {
    echo "❌ Login would fail<br>";
}

echo "<hr>";
echo "<h2>Summary</h2>";
echo "<p>If all tests pass, you should be able to login at: <a href='" . base_url('auth/login') . "'>" . base_url('auth/login') . "</a></p>";
?>
