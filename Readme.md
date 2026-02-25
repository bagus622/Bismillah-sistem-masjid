# bismallah, sistem informasi manajemen masjid

### Fitur Utama:

1. **Manajemen Transaksi Keuangan**
    - Pemasukan (Income): Zakat, Infaq, Sedekah, Donasi
    - Pengeluaran (Expense): Operasional, perlengkapan, kegiatan
    - Upcoming Income & Expense (transaksi yang akan datang)
2. **Manajemen Akun/Kas**
    - Multiple accounts (Kas Kecil, Kas Besar, Rekening Bank)
    - Tracking saldo per akun
    - Riwayat transaksi per akun
3. **Kategori & Sub-Kategori**
    - Kategori pemasukan: Zakat, Infaq, Sedekah, Donasi
    - Kategori pengeluaran: Operasional, Perlengkapan, Pengajian, Santunan, Pembangunan
4. **Budgeting**
    - Perencanaan anggaran per kategori
    - Monitoring realisasi vs budget
5. **Goals/Target**
    - Target pencapaian (contoh: Pembangunan Gerbang Masjid)
    - Tracking progress dengan deposit
6. **Laporan (Reports)**
    - Income vs Expense
    - Laporan per kategori (bulanan/tahunan)
    - Laporan per akun
    - Visualisasi chart dan grafik
7. **Kalender**
    - View transaksi dalam format kalender
8. **Multi-user & Role Management**
    - User management dengan role-based access
    - Granular permissions per fitur
9. **Multi-language**
    - Support bahasa Indonesia, English, Spanish, Turkish

Tech Stack Khusus :

1. decimal.js atau big.js

```jsx
const Decimal = require('decimal.js');
let total = new Decimal('1000.50').plus('500.25');
```

1. dinero.js (untuk currency)

```jsx
const price = Dinero({ amount: 100050, currency: 'IDR' });

```

Note : 

Tidak usah pake payment gateway, cukup pake pencatatan manual, tapi ada bukti transaksi berupa foto

Permission pake spatie

Multiple Masjid