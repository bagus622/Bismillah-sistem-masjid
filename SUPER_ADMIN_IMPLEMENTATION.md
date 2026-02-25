# Super Admin Implementation - Summary

## Overview
Implementasi sistem Super Admin global yang dapat mengelola semua masjid dalam sistem Bismillah.

## Perubahan yang Dilakukan

### 1. Helper Functions (`helpers/auth.php`)
**Fungsi `login()`**
- Diubah untuk TIDAK set `mosque_id` di session untuk super_admin
- Super admin tidak terikat ke masjid tertentu saat login
- Admin biasa tetap terikat ke mosque_id mereka

```php
// Super Admin: Don't set mosque_id in session yet
if ($user['role'] !== 'super_admin') {
    $_SESSION['mosque_id'] = $user['mosque_id'];
    // Load mosque data
} else {
    $_SESSION['mosque_id'] = null;
    $_SESSION['selected_mosque_id'] = null;
}
```

### 2. Helper Functions (`helpers/functions.php`)
**Fungsi `getMosque()`**
- Diubah untuk handle super admin dengan selected mosque
- Super admin menggunakan `$_SESSION['selected_mosque_id']`
- Admin biasa menggunakan `$_SESSION['mosque_id']` dari user mereka

```php
// Super Admin: Get selected mosque
if ($user['role'] === 'super_admin') {
    $mosqueId = $_SESSION['selected_mosque_id'] ?? null;
    // Load mosque based on selection
}
```

### 3. Base Controller (`app/Core/Controller.php`)
**Method `getMosqueId()`**
- Sudah diupdate sebelumnya untuk handle super admin
- Return null jika super admin belum pilih masjid
- Tidak redirect ke logout untuk super admin yang belum pilih masjid

### 4. Dashboard Controller (`controllers/DashboardController.php`)
**Method Baru:**

#### `index()`
- Ditambahkan pengecekan: jika super admin belum pilih masjid, tampilkan selector
- Jika sudah pilih, tampilkan dashboard normal

#### `showMosqueSelector()` (private)
- Menampilkan halaman pemilihan masjid untuk super admin
- List semua masjid aktif dalam sistem
- Hanya bisa diakses oleh super admin

#### `switchMosque()` (public)
- Handle POST request untuk switch masjid
- Validasi mosque_id dan set ke session
- Set `$_SESSION['selected_mosque_id']` dan `$_SESSION['mosque']`
- Redirect ke dashboard

#### `clearMosque()` (public)
- Clear selected mosque dari session
- Kembali ke halaman mosque selector
- Hanya bisa diakses super admin

### 5. Auth Controller (`controllers/AuthController.php`)
**Method `doRegister()`**
- Diubah dari `'role' => 'super_admin'` menjadi `'role' => 'admin'`
- User yang dibuat saat registrasi adalah admin masjid, bukan super admin
- Super admin harus dibuat manual via database

### 6. View: Mosque Selector (`views/dashboard/mosque_selector.php`)
**File Baru**
- Halaman pemilihan masjid untuk super admin
- Grid layout dengan card untuk setiap masjid
- Menampilkan info: nama, alamat, telepon, email
- Form POST ke `dashboard/switchMosque`
- Tombol logout di bawah

### 7. View: Header Layout (`views/layouts/header.php`)
**Penambahan:**
- Dropdown mosque switcher di header untuk super admin
- Menampilkan nama masjid yang sedang aktif
- Tombol "Ganti Masjid" untuk kembali ke selector
- Hanya muncul jika super admin sudah pilih masjid

### 8. Language Files
**`lang/en.php` dan `lang/id.php`**
- Ditambahkan translations untuk mosque selector:
  - `select_mosque`
  - `switch_mosque`
  - `mosque_switched`
  - `no_mosque_registered`
  - `select_mosque_description`

### 9. Documentation
**`SUPER_ADMIN_SETUP.md`**
- Panduan lengkap cara membuat super admin
- Penjelasan perbedaan super admin vs admin biasa
- Query SQL untuk insert super admin
- Cara ganti password
- Catatan keamanan

**`SUPER_ADMIN_IMPLEMENTATION.md`** (file ini)
- Summary implementasi
- Daftar perubahan
- Flow diagram

## Flow Diagram

### Login Flow - Super Admin
```
1. Super Admin login
   ↓
2. login() dipanggil
   ↓
3. $_SESSION['selected_mosque_id'] = null
   ↓
4. Redirect ke dashboard
   ↓
5. DashboardController->index() cek selected_mosque_id
   ↓
6. Jika null → showMosqueSelector()
   ↓
7. User pilih masjid
   ↓
8. POST ke switchMosque()
   ↓
9. Set $_SESSION['selected_mosque_id']
   ↓
10. Redirect ke dashboard dengan data masjid
```

### Login Flow - Admin Biasa
```
1. Admin login
   ↓
2. login() dipanggil
   ↓
3. $_SESSION['mosque_id'] = $user['mosque_id']
   ↓
4. Load mosque data ke session
   ↓
5. Redirect ke dashboard
   ↓
6. Dashboard langsung tampil dengan data masjid mereka
```

### Switch Mosque Flow - Super Admin
```
1. Super admin klik dropdown masjid di header
   ↓
2. Klik "Ganti Masjid"
   ↓
3. clearMosque() dipanggil
   ↓
4. unset($_SESSION['selected_mosque_id'])
   ↓
5. Redirect ke dashboard
   ↓
6. showMosqueSelector() tampil
   ↓
7. Pilih masjid baru
   ↓
8. switchMosque() set masjid baru
```

## Data Isolation

### Super Admin
- Dapat akses SEMUA data dari masjid yang dipilih
- `getMosqueId()` return `$_SESSION['selected_mosque_id']`
- Semua query menggunakan `WHERE mosque_id = ?` dengan selected_mosque_id
- Dapat switch antar masjid kapan saja

### Admin Biasa
- Hanya akses data masjid mereka sendiri
- `getMosqueId()` return `$_SESSION['mosque_id']` (fixed)
- Tidak bisa switch masjid
- Tidak bisa lihat data masjid lain

## Testing Checklist

### Super Admin
- [ ] Login sebagai super admin
- [ ] Halaman mosque selector muncul
- [ ] Bisa pilih masjid
- [ ] Dashboard menampilkan data masjid yang dipilih
- [ ] Dropdown masjid muncul di header
- [ ] Bisa ganti masjid
- [ ] Data berubah sesuai masjid yang dipilih
- [ ] Semua menu (transactions, accounts, dll) menampilkan data masjid yang benar

### Admin Biasa
- [ ] Login sebagai admin masjid
- [ ] Langsung masuk dashboard (tidak ada mosque selector)
- [ ] Dropdown masjid TIDAK muncul di header
- [ ] Hanya bisa lihat data masjid sendiri
- [ ] Tidak bisa akses data masjid lain

### Register
- [ ] Register masjid baru
- [ ] User yang dibuat adalah 'admin', bukan 'super_admin'
- [ ] Admin terikat ke mosque_id yang baru dibuat
- [ ] Auto login setelah register
- [ ] Langsung masuk dashboard masjid mereka

## Security Notes

1. Super admin memiliki akses penuh ke semua data
2. Validasi role di setiap method yang sensitive
3. Admin biasa tidak bisa akses fitur super admin
4. Mosque_id selalu divalidasi di query
5. Session management yang proper untuk prevent session hijacking

## Files Modified

1. `helpers/auth.php` - login()
2. `helpers/functions.php` - getMosque()
3. `app/Core/Controller.php` - getMosqueId()
4. `controllers/DashboardController.php` - index(), switchMosque(), clearMosque(), showMosqueSelector()
5. `controllers/AuthController.php` - doRegister()
6. `views/layouts/header.php` - mosque switcher dropdown
7. `lang/en.php` - translations
8. `lang/id.php` - translations

## Files Created

1. `views/dashboard/mosque_selector.php` - Mosque selection page
2. `SUPER_ADMIN_SETUP.md` - Setup guide
3. `SUPER_ADMIN_IMPLEMENTATION.md` - This file

## Next Steps (Optional Enhancements)

1. Add mosque management page for super admin (CRUD mosques)
2. Add user management across mosques for super admin
3. Add global reports (compare data across mosques)
4. Add mosque statistics dashboard for super admin
5. Add audit log untuk track super admin activities
6. Add permission untuk limit super admin access (jika diperlukan)
