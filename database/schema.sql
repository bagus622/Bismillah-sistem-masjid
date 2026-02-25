-- Database Schema for Bismallah - Mosque Management Information System
-- Compatible with MySQL/MariaDB

-- =====================================================
-- MOSQUE/ORGANIZATION TABLE (Multi-masjid support)
-- =====================================================
CREATE TABLE IF NOT EXISTS mosques (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    address TEXT,
    phone VARCHAR(50),
    email VARCHAR(100),
    logo VARCHAR(255),
    description TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- USERS TABLE (with role support)
-- =====================================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mosque_id INT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    photo VARCHAR(255),
    role ENUM('superadmin', 'admin', 'accountant', 'treasurer', 'member') DEFAULT 'member',
    is_active TINYINT(1) DEFAULT 1,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (mosque_id) REFERENCES mosques(id) ON DELETE SET NULL,
    INDEX idx_mosque (mosque_id),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- PERMISSIONS TABLE (Spatie-like)
-- =====================================================
CREATE TABLE IF NOT EXISTS permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    group_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default permissions
INSERT IGNORE INTO permissions (name, description, group_name) VALUES
-- Account permissions
('accounts.view', 'View account list and balances', 'accounts'),
('accounts.create', 'Create new account', 'accounts'),
('accounts.edit', 'Edit account details', 'accounts'),
('accounts.delete', 'Delete account', 'accounts'),
-- Transaction permissions
('transactions.view', 'View transactions', 'transactions'),
('transactions.create', 'Create new transaction', 'transactions'),
('transactions.edit', 'Edit transaction', 'transactions'),
('transactions.delete', 'Delete transaction', 'transactions'),
-- Category permissions
('categories.view', 'View categories', 'categories'),
('categories.create', 'Create category', 'categories'),
('categories.edit', 'Edit category', 'categories'),
('categories.delete', 'Delete category', 'categories'),
-- Budget permissions
('budgets.view', 'View budgets', 'budgets'),
('budgets.create', 'Create budget', 'budgets'),
('budgets.edit', 'Edit budget', 'budgets'),
('budgets.delete', 'Delete budget', 'budgets'),
-- Goals permissions
('goals.view', 'View goals/targets', 'goals'),
('goals.create', 'Create goal', 'goals'),
('goals.edit', 'Edit goal', 'goals'),
('goals.delete', 'Delete goal', 'goals'),
('goals.deposit', 'Make deposit to goal', 'goals'),
-- Reports permissions
('reports.view', 'View reports', 'reports'),
('reports.export', 'Export reports', 'reports'),
-- User permissions
('users.view', 'View users', 'users'),
('users.create', 'Create user', 'users'),
('users.edit', 'Edit user', 'users'),
('users.delete', 'Delete user', 'users'),
('users.roles', 'Manage user roles', 'users'),
-- Settings permissions
('settings.view', 'View settings', 'settings'),
('settings.edit', 'Edit settings', 'settings');

-- =====================================================
-- ROLE PERMISSIONS TABLE (Many-to-Many)
-- =====================================================
CREATE TABLE IF NOT EXISTS role_permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role ENUM('superadmin', 'admin', 'accountant', 'treasurer', 'member') NOT NULL,
    permission_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
    UNIQUE KEY unique_role_permission (role, permission_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default role-permissions assignment
-- Super Admin: All permissions
INSERT IGNORE INTO role_permissions (role, permission_id)
SELECT 'superadmin', id FROM permissions;

-- Admin: All except user management
INSERT IGNORE INTO role_permissions (role, permission_id)
SELECT 'admin', id FROM permissions WHERE name NOT IN ('users.create', 'users.edit', 'users.delete', 'users.roles');

-- Accountant: View all, create/edit transactions, categories, budgets, reports
INSERT IGNORE INTO role_permissions (role, permission_id)
SELECT 'accountant', id FROM permissions WHERE name IN (
    'accounts.view', 'transactions.view', 'transactions.create', 'transactions.edit',
    'categories.view', 'categories.create', 'categories.edit',
    'budgets.view', 'budgets.create', 'budgets.edit',
    'goals.view', 'goals.deposit',
    'reports.view', 'reports.export'
);

-- Treasurer: Manage transactions and goals deposit
INSERT IGNORE INTO role_permissions (role, permission_id)
SELECT 'treasurer', id FROM permissions WHERE name IN (
    'accounts.view', 'transactions.view', 'transactions.create', 'transactions.edit',
    'categories.view',
    'goals.view', 'goals.deposit',
    'reports.view'
);

-- Member: View only
INSERT IGNORE INTO role_permissions (role, permission_id)
SELECT 'member', id FROM permissions WHERE name IN (
    'accounts.view', 'transactions.view', 'categories.view',
    'budgets.view', 'goals.view', 'reports.view'
);

-- =====================================================
-- ACCOUNTS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mosque_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    type ENUM('cash', 'bank', 'e_wallet') NOT NULL DEFAULT 'cash',
    account_number VARCHAR(100),
    description TEXT,
    initial_balance DECIMAL(15,2) DEFAULT 0.00,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (mosque_id) REFERENCES mosques(id) ON DELETE CASCADE,
    INDEX idx_mosque (mosque_id),
    INDEX idx_type (type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- CATEGORIES TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mosque_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    type ENUM('income', 'expense') NOT NULL,
    parent_id INT NULL,
    icon VARCHAR(50),
    color VARCHAR(7),
    description TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (mosque_id) REFERENCES mosques(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_mosque (mosque_id),
    INDEX idx_type (type),
    INDEX idx_parent (parent_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default categories
-- Income categories
INSERT IGNORE INTO categories (mosque_id, name, type, icon, color) VALUES
(1, 'Zakat', 'income', 'fa-hand-holding-heart', '#4CAF50'),
(1, 'Infaq', 'income', 'fa-donate', '#2196F3'),
(1, 'Sedekah', 'income', 'fa-hands-helping', '#9C27B0'),
(1, 'Donasi', 'income', 'fa-gift', '#FF9800');

-- Expense categories
INSERT IGNORE INTO categories (mosque_id, name, type, icon, color) VALUES
(1, 'Operasional', 'expense', 'fa-cogs', '#F44336'),
(1, 'Perlengkapan', 'expense', 'fa-box', '#E91E63'),
(1, 'Pengajian', 'expense', 'fa-book-quran', '#00BCD4'),
(1, 'Santunan', 'expense', 'fa-hands-holding-child', '#795548'),
(1, 'Pembangunan', 'expense', 'fa-mosque', '#607D8B');

-- =====================================================
-- TRANSACTIONS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mosque_id INT NOT NULL,
    account_id INT NOT NULL,
    category_id INT NOT NULL,
    type ENUM('income', 'expense') NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    description TEXT,
    transaction_date DATE NOT NULL,
    reference_number VARCHAR(100),
    is_upcoming TINYINT(1) DEFAULT 0,
    is_recurring TINYINT(1) DEFAULT 0,
    recurring_interval ENUM('daily', 'weekly', 'monthly', 'yearly') NULL,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (mosque_id) REFERENCES mosques(id) ON DELETE CASCADE,
    FOREIGN KEY (account_id) REFERENCES accounts(id) ON DELETE RESTRICT,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_mosque (mosque_id),
    INDEX idx_account (account_id),
    INDEX idx_category (category_id),
    INDEX idx_type (type),
    INDEX idx_date (transaction_date),
    INDEX idx_upcoming (is_upcoming)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TRANSACTION ATTACHMENTS TABLE (Photo proofs)
-- =====================================================
CREATE TABLE IF NOT EXISTS transaction_attachments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_id INT NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(100),
    file_size INT,
    description TEXT,
    uploaded_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_transaction (transaction_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- BUDGETS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS budgets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mosque_id INT NOT NULL,
    category_id INT NOT NULL,
    year INT NOT NULL,
    month TINYINT,
    amount DECIMAL(15,2) NOT NULL,
    description TEXT,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (mosque_id) REFERENCES mosques(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT,
    UNIQUE KEY unique_budget (mosque_id, category_id, year, month),
    INDEX idx_mosque (mosque_id),
    INDEX idx_year (year)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- GOALS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS goals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mosque_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    target_amount DECIMAL(15,2) NOT NULL,
    current_amount DECIMAL(15,2) DEFAULT 0.00,
    description TEXT,
    target_date DATE,
    is_completed TINYINT(1) DEFAULT 0,
    completed_at TIMESTAMP NULL,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (mosque_id) REFERENCES mosques(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_mosque (mosque_id),
    INDEX idx_completed (is_completed)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- GOAL DEPOSITS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS goal_deposits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    goal_id INT NOT NULL,
    account_id INT NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    transaction_id INT,
    notes TEXT,
    deposited_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (goal_id) REFERENCES goals(id) ON DELETE CASCADE,
    FOREIGN KEY (account_id) REFERENCES accounts(id) ON DELETE RESTRICT,
    FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE SET NULL,
    FOREIGN KEY (deposited_by) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_goal (goal_id),
    INDEX idx_transaction (transaction_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ACTIVITY LOG TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mosque_id INT,
    user_id INT NOT NULL,
    action VARCHAR(100) NOT NULL,
    description TEXT,
    entity_type VARCHAR(50),
    entity_id INT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (mosque_id) REFERENCES mosques(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_mosque (mosque_id),
    INDEX idx_user (user_id),
    INDEX idx_entity (entity_type, entity_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- SESSIONS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(128) PRIMARY KEY,
    mosque_id INT,
    user_id INT NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    payload TEXT,
    last_activity INT,
    FOREIGN KEY (mosque_id) REFERENCES mosques(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_mosque (mosque_id),
    INDEX idx_user (user_id),
    INDEX idx_last_activity (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- PASSWORD RESETS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_token (token)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- SETTINGS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mosque_id INT NOT NULL,
    `key` VARCHAR(255) NOT NULL,
    value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (mosque_id) REFERENCES mosques(id) ON DELETE CASCADE,
    UNIQUE KEY unique_setting (mosque_id, `key`),
    INDEX idx_mosque (mosque_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default settings for mosque
INSERT IGNORE INTO settings (mosque_id, `key`, value) VALUES
(1, 'language', 'id'),
(1, 'currency', 'IDR'),
(1, 'currency_symbol', 'Rp'),
(1, 'date_format', 'd/m/Y'),
(1, 'timezone', 'Asia/Jakarta'),
(1, 'fiscal_year_start', '1');

-- =====================================================
-- SAMPLE DATA - Default Mosque
-- =====================================================
INSERT IGNORE INTO mosques (id, name, address, phone, email, description) VALUES
(1, 'Masjid Al-Falah', 'Jl. Masjid No. 123, Jakarta Selatan', '021-1234567', 'info@masjid-alfalah.org', 'Masjid Al-Falah - Tempat Ibadah dan Sosial Masyarakat');

-- Sample users (password: password123)
INSERT IGNORE INTO users (mosque_id, name, email, password, role) VALUES
(1, 'Admin Masjid', 'admin@masjid.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(1, 'Bendahara', 'bendahara@masjid.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'treasurer'),
(1, 'Akuntan', 'akuntan@masjid.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'accountant');

-- Sample accounts
INSERT IGNORE INTO accounts (mosque_id, name, type, account_number, description, initial_balance) VALUES
(1, 'Kas Kecil', 'cash', '-', 'Uang tunai di masjid', 5000000),
(1, 'Kas Besar', 'cash', '-', 'Simpanan uang tunai', 10000000),
(1, 'Rekening Bank BCA', 'bank', '1234567890', 'Rekening operasional masjid', 50000000);

-- Sample transactions
INSERT IGNORE INTO transactions (mosque_id, account_id, category_id, type, amount, description, transaction_date, created_by) VALUES
(1, 1, 1, 'income', 25000000, 'Zakat Fitrah Ramadan', '2026-01-15', 1),
(1, 1, 2, 'income', 5000000, 'Infaq harian bulan Januari', '2026-01-20', 1),
(1, 1, 1, 'income', 15000000, 'Zakat Mal', '2026-02-01', 1),
(1, 3, 5, 'expense', 8000000, 'Pembelian peralatan cleaning', '2026-01-25', 2),
(1, 1, 4, 'expense', 3000000, 'Keberangkatan pengajian umum', '2026-02-10', 2);

-- Sample budgets
INSERT IGNORE INTO budgets (mosque_id, category_id, year, month, amount, created_by) VALUES
(1, 5, 2026, 1, 10000000, 1),
(1, 6, 2026, 1, 5000000, 1),
(1, 7, 2026, 1, 15000000, 1),
(1, 8, 2026, 1, 10000000, 1);

-- Sample goals
INSERT IGNORE INTO goals (mosque_id, name, target_amount, current_amount, description, target_date, created_by) VALUES
(1, 'Pembangunan Gerbang Masjid', 500000000, 125000000, 'Renovation dan pembangunan gerbang Masjid Al-Falah', '2027-12-31', 1);
