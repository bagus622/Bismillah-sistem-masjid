# Fitur Register Multi-Masjid

## Overview
Sistem Bismillah sekarang mendukung registrasi multi-masjid, dimana setiap masjid dapat mendaftar dan memiliki data yang terpisah.

## Fitur yang Ditambahkan

### 1. Halaman Register Modern
- **URL**: `/auth/register`
- **Desain**: Multi-step form dengan Tailwind CSS
- **Step 1**: Data Masjid (Nama, Alamat, Telepon, Email)
- **Step 2**: Data Administrator (Nama, Email, Telepon, Password)

### 2. Proses Registrasi
Ketika masjid baru mendaftar, sistem akan otomatis:

1. **Membuat Data Masjid Baru**
   - Menyimpan informasi masjid ke tabel `mosques`
   - Status aktif secara default

2. **Membuat User Super Admin**
   - Role: `super_admin`
   - Akses penuh ke semua fitur
   - Auto-login setelah registrasi

3. **Membuat Kategori Default**
   - **Pemasukan**: Zakat, Infaq, Sedekah, Donasi
   - **Pengeluaran**: Operasional, Perlengkapan, Pengajian, Santunan, Pembangunan

4. **Membuat Akun Default**
   - Kas Kecil (Cash)
   - Kas Besar (Cash)

5. **Membuat Settings Default**
   - Language: Indonesia
   - Currency: IDR (Rp)
   - Timezone: Asia/Jakarta
   - Date Format: d/m/Y

## Struktur Database

### Tabel `mosques`
```sql
- id (Primary Key)
- name (Nama Masjid)
- address (Alamat)
- phone (Telepon)
- email (Email)
- logo (Logo - optional)
- description (Deskripsi)
- is_active (Status)
- created_at, updated_at
```

### Tabel `users`
```sql
- id (Primary Key)
- mosque_id (Foreign Key ke mosques)
- name, email, password
- phone, photo
- role (super_admin, admin, accountant, treasurer, member)
- is_active
- last_login
- created_at, updated_at
```

## Isolasi Data
Setiap masjid memiliki data yang terpisah:
- Semua tabel utama memiliki `mosque_id`
- Query selalu filter berdasarkan `mosque_id`
- User hanya bisa akses data masjid mereka sendiri

## Keamanan
1. **Password Hashing**: Menggunakan `password_hash()` PHP
2. **Validation**: 
   - Email harus valid dan unik
   - Password minimal 6 karakter
   - Nama masjid wajib diisi
3. **Transaction**: Menggunakan database transaction untuk memastikan data konsisten
4. **Auto-login**: Setelah registrasi berhasil, user langsung login

## Cara Menggunakan

### Untuk User Baru
1. Buka `/auth/login`
2. Klik "Daftar Masjid Baru"
3. Isi data masjid (Step 1)
4. Klik "Lanjutkan"
5. Isi data administrator (Step 2)
6. Klik "Daftar Sekarang"
7. Sistem akan redirect ke dashboard

### Untuk Developer
```php
// AuthController.php
public function doRegister() {
    // Validasi input
    // Buat masjid baru
    // Buat user admin
    // Buat data default (categories, accounts, settings)
    // Auto-login
    // Redirect ke dashboard
}
```

## File yang Dimodifikasi/Dibuat

### Baru
- `views/auth/register.php` - Halaman register dengan multi-step form

### Dimodifikasi
- `controllers/AuthController.php` - Menambahkan method `doRegister()` dan helper methods
- `views/auth/login.php` - Menambahkan link ke halaman register

## Testing
1. Akses `/auth/register`
2. Isi form dengan data test
3. Submit form
4. Verifikasi:
   - Data masjid tersimpan di tabel `mosques`
   - User admin tersimpan di tabel `users`
   - Kategori default tersimpan di tabel `categories`
   - Akun default tersimpan di tabel `accounts`
   - Settings default tersimpan di tabel `settings`
   - User ter-login otomatis
   - Redirect ke dashboard

## Catatan
- Setiap masjid memiliki data yang benar-benar terpisah
- Super admin masjid A tidak bisa akses data masjid B
- Sistem mendukung unlimited jumlah masjid
- Setiap masjid bisa menambah user sendiri dengan role berbeda

## Future Improvements
1. Email verification
2. Forgot password
3. Mosque logo upload
4. Custom categories saat registrasi
5. Subscription/payment system
6. Multi-language support untuk form register
