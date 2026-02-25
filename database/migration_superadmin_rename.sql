-- Migration: Rename super_admin to superadmin
-- Run this SQL if you already have existing database with super_admin role

-- Step 1: Alter users table to add new enum value
ALTER TABLE users MODIFY COLUMN role ENUM('superadmin', 'super_admin', 'admin', 'accountant', 'treasurer', 'member') DEFAULT 'member';

-- Step 2: Update existing super_admin users to superadmin
UPDATE users SET role = 'superadmin' WHERE role = 'super_admin';

-- Step 3: Alter role_permissions table to add new enum value
ALTER TABLE role_permissions MODIFY COLUMN role ENUM('superadmin', 'super_admin', 'admin', 'accountant', 'treasurer', 'member') NOT NULL;

-- Step 4: Update existing super_admin permissions to superadmin
UPDATE role_permissions SET role = 'superadmin' WHERE role = 'super_admin';

-- Step 5: Remove old enum value from users table
ALTER TABLE users MODIFY COLUMN role ENUM('superadmin', 'admin', 'accountant', 'treasurer', 'member') DEFAULT 'member';

-- Step 6: Remove old enum value from role_permissions table
ALTER TABLE role_permissions MODIFY COLUMN role ENUM('superadmin', 'admin', 'accountant', 'treasurer', 'member') NOT NULL;

-- Verification queries
SELECT 'Users with superadmin role:' as info;
SELECT id, name, email, role FROM users WHERE role = 'superadmin';

SELECT 'Role permissions for superadmin:' as info;
SELECT COUNT(*) as permission_count FROM role_permissions WHERE role = 'superadmin';
