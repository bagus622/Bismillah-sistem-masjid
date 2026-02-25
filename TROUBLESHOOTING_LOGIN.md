# Troubleshooting: Tidak Bisa Login Superadmin

## Langkah-langkah Troubleshooting

### Langkah 1: Jalankan Test Script

Buka browser dan akses:
```
http://localhost/project-basmalahCopy/test_superadmin_login.php
```

Script ini akan mengecek:
- ✅ Koneksi database
- ✅ Apakah user superadmin ada
- ✅ Apakah password benar
- ✅ Apakah role enum sudah benar
- ✅ Simulasi login

### Langkah 2: Buat Superadmin (Jika Belum Ada)

#### Via phpMyAdmin:
1. Buka `http://localhost/phpmyadmin`
2. Pilih database Anda (misalnya `project-basmalahcopy`)
3. Klik tab "SQL"
4. Copy-paste isi file `database/create_superadmin.sql`
5. Klik "Go"

#### Via Command Line:
```bash
cd D:\laragon\www\project-basmalahCopy
mysql -u root -p < database/create_superadmin.sql
```

### Langkah 3: Update Role Enum (Jika Masih Pakai super_admin)

Jika database Anda masih menggunakan role `super_admin` (dengan underscore), jalankan migration:

#### Via phpMyAdmin:
1. Buka `http://localhost/phpmyadmin`
2. Pilih database Anda
3. Klik tab "SQL"
4. Copy-paste isi file `database/migration_superadmin_rename.sql`
5. Klik "Go"

#### Via Command Line:
```bash
cd D:\laragon\www\project-basmalahCopy
mysql -u root -p < database/migration_superadmin_rename.sql
```

### Langkah 4: Verify di Database

Jalankan query ini di phpMyAdmin atau MySQL:

```sql
-- Check if superadmin exists
SELECT id, name, email, role, is_active, mosque_id 
FROM users 
WHERE email = 'superadmin@bismillah.com';

-- Check role enum
SHOW COLUMNS FROM users LIKE 'role';

-- Check if password hash is correct
SELECT password FROM users WHERE email = 'superadmin@bismillah.com';
```

Expected results:
- User dengan email `superadmin@bismillah.com` harus ada
- Role harus `'superadmin'` (bukan `'super_admin'`)
- `mosque_id` harus `NULL`
- `is_active` harus `1`
- Password hash harus: `$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi`

### Langkah 5: Manual Fix (Jika Masih Gagal)

Jika masih tidak bisa login, jalankan query ini untuk force update:

```sql
-- Delete existing superadmin (if any)
DELETE FROM users WHERE email = 'superadmin@bismillah.com';

-- Create fresh superadmin
INSERT INTO users (
    mosque_id,
    name,
    email,
    password,
    role,
    is_active,
    created_at,
    updated_at
) VALUES (
    NULL,
    'superadmin',
    'superadmin@bismillah.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'superadmin',
    1,
    NOW(),
    NOW()
);

-- Verify
SELECT * FROM users WHERE email = 'superadmin@bismillah.com';
```

### Langkah 6: Test Login

1. Buka: `http://localhost/project-basmalahCopy/public/index.php?url=auth/login`
2. Masukkan:
   - **Email:** `superadmin@bismillah.com`
   - **Password:** `password123`
3. Klik Login

## Common Issues

### Issue 1: "Invalid email or password"

**Penyebab:**
- User tidak ada di database
- Password hash salah
- User tidak aktif (`is_active = 0`)

**Solusi:**
- Jalankan `test_superadmin_login.php` untuk cek
- Jalankan `database/create_superadmin.sql`

### Issue 2: "Column 'role' doesn't match enum"

**Penyebab:**
- Database masih pakai `super_admin` tapi kode sudah pakai `superadmin`

**Solusi:**
- Jalankan `database/migration_superadmin_rename.sql`

### Issue 3: Login berhasil tapi redirect ke login lagi

**Penyebab:**
- Session tidak tersimpan
- Cookie blocked

**Solusi:**
```php
// Check di config/config.php
session_start(); // Pastikan ada di awal file
```

### Issue 4: "Mosque not found" atau error setelah login

**Penyebab:**
- Tidak ada masjid di database

**Solusi:**
```sql
-- Check mosques
SELECT * FROM mosques;

-- If empty, create one
INSERT INTO mosques (name, address, is_active, created_at) 
VALUES ('Masjid Test', 'Alamat Test', 1, NOW());
```

## Quick Fix: Reset Everything

Jika semua cara di atas gagal, reset database:

```sql
-- 1. Drop and recreate users table
DROP TABLE IF EXISTS users;

-- 2. Run schema.sql again
-- Copy-paste isi database/schema.sql

-- 3. Create superadmin
-- Copy-paste isi database/create_superadmin.sql
```

## Test Credentials

Setelah semua langkah di atas, gunakan:

**Email:** `superadmin@bismillah.com`  
**Password:** `password123`

## Alternative: Create via PHP Script

Jika SQL tidak work, buat file `create_superadmin.php`:

```php
<?php
require_once 'config/config.php';
require_once 'app/Core/Database.php';
require_once 'helpers/auth.php';

$db = Database::getInstance();

// Delete if exists
$db->execute("DELETE FROM users WHERE email = ?", ['superadmin@bismillah.com']);

// Create superadmin
$data = [
    'mosque_id' => null,
    'name' => 'superadmin',
    'email' => 'superadmin@bismillah.com',
    'password' => hashPassword('password123'),
    'role' => 'superadmin',
    'is_active' => 1,
    'created_at' => date('Y-m-d H:i:s'),
    'updated_at' => date('Y-m-d H:i:s')
];

$id = $db->insert('users', $data);

if ($id) {
    echo "✅ Superadmin created successfully!<br>";
    echo "ID: $id<br>";
    echo "Email: superadmin@bismillah.com<br>";
    echo "Password: password123<br>";
} else {
    echo "❌ Failed to create superadmin<br>";
}
?>
```

Akses: `http://localhost/project-basmalahCopy/create_superadmin.php`

## Contact

Jika masih ada masalah, cek:
1. Error log PHP: `D:\laragon\www\project-basmalahCopy\error.log`
2. Browser console (F12)
3. Network tab untuk cek response login
