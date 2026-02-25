# Modern Notification System

Sistem notifikasi modern dengan toast popup dan loading overlay.

## Fitur

1. **Toast Notifications** - Popup notifikasi yang muncul dari kanan atas
2. **Loading Overlay** - Loading screen dengan spinner animasi
3. **Auto-dismiss** - Notifikasi otomatis hilang setelah 5 detik
4. **Manual close** - Bisa ditutup manual dengan tombol X
5. **4 Tipe notifikasi**: Success, Error, Warning, Info

## Cara Penggunaan

### 1. Toast Notifications

#### Dari JavaScript:
```javascript
// Success notification
window.toast.success('Data berhasil disimpan!');

// Error notification
window.toast.error('Terjadi kesalahan!');

// Warning notification
window.toast.warning('Perhatian! Data akan dihapus.');

// Info notification
window.toast.info('Informasi penting.');

// Custom duration (dalam milidetik)
window.toast.success('Pesan ini akan hilang dalam 3 detik', 3000);

// Tidak auto-dismiss (duration = 0)
window.toast.error('Pesan ini tidak akan hilang otomatis', 0);
```

#### Dari PHP (Flash Messages):
```php
// Di controller
setSuccess('Masjid berhasil ditambahkan');
setError('Nama masjid harus diisi');
setWarning('Data akan dihapus permanen');
setInfo('Silakan lengkapi data');

// Flash messages otomatis dikonversi menjadi toast saat page load
```

### 2. Loading Overlay

```javascript
// Show loading
window.loading.show('Memproses data...');

// Show loading dengan pesan default
window.loading.show();

// Hide loading
window.loading.hide();
```

#### Contoh dengan Form Submit:
```javascript
document.getElementById('myForm').addEventListener('submit', function(e) {
    window.loading.show('Menyimpan data...');
    // Form akan submit dan loading akan muncul
});
```

#### Contoh dengan AJAX:
```javascript
// Show loading sebelum request
window.loading.show('Mengambil data...');

fetch('/api/data')
    .then(response => response.json())
    .then(data => {
        window.loading.hide();
        window.toast.success('Data berhasil dimuat!');
    })
    .catch(error => {
        window.loading.hide();
        window.toast.error('Gagal memuat data!');
    });
```

## Styling

### Toast Colors:
- **Success**: Green (bg-green-50, border-green-200, text-green-800)
- **Error**: Red (bg-red-50, border-red-200, text-red-800)
- **Warning**: Yellow (bg-yellow-50, border-yellow-200, text-yellow-800)
- **Info**: Blue (bg-blue-50, border-blue-200, text-blue-800)

### Loading Spinner:
- Warna: Violet (border-violet-600)
- Animasi: Spin dengan Tailwind CSS
- Background: Black dengan opacity 50% dan backdrop blur

## Animasi

1. **Toast slide-in**: Muncul dari kanan dengan translate-x
2. **Toast fade-in**: Opacity dari 0 ke 100
3. **Loading spinner**: Rotate 360 derajat continuous
4. **Modal backdrop**: Blur effect

## Browser Support

- Chrome/Edge: ✅
- Firefox: ✅
- Safari: ✅
- Opera: ✅

## Dependencies

- Tailwind CSS (untuk styling)
- Lucide Icons (untuk icon)
- Alpine.js (opsional, tidak wajib)

## File Structure

```
public/js/
├── notifications.js    # Sistem notifikasi utama
└── mosques.js         # Contoh implementasi di mosques

views/layouts/
└── header.php         # Include notifications.js
```

## Customization

### Mengubah Durasi Default:
Edit di `notifications.js`:
```javascript
show(message, type = 'info', duration = 5000) // Ubah 5000 ke nilai lain
```

### Mengubah Posisi Toast:
Edit class di `notifications.js`:
```javascript
// Dari kanan atas (default)
this.container.className = 'fixed top-4 right-4 z-[9999] flex flex-col gap-3';

// Ke kiri atas
this.container.className = 'fixed top-4 left-4 z-[9999] flex flex-col gap-3';

// Ke tengah atas
this.container.className = 'fixed top-4 left-1/2 -translate-x-1/2 z-[9999] flex flex-col gap-3';
```

### Mengubah Warna Loading Spinner:
Edit di `notifications.js`:
```javascript
// Dari violet ke warna lain
<div class="absolute inset-0 border-4 border-blue-600 rounded-full border-t-transparent animate-spin"></div>
```

## Tips

1. Gunakan `window.loading.show()` sebelum operasi yang memakan waktu
2. Selalu panggil `window.loading.hide()` setelah operasi selesai
3. Gunakan toast untuk feedback user yang tidak blocking
4. Gunakan duration 0 untuk pesan error penting yang perlu dibaca user
5. Kombinasikan loading + toast untuk UX yang lebih baik

## Contoh Lengkap

```javascript
// Delete dengan konfirmasi
function deleteItem(id) {
    if (confirm('Yakin ingin menghapus?')) {
        window.loading.show('Menghapus data...');
        
        fetch('/api/delete/' + id, { method: 'DELETE' })
            .then(response => response.json())
            .then(data => {
                window.loading.hide();
                if (data.success) {
                    window.toast.success('Data berhasil dihapus!');
                    // Reload atau update UI
                } else {
                    window.toast.error(data.message);
                }
            })
            .catch(error => {
                window.loading.hide();
                window.toast.error('Terjadi kesalahan!');
            });
    }
}
```

---

**Dibuat:** 23 Februari 2026  
**Status:** Production Ready ✅
