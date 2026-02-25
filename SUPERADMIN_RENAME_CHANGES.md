# Perubahan: Rename super_admin menjadi superadmin

## Summary
Role "super_admin" telah diubah menjadi "superadmin" (tanpa underscore) di seluruh sistem, dan akun superadmin tidak akan muncul di daftar user yang dilihat oleh admin biasa.

## Perubahan yang Dilakukan

### 1. Database Schema (`database/schema.sql`)
- ENUM role di tabel `users`: `'super_admin'` → `'superadmin'`
- ENUM role di tabel `role_permissions`: `'super_admin'` → `'superadmin'`
- INSERT role_permissions: `'super_admin'` → `'superadmin'`
- Sample user: role `'super_admin'` → `'admin'` (untuk admin masjid)

### 2. Migration File (`database/migration_superadmin_rename.sql`)
**File Baru** - SQL migration untuk update database yang sudah ada:
- Alter table untuk menambah enum value baru
- Update existing data dari `super_admin` ke `superadmin`
- Remove enum value lama

### 3. Config (`config/config.php`)
```php
$ROLES = [
    'superadmin' => 'Super Admin',  // Changed from 'super_admin'
    'admin' => 'Admin',
    // ...
];
```

### 4. Helper Functions (`helpers/auth.php`)
- `login()`: `'super_admin'` → `'superadmin'`
- `can()`: `'super_admin'` → `'superadmin'`
- `isAdmin()`: `'super_admin'` → `'superadmin'`
- `isTreasurer()`: `'super_admin'` → `'superadmin'`
- `isAccountant()`: `'super_admin'` → `'superadmin'`

### 5. Helper Functions (`helpers/functions.php`)
- `hasPermission()`: `'super_admin'` → `'superadmin'`
- `getMosque()`: `'super_admin'` → `'superadmin'`

### 6. Base Controller (`app/Core/Controller.php`)
- `getMosqueId()`: `'super_admin'` → `'superadmin'`

### 7. Dashboard Controller (`controllers/DashboardController.php`)
- `index()`: `'super_admin'` → `'superadmin'`
- `switchMosque()`: `'super_admin'` → `'superadmin'`
- `clearMosque()`: `'super_admin'` → `'superadmin'`

### 8. User Controller (`controllers/UserController.php`)
**Perubahan Besar:**

#### `index()` - Hide Superadmin dari List
```php
// Hide superadmin from regular admin view
$users = $this->db->query("
    SELECT * FROM users 
    WHERE mosque_id = ? AND role != 'superadmin'
    ORDER BY name
", [$mosqueId]);
```

#### `create()` - Remove Superadmin dari Dropdown
```php
// Remove superadmin from available roles for regular admins
$availableRoles = $ROLES;
unset($availableRoles['superadmin']);
```

#### `edit()` - Remove Superadmin dari Dropdown
```php
// Remove superadmin from available roles for regular admins
$availableRoles = $ROLES;
unset($availableRoles['superadmin']);
```

### 9. Views (`views/layouts/header.php`)
- Mosque switcher check: `'super_admin'` → `'superadmin'`

### 10. Views (`views/users/create.php` & `views/users/edit.php`)
- Loop variable: `$ROLES` → `$availableRoles`

### 11. Language Files
**`lang/en.php`:**
```php
'superadmin' => 'Super Admin',  // Changed from 'super_admin'
```

**`lang/id.php`:**
```php
'superadmin' => 'Super Admin',  // Changed from 'super_admin'
```

### 12. Documentation (`SUPER_ADMIN_SETUP.md`)
- Nama user: `'Super Administrator'` → `'superadmin'`
- Email: tetap `'superadmin@bismillah.com'`
- Role: `'super_admin'` → `'superadmin'`
- Tambahan catatan: "Super Admin tidak muncul di daftar user"

## Cara Membuat Superadmin

### Query SQL Baru:
```sql
INSERT INTO users (
    mosque_id,
    name,
    email,
    password,
    phone,
    role,
    is_active,
    created_at
) VALUES (
    NULL,
    'superadmin',
    'superadmin@bismillah.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    NULL,
    'superadmin',
    1,
    NOW()
);
```

Password default: `password123`

## Migration untuk Database yang Sudah Ada

Jika Anda sudah memiliki database dengan role `super_admin`, jalankan:

```bash
mysql -u username -p database_name < database/migration_superadmin_rename.sql
```

Atau copy-paste isi file `database/migration_superadmin_rename.sql` ke phpMyAdmin.

## Fitur Baru: Hide Superadmin dari Admin Biasa

### Sebelum:
- Admin biasa bisa melihat semua user termasuk superadmin di halaman user management
- Admin biasa bisa memilih role "Super Admin" saat create/edit user

### Sesudah:
- Admin biasa TIDAK bisa melihat user dengan role `superadmin` di list
- Admin biasa TIDAK bisa memilih role "Super Admin" di dropdown
- Hanya superadmin yang bisa manage superadmin (via database)

### Query yang Diubah:
```php
// OLD
SELECT * FROM users WHERE mosque_id = ? ORDER BY name

// NEW
SELECT * FROM users 
WHERE mosque_id = ? AND role != 'superadmin'
ORDER BY name
```

## Testing Checklist

### Database Migration
- [ ] Backup database sebelum migration
- [ ] Jalankan migration SQL
- [ ] Verify: `SELECT * FROM users WHERE role = 'superadmin'`
- [ ] Verify: `SELECT * FROM role_permissions WHERE role = 'superadmin'`
- [ ] Tidak ada lagi role `super_admin` di database

### Superadmin Login
- [ ] Login sebagai superadmin
- [ ] Halaman mosque selector muncul
- [ ] Bisa pilih masjid
- [ ] Dashboard berfungsi normal
- [ ] Bisa switch masjid

### Admin Biasa - User Management
- [ ] Login sebagai admin masjid
- [ ] Buka halaman Users
- [ ] Akun superadmin TIDAK muncul di list
- [ ] Klik "Add User"
- [ ] Dropdown role TIDAK ada "Super Admin"
- [ ] Edit user existing
- [ ] Dropdown role TIDAK ada "Super Admin"

### Register
- [ ] Register masjid baru
- [ ] User yang dibuat adalah 'admin', bukan 'superadmin'
- [ ] Auto login berhasil
- [ ] Dashboard muncul normal

## Files Modified

1. `database/schema.sql` - Update ENUM values
2. `config/config.php` - Update $ROLES array
3. `helpers/auth.php` - Update all role checks
4. `helpers/functions.php` - Update all role checks
5. `app/Core/Controller.php` - Update getMosqueId()
6. `controllers/DashboardController.php` - Update all role checks
7. `controllers/UserController.php` - Hide superadmin, remove from dropdown
8. `views/layouts/header.php` - Update role check
9. `views/users/create.php` - Use availableRoles
10. `views/users/edit.php` - Use availableRoles
11. `lang/en.php` - Update translation key
12. `lang/id.php` - Update translation key
13. `SUPER_ADMIN_SETUP.md` - Update documentation

## Files Created

1. `database/migration_superadmin_rename.sql` - Migration script
2. `SUPERADMIN_RENAME_CHANGES.md` - This file

## Breaking Changes

⚠️ **IMPORTANT**: Jika Anda sudah memiliki database dengan role `super_admin`, Anda HARUS menjalankan migration SQL sebelum menggunakan kode yang baru.

Tanpa migration, user dengan role `super_admin` tidak akan bisa login karena sistem sekarang menggunakan `superadmin`.

## Rollback (Jika Diperlukan)

Jika ingin kembali ke `super_admin`:

```sql
-- Rollback: superadmin to super_admin
ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'superadmin', 'admin', 'accountant', 'treasurer', 'member') DEFAULT 'member';
UPDATE users SET role = 'super_admin' WHERE role = 'superadmin';

ALTER TABLE role_permissions MODIFY COLUMN role ENUM('super_admin', 'superadmin', 'admin', 'accountant', 'treasurer', 'member') NOT NULL;
UPDATE role_permissions SET role = 'super_admin' WHERE role = 'superadmin';

ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'accountant', 'treasurer', 'member') DEFAULT 'member';
ALTER TABLE role_permissions MODIFY COLUMN role ENUM('super_admin', 'admin', 'accountant', 'treasurer', 'member') NOT NULL;
```

Kemudian revert semua perubahan kode.
