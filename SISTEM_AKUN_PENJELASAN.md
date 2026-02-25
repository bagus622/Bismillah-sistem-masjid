# 📚 Penjelasan Sistem Akun Bismillah

## 🎯 Konsep Dasar

Sistem Bismillah memiliki 2 tingkat akun yang berbeda:

### 1️⃣ SUPERADMIN (Global - Hanya 1)
### 2️⃣ ADMIN MASJID (Per Masjid - Setiap Masjid Punya 1)

---

## 🔐 1. SUPERADMIN (Global)

### Karakteristik:
- ✅ **Jumlah:** Hanya 1 untuk seluruh sistem
- ✅ **Akses:** Dapat melihat dan mengelola SEMUA masjid
- ✅ **Mosque ID:** NULL (tidak terikat ke masjid tertentu)
- ✅ **Cara Kerja:** Harus pilih masjid dulu sebelum akses data
- ✅ **Fleksibilitas:** Bisa switch antar masjid kapan saja
- ✅ **Visibilitas:** Tidak muncul di daftar user yang dilihat admin masjid

### Kredensial Default:
```
Email: superadmin@bismillah.com
Password: password123
Role: superadmin
```

### Flow Kerja Superadmin:
```
1. Login
   ↓
2. Pilih Masjid (dari daftar semua masjid)
   ↓
3. Dashboard menampilkan data masjid yang dipilih
   ↓
4. Bisa switch ke masjid lain kapan saja
```

### Contoh Use Case:
- Developer/Owner sistem yang perlu monitor semua masjid
- Support team yang perlu akses ke berbagai masjid
- Audit atau review data lintas masjid

---

## 🕌 2. ADMIN MASJID (Per Masjid)

### Karakteristik:
- ✅ **Jumlah:** Setiap masjid memiliki 1 admin sendiri
- ✅ **Akses:** Hanya bisa lihat data masjid mereka sendiri
- ✅ **Mosque ID:** Terikat ke 1 masjid tertentu (contoh: mosque_id = 1)
- ✅ **Cara Kerja:** Langsung masuk dashboard masjid mereka
- ✅ **Fleksibilitas:** TIDAK bisa switch masjid atau lihat masjid lain
- ✅ **Pembuatan:** Otomatis dibuat saat registrasi masjid baru

### Kredensial (Contoh):
```
Email: admin@masjid-alfalah.com
Password: [ditentukan saat registrasi]
Role: admin
Mosque ID: 1
```

### Flow Kerja Admin Masjid:
```
1. Registrasi Masjid Baru
   ↓
2. Sistem otomatis buat akun admin untuk masjid tersebut
   ↓
3. Login → Langsung masuk dashboard masjid mereka
   ↓
4. Hanya bisa lihat & kelola data masjid sendiri
```

### Contoh Use Case:
- Pengurus masjid yang mengelola keuangan masjid mereka
- Bendahara masjid
- Takmir masjid

---

## 📊 Perbandingan

| Aspek | Superadmin | Admin Masjid |
|-------|-----------|--------------|
| **Jumlah** | 1 untuk seluruh sistem | 1 per masjid |
| **Mosque ID** | NULL | ID masjid tertentu |
| **Akses Data** | Semua masjid | Hanya masjid sendiri |
| **Switch Masjid** | ✅ Bisa | ❌ Tidak bisa |
| **Dibuat Via** | Manual (SQL/Script) | Otomatis (Registrasi) |
| **Muncul di User List** | ❌ Tidak | ✅ Ya |
| **Bisa Diedit Admin** | ❌ Tidak | ✅ Ya (oleh admin masjid) |

---

## 🔒 Isolasi Data

### Bagaimana Data Diisolasi?

Setiap query di sistem menggunakan `mosque_id` untuk filter data:

```sql
-- Contoh: Admin Masjid A (mosque_id = 1)
SELECT * FROM transactions WHERE mosque_id = 1;
-- Hanya melihat transaksi masjid A

-- Contoh: Superadmin pilih Masjid B (selected_mosque_id = 2)
SELECT * FROM transactions WHERE mosque_id = 2;
-- Melihat transaksi masjid B
```

### Implementasi di Code:

```php
// Di setiap controller
$mosqueId = $this->getMosqueId();

// getMosqueId() akan return:
// - Untuk Admin Masjid: mosque_id dari user mereka (fixed)
// - Untuk Superadmin: selected_mosque_id dari session (bisa berubah)

// Semua query menggunakan mosque_id ini
$transactions = $this->db->query("
    SELECT * FROM transactions WHERE mosque_id = ?
", [$mosqueId]);
```

---

## 🎭 Skenario Penggunaan

### Skenario 1: Masjid Baru Registrasi

```
1. Pengurus Masjid Al-Falah buka website
2. Klik "Register"
3. Isi data:
   - Nama Masjid: Masjid Al-Falah
   - Alamat: Jl. Masjid No. 123
   - Email Admin: admin@alfalah.com
   - Password: rahasia123
4. Submit
5. Sistem otomatis:
   - Buat data masjid (mosque_id = 1)
   - Buat akun admin dengan role 'admin' dan mosque_id = 1
   - Auto login
6. Admin langsung masuk dashboard Masjid Al-Falah
7. Admin hanya bisa lihat data Masjid Al-Falah
```

### Skenario 2: Superadmin Monitor Masjid

```
1. Superadmin login dengan superadmin@bismillah.com
2. Sistem tampilkan halaman "Pilih Masjid"
3. Superadmin lihat daftar:
   - Masjid Al-Falah
   - Masjid An-Nur
   - Masjid Al-Ikhlas
4. Superadmin pilih "Masjid Al-Falah"
5. Dashboard menampilkan data Masjid Al-Falah
6. Superadmin bisa:
   - Lihat transaksi Masjid Al-Falah
   - Lihat laporan Masjid Al-Falah
   - Edit data Masjid Al-Falah
7. Jika ingin lihat masjid lain:
   - Klik dropdown masjid di header
   - Klik "Ganti Masjid"
   - Pilih masjid lain
```

### Skenario 3: Admin Masjid Kelola User

```
1. Admin Masjid Al-Falah login
2. Buka menu "Users"
3. Lihat daftar user:
   - Admin Masjid (admin@alfalah.com) - Role: admin
   - Bendahara (bendahara@alfalah.com) - Role: treasurer
   - Akuntan (akuntan@alfalah.com) - Role: accountant
4. TIDAK melihat akun superadmin (disembunyikan)
5. Klik "Add User" untuk tambah user baru
6. Dropdown role menampilkan:
   - Admin
   - Accountant
   - Treasurer
   - Member
7. TIDAK ada pilihan "Super Admin" (disembunyikan)
```

---

## 🛡️ Keamanan

### Proteksi yang Diterapkan:

1. **Isolasi Data per Masjid**
   - Setiap query menggunakan `WHERE mosque_id = ?`
   - Admin masjid tidak bisa akses data masjid lain

2. **Superadmin Tersembunyi**
   - Query user list: `WHERE role != 'superadmin'`
   - Admin masjid tidak bisa lihat atau edit superadmin

3. **Role Restriction**
   - Admin masjid tidak bisa assign role 'superadmin'
   - Dropdown role tidak menampilkan 'superadmin'

4. **Session Management**
   - Superadmin: `$_SESSION['selected_mosque_id']` (bisa berubah)
   - Admin Masjid: `$_SESSION['mosque_id']` (fixed)

---

## 📝 Database Structure

### Tabel Users:

```sql
CREATE TABLE users (
    id INT PRIMARY KEY,
    mosque_id INT NULL,  -- NULL untuk superadmin, ID untuk admin masjid
    name VARCHAR(255),
    email VARCHAR(255),
    password VARCHAR(255),
    role ENUM('superadmin', 'admin', 'accountant', 'treasurer', 'member'),
    is_active TINYINT(1),
    created_at TIMESTAMP
);
```

### Contoh Data:

```sql
-- Superadmin (Global)
INSERT INTO users VALUES (
    1, NULL, 'superadmin', 'superadmin@bismillah.com', 
    '[hash]', 'superadmin', 1, NOW()
);

-- Admin Masjid Al-Falah
INSERT INTO users VALUES (
    2, 1, 'Admin Al-Falah', 'admin@alfalah.com', 
    '[hash]', 'admin', 1, NOW()
);

-- Admin Masjid An-Nur
INSERT INTO users VALUES (
    3, 2, 'Admin An-Nur', 'admin@annur.com', 
    '[hash]', 'admin', 1, NOW()
);
```

---

## ✅ Checklist Implementasi

- [x] Superadmin bisa switch antar masjid
- [x] Admin masjid hanya lihat data masjid sendiri
- [x] Superadmin tidak muncul di user list admin masjid
- [x] Role 'superadmin' tidak ada di dropdown create/edit user
- [x] Semua query menggunakan mosque_id untuk isolasi data
- [x] Registrasi masjid baru otomatis buat admin dengan role 'admin'
- [x] Session management berbeda untuk superadmin vs admin masjid
- [x] Mosque selector untuk superadmin setelah login

---

## 🚀 Cara Membuat Akun

### Superadmin (Manual):
```bash
# Akses script PHP
http://localhost/project-basmalahCopy/create_superadmin.php

# Atau jalankan SQL
mysql -u root -p < database/create_superadmin.sql
```

### Admin Masjid (Otomatis):
```
1. Buka halaman registrasi
2. Isi form registrasi masjid
3. Submit
4. Sistem otomatis buat admin masjid
```

---

## 📞 Support

Jika ada pertanyaan atau masalah:
1. Cek file `TROUBLESHOOTING_LOGIN.md`
2. Jalankan `test_superadmin_login.php` untuk diagnosa
3. Review file `SUPER_ADMIN_IMPLEMENTATION.md` untuk detail teknis
