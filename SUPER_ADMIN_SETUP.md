# Super Admin Setup Guide

## Sistem Akun

Aplikasi Bismillah memiliki 2 jenis akun:

### 1. Super Admin (Global)
- **Hanya 1 untuk seluruh sistem**
- Dapat melihat dan mengelola SEMUA masjid
- Tidak terikat ke masjid tertentu
- Harus memilih masjid sebelum mengakses data
- Dapat berpindah antar masjid

### 2. Admin/Accountant/Treasurer/Member (Per Masjid)
- **Setiap masjid memiliki admin sendiri**
- Hanya dapat melihat data masjid mereka
- Terikat ke 1 masjid tertentu
- Tidak dapat melihat data masjid lain

## Cara Membuat Super Admin

Super Admin tidak dapat dibuat melalui form registrasi. Harus dibuat manual melalui database.

### Langkah 1: Buat User Super Admin di Database

Jalankan query SQL berikut di database Anda:

```sql
-- Ganti nilai sesuai kebutuhan
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
    NULL,  -- Super Admin tidak terikat ke masjid
    'superadmin',  -- Nama super admin
    'superadmin@bismillah.com',  -- Email super admin
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',  -- Password: password123
    '081234567890',  -- Nomor telepon (opsional)
    'superadmin',  -- Role HARUS superadmin
    1,  -- Aktif
    NOW()
);
```

### Langkah 2: Login sebagai Super Admin

1. Buka halaman login
2. Login dengan email dan password yang sudah dibuat
3. Setelah login, Anda akan diarahkan ke halaman pemilihan masjid
4. Pilih masjid yang ingin dikelola
5. Dashboard akan menampilkan data masjid yang dipilih

### Langkah 3: Ganti Masjid (Opsional)

Sebagai Super Admin, Anda dapat berpindah antar masjid:

1. Klik dropdown masjid di header (pojok kanan atas)
2. Klik "Ganti Masjid"
3. Pilih masjid lain yang ingin dikelola

## Password Default

Password default dalam contoh di atas adalah: `password123`

Untuk membuat password baru, gunakan PHP:

```php
<?php
echo password_hash('password_anda', PASSWORD_BCRYPT, ['cost' => 10]);
?>
```

## Catatan Penting

1. **Super Admin tidak memiliki mosque_id** - Kolom `mosque_id` harus `NULL`
2. **Role harus 'superadmin'** - Pastikan role tepat, bukan 'admin'
3. **Hanya buat 1 Super Admin** - Sistem dirancang untuk 1 super admin global
4. **Admin per masjid dibuat via registrasi** - Setiap masjid yang register akan mendapat admin sendiri
5. **Super Admin tidak muncul di daftar user** - Admin biasa tidak dapat melihat akun superadmin di halaman user management

## Keamanan

- Ganti password default setelah login pertama kali
- Jangan share kredensial super admin
- Super admin memiliki akses penuh ke semua data masjid
