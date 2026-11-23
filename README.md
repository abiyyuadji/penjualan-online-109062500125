# Laravel Penjualan Online

Aplikasi penjualan online dengan dua peran (Admin dan Pelanggan) untuk praktikum Pengembangan Aplikasi Web. Dibuat dengan Laravel 13, PHP 8.3, MySQL, Blade, dan Eloquent.

## Fitur

**Pelanggan**
- Registrasi dan login
- Katalog produk dengan pencarian, filter kategori, filter harga, stok tersedia, pengurutan, dan pagination
- Detail produk (berdasarkan slug)
- Keranjang belanja berbasis session
- Checkout dengan pilihan ekspedisi
- Riwayat dan detail pesanan

**Admin**
- Dashboard ringkasan
- Kelola kategori dan produk (termasuk unggah gambar)
- Barang masuk dan histori stok (IN dan OUT)
- Kelola layanan ekspedisi
- Daftar transaksi, validasi, pembatalan dengan pengembalian stok, dan perubahan status

**Aspek teknis**
- Akses dibatasi middleware `role:admin` dan `role:customer`
- Checkout memakai `DB::transaction()` dan `lockForUpdate()` untuk mencegah overselling
- Nama dan harga produk disimpan sebagai snapshot di `order_items`
- Alur status: pending, confirmed, processed, shipped, completed (atau cancelled)

## Kebutuhan

- PHP 8.3 atau lebih baru
- Composer
- MySQL atau MariaDB
- Git

## Instalasi

```bash
git clone <alamat-repository> penjualan-online
cd penjualan-online
composer install
copy .env.example .env
php artisan key:generate
```

Buat database `laravel_online_sales`, lalu atur bagian database di `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_online_sales
DB_USERNAME=root
DB_PASSWORD=
```

Lanjutkan:

```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
php artisan serve
```

Buka `http://127.0.0.1:8000`.

## Akun latihan (hanya untuk lokal)

| Peran | Email | Password |
|---|---|---|
| Admin | admin@example.com | Admin12345! |
| Pelanggan | abiyyutes1@gmail.com | 12345678 |
| Pelanggan | abiyyutes2@gmail.com | 87654321 |

Jangan memakai akun ini di server sungguhan.

## Panduan pengujian

1. Login admin, tambah kategori dan produk (dengan gambar), lalu catat barang masuk.
2. Login pelanggan, cari produk, tambah ke keranjang, lalu checkout.
3. Login admin, validasi pesanan, lalu ubah status sampai completed.
4. Batalkan satu pesanan pending dan pastikan stok kembali.
5. Pastikan pelanggan yang membuka `/admin` mendapat halaman 403.

## Milestone Git

| Milestone | Fokus | Tag |
|---|---|---|
| M0 | Setup proyek | m0-setup |
| M1 | Autentikasi dan role | m1-auth-role |
| M2 | Kategori dan produk | m2-master-product |
| M3 | Barang masuk, stok, ekspedisi | m3-inventory-shipping |
| M4 | Katalog toko | m4-storefront |
| M5 | Keranjang | m5-cart |
| M6 | Checkout | m6-checkout |
| M7 | Admin transaksi | m7-order-admin |
| M8 | QA dan dokumentasi | m8-final |

## Catatan

- Zona waktu bawaan Laravel adalah UTC. Untuk WIB, tambahkan `APP_TIMEZONE=Asia/Jakarta` di `.env`, lalu jalankan `php artisan config:clear`.
- Keranjang disimpan di session, sehingga kosong setelah logout.