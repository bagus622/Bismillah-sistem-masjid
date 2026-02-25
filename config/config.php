<?php
/**
 * Bismallah - Configuration File
 * Sistem Informasi Manajemen Masjid
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'bismillah');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Application Configuration
define('APP_NAME', 'Bismallah');
define('APP_TITLE', 'Sistem Informasi Manajemen Masjid');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/project-basmalahCopy');

// Session Configuration
define('SESSION_LIFETIME', 86400); // 24 hours
define('SESSION_NAME', 'BISMALLAH_SESSION');

// Security
define('HASH_COST', 10);
define('TOKEN_LENGTH', 32);

// Pagination
define('PER_PAGE', 15);
define('PER_PAGE_LARGE', 50);

// File Upload
define('UPLOAD_MAX_SIZE', 5242880); // 5MB
define('ALLOWED_EXTENSIONS', 'jpg,jpeg,png,gif,pdf,doc,docx');

// Date & Time
define('TIMEZONE', 'Asia/Jakarta');
define('DATE_FORMAT', 'd/m/Y');
define('DATETIME_FORMAT', 'd/m/Y H:i:s');
define('TIME_FORMAT', 'H:i');

// Currency
define('CURRENCY', 'IDR');
define('CURRENCY_SYMBOL', 'Rp');
define('CURRENCY_POSITION', 'before');

// Multi-language
define('DEFAULT_LANGUAGE', 'id');
define('AVAILABLE_LANGUAGES', ['id', 'en', 'es', 'tr']);

// Languages
$LANG = [
    'id' => 'Indonesia',
    'en' => 'English',
    'es' => 'Español',
    'tr' => 'Türkçe'
];

// Role definitions
$ROLES = [
    'superadmin' => 'Super Admin',
    'admin' => 'Admin',
    'accountant' => 'Akuntan',
    'treasurer' => 'Bendahara',
    'member' => 'Anggota'
];

// Set timezone
date_default_timezone_set(TIMEZONE);

// Development mode
define('DEBUG', true);
