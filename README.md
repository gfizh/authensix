# AuthenSIX Computer

**AuthenSIX Computer** adalah aplikasi web e-commerce komponen PC, perangkat keras (*hardware*), dan PC *prebuild* berbasis **Laravel** dengan dukungan manajemen multi-role (**Customer**, **Admin**, dan **Super Admin**).

---

## Fitur Utama Berdasarkan Role

### 1. Customer (`customer`)
- **Katalog & Pencarian Produk (`/dashboard`, `/kategori`):** Menjelajahi produk aktif berdasarkan kategori, mencari produk menggunakan kata kunci nama produk maupun kategori, dan melihat rating serta jumlah terjual.
- **Detail Produk (`/produk/{id}`):** Menampilkan galeri foto, harga, SKU, deskripsi, garansi, spesifikasi teknis, stok *real-time*, tombol **+ Keranjang** (dengan pembaruan badge keranjang di navbar), dan **Beli Sekarang**.
- **Keranjang Belanja (`/keranjang`):** Terintegrasi langsung dengan tabel `keranjang` dan `item_keranjang` di database:
  - Menambah produk ke keranjang beserta akumulasi kuantitas dan validasi batas stok.
  - Mengubah kuantitas item (`+` / `−` atau input angka langsung), menghapus item satuan, atau mengosongkan keranjang.
  - Memilih item tertentu (*partial checkout*) sebelum melanjutkan ke halaman checkout.
- **Checkout & Alamat Pengiriman (`/checkout`, `/checkout/berhasil`):** Pengisian/pemilihan alamat pengiriman, pilihan metode pengiriman (**JNE Reguler**, **JNE Yes**, **JNE OKE Express**), pembuatan pesanan (`Pesanan`), serta halaman ringkasan transaksi berhasil & nomor invoice.
- **Riwayat Pesanan (`/riwayat-pesanan`):** Memantau status pesanan (*pending*, *diproses*, *dikirim*, *selesai*, *dibatalkan*), melihat nomor resi pengiriman, dan membatalkan pesanan berstatus `pending` yang otomatis mengembalikan stok produk.

### 2. Admin (`admin`)
- **Dashboard Admin (`/admin/dashboard-admin`):** Ringkasan metrik operasional toko (total pendapatan, produk aktif, pesanan masuk, dan data pelanggan).
- **Manajemen Pesanan (`/admin/manajemen_produk`):** Memantau seluruh pesanan pelanggan, memproses status pesanan, dan menyetujui pengiriman (**ACC Dikirim**) beserta input kurir, layanan, dan nomor resi yang langsung tersinkron ke halaman riwayat pesanan customer.
- **Manajemen Produk (`/admin/manajemen_produk?tab=produk`, `/admin/tambah_produk`):**
  - Menambah produk baru beserta kategori, merek, harga, stok, deskripsi, dan foto produk.
  - Filter status (**Semua**, **Aktif**, **Nonaktif**) dan pengurutan dua arah berdasarkan **Harga**, **Nama**, dan **Terakhir Diperbarui**.
  - Mengaktifkan/menonaktifkan produk untuk menyembunyikan atau menampilkan kembali di katalog customer.
  - Edit produk dilengkapi *pop-out card* konfirmasi perubahan dengan *dirty-checking* (tombol Edit dinonaktifkan saat produk berstatus nonaktif).
  - Hapus produk dengan *pop-out card* konfirmasi yang hanya aktif apabila produk telah dinonaktifkan terlebih dahulu.

### 3. Super Admin (`superadmin`)
- **Dashboard Super Admin (`/superadmin/dashboard`):** Statistik total akun, akun aktif vs nonaktif, aktivitas hari ini (*berhasil* / *gagal*), grafik tren aktivitas 7 hari terakhir, komposisi akun per role, serta daftar aktivitas terbaru.
- **Buat & Kelola Akun (`/superadmin/buat-akun`):**
  - Pembuatan akun baru untuk role **Customer**, **Admin**, atau **Super Admin** lengkap dengan email, username, status akun, dan opsi wajib ganti kata sandi.
  - Panel pratinjau hak akses dinamis berdasarkan role yang dipilih.
  - *Pop-out card* konfirmasi sebelum menyimpan akun baru; akun yang dibuat otomatis muncul di daftar **Quick Login** halaman `/login`.
- **Action Log (`/superadmin/action-log`):** Pencatatan otomatis aktivitas autentikasi (login berhasil/ditolak, logout) dan manajemen akun secara *real-time* (tanpa mock data), dilengkapi pencarian, filter rentang tanggal, pengguna, modul, status, paginasi, dan **Ekspor CSV** (`/superadmin/action-logs/export`).

---

## Teknologi yang Digunakan

- **Backend:** PHP `^8.3`, Laravel Framework `^13.17`, Laravel Sanctum `^4.0`
- **Database:** MySQL (konfigurasi lokal `authensix_db`) & SQLite `:memory:` (untuk automated testing PHPUnit)
- **Frontend:** Blade Templates, CSS, Vanilla JavaScript, Vite `^8.0`, Tailwind CSS Vite Plugin `^4.0`
- **Testing & Code Quality:** PHPUnit `^12.5`, Laravel Pint `^1.27`

---

## Prasyarat Sistem

Sebelum melakukan instalasi secara lokal, pastikan lingkungan pengembangan Anda memiliki:
- **PHP** `>= 8.3` (ekstensi standar Laravel: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `tokenizer`, `xml`, `ctype`, `json`)
- **Composer**
- **Node.js & NPM**
- **MySQL** `8.0+` (disarankan menggunakan **Laragon** di Windows)

---

## Panduan Instalasi & Konfigurasi Lokal

### 1. Siapkan Database MySQL
Buat database kosong bernama `authensix_db` melalui Laragon (**Database / HeidiSQL**), phpMyAdmin, atau terminal MySQL:
```sql
CREATE DATABASE authensix_db;
```

### 2. Opsi Cepat (Menggunakan Script Composer)
Jika database `authensix_db` sudah dibuat dan konfigurasi MySQL lokal sesuai default Laragon (`root` tanpa password):
```bash
cd authensix
composer run setup
php artisan db:seed
php artisan storage:link
php artisan serve
```

### 3. Langkah Manual Step-by-Step

1. **Masuk ke direktori proyek:**
   ```bash
   cd authensix
   ```

2. **Install dependensi PHP dan JavaScript:**
   ```bash
   composer install
   npm install
   ```

3. **Salin file environment & buat APP_KEY:**
   - **Windows PowerShell:**
     ```powershell
     Copy-Item .env.example .env
     ```
   - **Git Bash / Linux / macOS:**
     ```bash
     cp .env.example .env
     ```
   Lalu generate application key:
   ```bash
   php artisan key:generate
   ```

4. **Pastikan konfigurasi `.env` sesuai:**
   File `.env.example` sudah disesuaikan dengan proyek ini. Pastikan bagian berikut di `.env` sudah benar:
   ```env
   APP_NAME=Authensix
   APP_URL=http://127.0.0.1:8000

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=authensix_db
   DB_USERNAME=root
   DB_PASSWORD=

   SESSION_DRIVER=file
   QUEUE_CONNECTION=sync
   CACHE_STORE=file
   ```

5. **Jalankan migrasi dan seeder database:**
   ```bash
   php artisan migrate --seed
   ```
   Perintah `--seed` menjalankan `DatabaseSeeder` yang memanggil `DemoSeeder` untuk mengisi data awal pengguna, kategori, merek, produk, keranjang, dan contoh pesanan.
   > Jika ingin mereset database ke kondisi awal (*clean seed*), jalankan:
   > ```bash
   > php artisan migrate:fresh --seed
   > ```

6. **Buat symbolic link untuk penyimpanan gambar produk:**
   ```bash
   php artisan storage:link
   ```

7. **Build aset frontend & jalankan server lokal:**
   ```bash
   npm run build
   php artisan serve
   ```
   Aplikasi dapat diakses di `http://127.0.0.1:8000` (atau melalui domain Laragon lokal seperti `http://authensix.test`). Untuk mode pengembangan dengan hot-reload Vite, jalankan `npm run dev` di terminal terpisah.

---

## Troubleshooting Umum

- **Error `Table 'authensix_db.sessions' doesn't exist`:**
  Proyek ini menggunakan driver file untuk sesi dan cache (tidak memakai tabel `sessions`). Pastikan di `.env` tertulis `SESSION_DRIVER=file`, `CACHE_STORE=file`, dan `QUEUE_CONNECTION=sync`, lalu jalankan:
  ```bash
  php artisan optimize:clear
  ```
- **Gambar produk tidak muncul (`404` pada `/storage/...`):**
  Pastikan symbolic link penyimpanan publik sudah dibuat dengan menjalankan:
  ```bash
  php artisan storage:link
  ```
- **Error `Vite manifest not found`:**
  Aset CSS/JS belum di-build. Jalankan `npm run build` (atau `npm run dev` saat development).
- **Perubahan konfigurasi `.env` atau route belum terbaca:**
  Bersihkan cache aplikasi dengan menjalankan:
  ```bash
  php artisan optimize:clear
  ```

---

## Akun Demo (Quick Login)

Halaman `/login` menyediakan daftar tombol **Quick Login** dinamis yang dimuat langsung dari tabel `users` di database. Akun bawaan hasil `DemoSeeder`:

| Role | Nama Lengkap | Username | Password |
| :--- | :--- | :--- | :--- |
| **Super Admin** | GURUH | `guruhhhh` | `superadmin123` |
| **Admin** | Admin Store | `admin` | `admin123` |
| **Customer** | Ahmad Hafizh | `ahmad` | `password` |
| **Customer** | Budi Santoso | `budi` | `password` |

---

## Struktur Direktori & Endpoint Utama

### Struktur Direktori Penting
```text
authensix/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── CartController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── LoginController.php
│   │   │   ├── OrderHistoryController.php
│   │   │   ├── Admin/          # Dashboard, Product, Order, Category, Brand, User, Shipment, Payment, Review
│   │   │   ├── Api/            # REST API (Sanctum) untuk produk, keranjang, alamat, checkout, pesanan, ulasan
│   │   │   └── SuperAdmin/     # SuperAdminDashboardController, SuperAdminUserController, SuperAdminActionLogController, SuperAdminTransactionController
│   │   └── Middleware/         # EnsureUserIsAdmin, EnsureUserIsSuperAdmin
│   └── Models/                 # User, Produk, Kategori, Merek, Keranjang, ItemKeranjang, Pesanan, ItemPesanan, Pembayaran, Invoice, Pengiriman, AlamatPengiriman, Ulasan, ActionLog
├── database/
│   ├── migrations/
│   └── seeders/                # DatabaseSeeder, DemoSeeder
├── resources/
│   └── views/
│       ├── login.blade.php
│       ├── body/               # navbar.blade.php
│       ├── users/              # dashboard, kategori, detail-produk, cart, checkout, checkout-berhasil, riwayat-pesanan
│       ├── admin/              # dashboard-admin, manajemen_produk, tambah_produk, partials/sidebar
│       └── super_admin/        # dashboard, buat_akun, action_log, partials/sidebar, partials/top_header
├── routes/
│   ├── web.php
│   └── api.php
└── tests/
    └── Feature/                # CartSystemTest, OrderFlowTest, CheckoutSuccessTest, SuperAdminIntegrationTest
```

### Ringkasan Rute Utama
- **Autentikasi & Publik:** `GET /login`, `POST /login`, `POST /logout`
- **Customer Web (`auth`):**
  - `GET /dashboard`, `GET /kategori`, `GET /produk/{id}`
  - `GET /keranjang`, `POST /keranjang`, `PATCH /keranjang/{id}`, `DELETE /keranjang/{id}`, `DELETE /keranjang`
  - `GET /checkout`, `GET /checkout/berhasil`
  - `GET /riwayat-pesanan`, `PATCH /riwayat-pesanan/{pesanan}/batal`
- **Admin Web (`auth`, `admin`):**
  - `GET /admin/dashboard-admin`, `GET /admin/manajemen_produk`, `GET /admin/tambah_produk`
  - Resource `products`, `categories`, `brands`, `orders`, `users`, `shipments`, `reviews`, `payments`
- **Super Admin Web (`auth`, `superadmin`):**
  - `GET /superadmin/dashboard` *(mendukung tampilan Blade & JSON API)*
  - `GET /superadmin/buat-akun`, `POST /superadmin/users`, `GET /superadmin/users`, `PUT /superadmin/users/{user}`, `DELETE /superadmin/users/{user}`
  - `GET /superadmin/action-log`, `GET /superadmin/action-logs/export`
  - `GET /superadmin/transactions`, `GET /superadmin/transactions/{pesanan}`
- **REST API (`/api`):**
  - Publik: `POST /api/login`, `GET /api/products`, `GET /api/products/{id}`, `GET /api/categories`, `GET /api/brands`, `GET /api/products/{productId}/reviews`
  - Terproteksi (`auth:sanctum`): `/api/cart`, `/api/cart/items`, `/api/addresses`, `/api/checkout`, `/api/orders`, `/api/orders/{orderId}/payment`, `/api/orders/{orderId}/invoice`, `/api/orders/{orderId}/shipping`

---

## Menjalankan Pengujian & Code Formatting

Pengujian fitur berjalan di SQLite `:memory:` sesuai konfigurasi `phpunit.xml`:

```bash
# Jalankan test suite utama
php artisan test tests/Feature/CartSystemTest.php tests/Feature/OrderFlowTest.php tests/Feature/CheckoutSuccessTest.php tests/Feature/SuperAdminIntegrationTest.php

# Cek atau perbaiki style kode PHP dengan Laravel Pint
vendor/bin/pint --test
```
