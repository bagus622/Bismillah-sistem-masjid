-- =====================================================
-- CREATE SUPERADMIN ACCOUNT
-- =====================================================
-- Email: superadmin@bismillah.com
-- Password: password123
-- =====================================================

-- Step 1: Check if superadmin already exists
SELECT 'Checking existing superadmin...' as status;
SELECT id, name, email, role FROM users WHERE email = 'superadmin@bismillah.com';

-- Step 2: Delete if exists (optional - uncomment if you want to recreate)
-- DELETE FROM users WHERE email = 'superadmin@bismillah.com';

-- Step 3: Insert superadmin user
INSERT INTO users (
    mosque_id,
    name,
    email,
    password,
    phone,
    role,
    is_active,
    created_at,
    updated_at
) VALUES (
    NULL,
    'superadmin',
    'superadmin@bismillah.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    NULL,
    'superadmin',
    1,
    NOW(),
    NOW()
);

-- Step 4: Verify creation
SELECT 'Superadmin created successfully!' as status;
SELECT id, name, email, role, is_active, created_at FROM users WHERE email = 'superadmin@bismillah.com';

-- =====================================================
-- LOGIN CREDENTIALS
-- =====================================================
-- Email: superadmin@bismillah.com
-- Password: password123
-- =====================================================
