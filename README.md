# Sistem Pembayaran Listrik Pascabayar

Aplikasi web manajemen tagihan listrik pascabayar berbasis industri yang tangguh, aman, dan adaptif. Sistem ini dibangun dengan memanfaatkan kekuatan framework modern **Laravel 12**, **Tailwind CSS**, dan **Alpine.js**, serta dirancang khusus untuk memfasilitasi kebutuhan administrasi internal petugas PLN sekaligus pencarian tagihan pelanggan publik yang cepat dan transparan.

---

## Daftar Isi
- [Fitur Utama Sistem](#fitur-utama-sistem)
- [Arsitektur & Keamanan](#arsitektur--keamanan)
- [Kebutuhan Sistem](#kebutuhan-sistem)
- [Panduan Pemasangan](#panduan-pemasangan)
- [Kredensial Akun Pengujian](#kredensial-akun-pengujian)
- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Lisensi](#lisensi)

---

## Fitur Utama Sistem

### 🔒 Autentikasi Modifikasi Laravel Breeze
Sistem autentikasi bawaan Laravel Breeze dimodifikasi secara mendalam untuk menggunakan `username` unik (maksimum 12 karakter) sebagai pengenal login utama menggantikan alamat email.

### 🛡️ Otorisasi Multi-Peran Terproteksi
Hak akses internal diatur secara ketat berdasarkan tingkatan `access_level` pengguna yang diproteksi oleh kombinasi kustom middleware `CheckAccessLevel` dan Laravel Policies (`BillPolicy`, `CustomerPolicy`):
- **Admin (Level 1)**: Memiliki hak akses penuh untuk melakukan semua aksi CRUD (Melihat, Menambah, Mengubah, dan Menghapus data pelanggan serta tagihan).
- **Officer (Level 2)**: Diizinkan untuk melakukan manajemen data pelanggan dan tagihan (Melihat, Menambah, dan Mengubah), tetapi **DILARANG KERAS** menghapus data apapun (`delete` terproteksi penuh di tingkat backend gate dan antarmuka visual).

### 🌓 Mesin Tema Global & Persisten
Sistem mengadopsi tema gelap (Dark Mode) dan terang (Light Mode) global yang reaktif dikendalikan oleh Alpine.js. Pilihan tema disimpan secara persisten di dalam `localStorage` browser.

### 🔍 Pencarian & Akumulasi Tunggakan Publik
Akses pencarian publik yang transparan tanpa login berbasis nomor pelanggan (`customer_number`):
- **Akumulasi Otomatis**: Menghitung secara real-time total tagihan yang belum dibayar (*unpaid*) milik pelanggan terkait melalui kueri controller.
- **Banner Kondisional Kontras Tinggi**: Menampilkan informasi total tunggakan berlatar merah pekat jika ada tunggakan (`grand_total_unpaid > 0`), atau banner sukses hijau ramah lingkungan jika semua tagihan lunas.

### 📊 Dashboard Infografis
Halaman utama petugas dilengkapi dengan panel infografis yang menyajikan statistik agregat dari database secara real-time (`total_customers`, `total_usage_kwh`, `total_bills_count`).

### ↕️ Tabel Interaktif Klik-Sortir (Sorting)
Tabel data tagihan internal mendukung pengurutan kolom secara dinamis (berdasarkan Pelanggan, Periode, Penggunaan kWh, dan Total Tagihan) dengan memanfaatkan join relasi database yang aman dan formula SQL. Fitur ini dirancang pintar agar tetap mempertahankan (*preserve*) query string pencarian aktif dan paginasi data saat pengguna mengklik header tabel.


---

## Arsitektur & Keamanan

Struktur file kunci pada aplikasi ini:
```
aplikasi-listrik/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── CustomerController.php      # CRUD data Pelanggan + Policy Gate
│   │   │   ├── BillController.php          # CRUD Tagihan + Pengurutan/Pencarian + Toggle Status
│   │   │   └── PublicBillController.php     # Pencarian tagihan publik + Akumulasi Tunggakan
│   │   ├── Middleware/
│   │   │   └── CheckAccessLevel.php        # Guard level akses (1=Admin, 2=Officer)
│   │   └── Requests/
│   │       └── Auth/LoginRequest.php       # Autentikasi modifikasi berbasis Username
│   ├── Models/
│   │   ├── Bill.php                        # Properti $fillable status & accessor virtual total_charge
│   │   ├── Customer.php                    # Relasi BelongsTo Tarif & HasMany Tagihan
│   │   └── User.php
│   └── Policies/
│       ├── BillPolicy.php                  # Aturan pembatasan aksi hapus Tagihan
│       └── CustomerPolicy.php              # Aturan pembatasan aksi hapus Pelanggan
├── database/
│   ├── migrations/
│   │   ├── 2026_07_07_130005_create_customers_table.php
│   │   └── 2026_07_07_130012_create_bills_table.php
│   └── seeders/
│       └── DatabaseSeeder.php              # Seeder data pengujian terpadu
├── resources/views/
│   ├── bills/                              # View CRUD Tagihan (index, create, edit, show)
│   ├── customers/                          # View CRUD Pelanggan (index, create, edit, show)
│   └── public_bills/                       # View Pencarian Publik (index, result)
└── routes/
    └── web.php                             # Rute resource terproteksi & publik
```

---

## Kebutuhan Sistem

Sebelum memasang aplikasi, pastikan lingkungan pengembangan/server Anda memenuhi persyaratan berikut:
- **PHP** >= 8.3
- **Composer** >= 2.x
- **Node.js** >= 20.x & **NPM**
- **Database Engine**: MariaDB >= 10.x atau MySQL >= 8.x
- **Ekstensi PHP yang Wajib Aktif**: `pdo_mysql`, `mbstring`, `openssl`, `xml`, `zip`, `bcmath`, `curl`.

---

## Panduan Pemasangan

Jalankan perintah-perintah CLI di bawah ini secara berurutan untuk memasang aplikasi pada lingkungan lokal atau server Anda:

```bash
# 1. Kloning repositori kode sumber
git clone https://github.com/nabararifki/aplikasi-listrik.git
cd aplikasi-listrik

# 2. Instal dependensi PHP pihak ketiga
composer install

# 3. Instal dependensi frontend dan kompilasi aset Tailwind CSS/Vite
npm install
npm run build

# 4. Salin file konfigurasi lingkungan
cp .env.example .env
```


```bash
# 5. Buat kunci pengaman aplikasi Laravel
php artisan key:generate

# 6. Bersihkan database, jalankan migrasi, dan isi data seeder pengujian
php artisan migrate:fresh --seed

# 7. Atur kepemilikan direktori untuk user web server (Khusus Linux Server)
sudo chown -R $USER:www-data storage bootstrap/cache

# 8. Berikan izin tulis dan eksekusi pada direktori penyimpanan
sudo chmod -R 775 storage bootstrap/cache
```

Untuk menjalankan server pengembangan internal bawaan PHP:
```bash
php artisan serve
```
Buka peramban (browser) Anda dan akses alamat `http://127.0.0.1:8000`.

---

## Kredensial Akun Pengujian

Setelah database berhasil diisi menggunakan `DatabaseSeeder`, Anda dapat menguji fungsionalitas sistem menggunakan akun pengujian bawaan berikut:

### 👥 Akun Petugas Internal
| Peran / Jabatan | Username | Password | Otorisasi Aksi |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin` | `password` | Semua Aksi CRUD (Lihat, Tambah, Ubah, Hapus Pelanggan & Tagihan) |
| **Officer / Petugas** | `officer` | `password` | Terbatas (Melihat, Menambah, dan Mengubah; **Tanpa Menghapus**) |

### 🔍 Data Pelanggan untuk Uji Cek Publik
Untuk mencoba fitur pencarian tagihan publik tanpa login pada halaman utama:
- **Nomor Pelanggan**: `CUST-001`
- **Nama Pelanggan**: Bara Rifki
- **Status Demo**: Memiliki 1 tagihan bertatus Lunas (`paid`) dan 2 tagihan bertatus Belum Lunas (`unpaid`) untuk kalkulasi simulasi banner tunggakan secara presisi.

---

## Teknologi yang Digunakan
- **Framework**: Laravel 12.x
- **Frontend Stack**: Tailwind CSS, Alpine.js v3.x, Laravel Breeze
- **Bundler**: Vite
- **Database**: MySQL / MariaDB

---

## Lisensi

Proyek ini dikembangkan secara komprehensif untuk memenuhi standar asesmen kompetensi rekayasa perangkat lunak.

---
<p align="center">Dikembangkan dengan ❤️ untuk Keamanan dan Kenyamanan Pengelolaan Tagihan Listrik Anda</p>
