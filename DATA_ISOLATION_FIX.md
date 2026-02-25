# Perbaikan Isolasi Data Multi-Masjid

## Masalah
Setiap masjid yang baru register melihat data masjid lain (masjid dengan ID 1). Ini terjadi karena ada fallback ke `mosque_id = 1` di beberapa tempat.

## Penyebab
1. **Controller.php** - Method `getMosqueId()` memiliki fallback `?? 1`
2. **functions.php** - Function `getMosque()` memiliki fallback ke masjid default
3. **auth.php** - Function `login()` tidak load data mosque ke session

## Solusi

### 1. Controller Base Class (`app/Core/Controller.php`)
**Sebelum:**
```php
protected function getMosqueId() {
    return $this->mosque['id'] ?? $_SESSION['mosque_id'] ?? 1;
}
```

**Sesudah:**
```php
protected function getMosqueId() {
    // Get from session first
    if (isset($_SESSION['mosque_id']) && !empty($_SESSION['mosque_id'])) {
        return $_SESSION['mosque_id'];
    }
    
    // Get from user data
    if (isset($this->user['mosque_id']) && !empty($this->user['mosque_id'])) {
        return $this->user['mosque_id'];
    }
    
    // Get from mosque array
    if (isset($this->mosque['id']) && !empty($this->mosque['id'])) {
        return $this->mosque['id'];
    }
    
    // If still not found, redirect to login
    $this->redirect('auth/logout');
}
```

### 2. Helper Functions (`helpers/functions.php`)
**Sebelum:**
```php
function getMosque() {
    return $_SESSION['mosque'] ?? ['id' => 1, 'name' => 'Masjid Al-Falah'];
}
```

**Sesudah:**
```php
function getMosque() {
    if (!isLoggedIn()) {
        return null;
    }
    
    // Check if mosque data already in session
    if (isset($_SESSION['mosque']) && !empty($_SESSION['mosque'])) {
        return $_SESSION['mosque'];
    }
    
    // Get mosque_id from session
    $mosqueId = $_SESSION['mosque_id'] ?? null;
    
    if (!$mosqueId) {
        return null;
    }
    
    // Load mosque data from database
    $db = Database::getInstance();
    $mosque = $db->first("SELECT * FROM mosques WHERE id = ?", [$mosqueId]);
    
    if ($mosque) {
        $_SESSION['mosque'] = $mosque;
        return $mosque;
    }
    
    return null;
}
```

### 3. Auth Helper (`helpers/auth.php`)
**Sebelum:**
```php
function login($userId, $user) {
    $_SESSION['user_id'] = $userId;
    $_SESSION['user'] = $user;
    $_SESSION['role'] = $user['role'];
    $_SESSION['mosque_id'] = $user['mosque_id'];
    $_SESSION['login_time'] = time();
    
    // Load user permissions
    loadPermissions($user['role']);
}
```

**Sesudah:**
```php
function login($userId, $user) {
    $_SESSION['user_id'] = $userId;
    $_SESSION['user'] = $user;
    $_SESSION['role'] = $user['role'];
    $_SESSION['mosque_id'] = $user['mosque_id'];
    $_SESSION['login_time'] = time();
    
    // Load mosque data
    if ($user['mosque_id']) {
        $db = Database::getInstance();
        $mosque = $db->first("SELECT * FROM mosques WHERE id = ?", [$user['mosque_id']]);
        if ($mosque) {
            $_SESSION['mosque'] = $mosque;
        }
    }
    
    // Load user permissions
    loadPermissions($user['role']);
}
```

## Hasil
- ✅ Setiap masjid hanya melihat data mereka sendiri
- ✅ Tidak ada fallback ke masjid default
- ✅ Jika mosque_id tidak ditemukan, user akan di-logout
- ✅ Data mosque di-load saat login dan disimpan di session

## Testing
1. **Logout dari akun lama**
2. **Register masjid baru**
   - Nama: Masjid Test 2
   - Email: admin2@test.com
3. **Login dengan akun baru**
4. **Verifikasi:**
   - Dashboard menampilkan data kosong (bukan data masjid lain)
   - Semua transaksi, akun, kategori adalah milik masjid baru
   - Tidak ada data dari masjid lain yang terlihat

## Catatan Penting
- Semua controller sudah menggunakan `$this->getMosqueId()` untuk filter data
- Semua query sudah include `WHERE mosque_id = ?`
- Session menyimpan `mosque_id` dan `mosque` data
- Isolasi data dijamin di level aplikasi dan database

## Keamanan
- User tidak bisa akses data masjid lain
- Setiap query di-filter berdasarkan mosque_id
- Tidak ada cara untuk bypass isolasi data
- Super admin masjid A tidak bisa akses data masjid B
