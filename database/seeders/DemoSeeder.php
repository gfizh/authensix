<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */

        DB::table('users')->insert([
            [
                'id_user' => 1,
                'nama_lengkap' => 'Ahmad Hafizh',
                'nomor_telepon' => '081234567890',
                'username' => 'ahmad',
                'password_hash' => Hash::make('password'),
                'role' => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 2,
                'nama_lengkap' => 'Budi Santoso',
                'nomor_telepon' => '081298765432',
                'username' => 'budi',
                'password_hash' => Hash::make('password'),
                'role' => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 3,
                'nama_lengkap' => 'Admin Store',
                'nomor_telepon' => '081111111111',
                'username' => 'admin',
                'password_hash' => Hash::make('admin123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | KATEGORI
        |--------------------------------------------------------------------------
        */

        DB::table('kategori')->insert([
            [
                'id_kategori' => 1,
                'nama_kategori' => 'Processor',
                'slug' => 'processor',
                'deskripsi' => 'Processor untuk komputer dan laptop.',
            ],
            [
                'id_kategori' => 2,
                'nama_kategori' => 'Kartu Grafis',
                'slug' => 'kartu-grafis',
                'deskripsi' => 'GPU dan kartu grafis untuk gaming dan workstation.',
            ],
            [
                'id_kategori' => 3,
                'nama_kategori' => 'RAM',
                'slug' => 'ram',
                'deskripsi' => 'Memory RAM untuk komputer.',
            ],
            [
                'id_kategori' => 4,
                'nama_kategori' => 'SSD',
                'slug' => 'ssd',
                'deskripsi' => 'Media penyimpanan SSD.',
            ],
            [
                'id_kategori' => 5,
                'nama_kategori' => 'Motherboard',
                'slug' => 'motherboard',
                'deskripsi' => 'Motherboard untuk berbagai platform processor.',
            ],
            [
                'id_kategori' => 6,
                'nama_kategori' => 'PC Prebuild',
                'slug' => 'pc-prebuild',
                'deskripsi' => 'PC rakitan siap digunakan.',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | MEREK
        |--------------------------------------------------------------------------
        */

        DB::table('merek')->insert([
            [
                'id_merek' => 1,
                'nama_merek' => 'AMD',
                'logo_url' => 'https://example.com/logo/amd.png',
            ],
            [
                'id_merek' => 2,
                'nama_merek' => 'Intel',
                'logo_url' => 'https://example.com/logo/intel.png',
            ],
            [
                'id_merek' => 3,
                'nama_merek' => 'NVIDIA',
                'logo_url' => 'https://example.com/logo/nvidia.png',
            ],
            [
                'id_merek' => 4,
                'nama_merek' => 'Kingston',
                'logo_url' => 'https://example.com/logo/kingston.png',
            ],
            [
                'id_merek' => 5,
                'nama_merek' => 'Samsung',
                'logo_url' => 'https://example.com/logo/samsung.png',
            ],
            [
                'id_merek' => 6,
                'nama_merek' => 'ASUS',
                'logo_url' => 'https://example.com/logo/asus.png',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | ALAMAT PENGIRIMAN
        |--------------------------------------------------------------------------
        */

        DB::table('alamat_pengiriman')->insert([
            [
                'id_alamat' => 1,
                'id_user' => 1,
                'label_alamat' => 'Rumah',
                'nama_penerima' => 'Ahmad Hafizh',
                'nomor_telepon' => '081234567890',
                'alamat_lengkap' => 'Jl. Contoh No. 10',
                'kota' => 'Depok',
                'provinsi' => 'Jawa Barat',
                'kode_pos' => '16451',
                'is_primary' => true,
            ],
            [
                'id_alamat' => 2,
                'id_user' => 1,
                'label_alamat' => 'Sekolah',
                'nama_penerima' => 'Ahmad Hafizh',
                'nomor_telepon' => '081234567890',
                'alamat_lengkap' => 'Jl. Pendidikan No. 20',
                'kota' => 'Depok',
                'provinsi' => 'Jawa Barat',
                'kode_pos' => '16452',
                'is_primary' => false,
            ],
            [
                'id_alamat' => 3,
                'id_user' => 2,
                'label_alamat' => 'Rumah',
                'nama_penerima' => 'Budi Santoso',
                'nomor_telepon' => '081298765432',
                'alamat_lengkap' => 'Jl. Merdeka No. 15',
                'kota' => 'Bogor',
                'provinsi' => 'Jawa Barat',
                'kode_pos' => '16111',
                'is_primary' => true,
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | PRODUK
        |--------------------------------------------------------------------------
        */

        DB::table('produk')->insert([
            [
                'id_produk' => 1,
                'id_kategori' => 1,
                'id_merek' => 1,
                'sku' => 'AMD-R5-5600',
                'nama_produk' => 'AMD Ryzen 5 5600',
                'tipe_produk' => 'regular',
                'harga' => 1650000,
                'stok' => 20,
                'jumlah_terjual' => 35,
                'kondisi' => 'baru',
                'masa_garansi_bulan' => 36,
                'deskripsi' => 'Processor AMD Ryzen 5 5600 untuk gaming dan produktivitas.',
                'gambar_url' => 'https://example.com/products/ryzen-5-5600.jpg',
                'rating_rata_rata' => 4.80,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_produk' => 2,
                'id_kategori' => 2,
                'id_merek' => 3,
                'sku' => 'NV-RTX-4060',
                'nama_produk' => 'NVIDIA GeForce RTX 4060',
                'tipe_produk' => 'regular',
                'harga' => 4800000,
                'stok' => 10,
                'jumlah_terjual' => 21,
                'kondisi' => 'baru',
                'masa_garansi_bulan' => 36,
                'deskripsi' => 'Kartu grafis NVIDIA untuk gaming dan content creation.',
                'gambar_url' => 'https://example.com/products/rtx-4060.jpg',
                'rating_rata_rata' => 4.70,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_produk' => 3,
                'id_kategori' => 3,
                'id_merek' => 4,
                'sku' => 'KNG-16GB-DDR4',
                'nama_produk' => 'Kingston Fury 16GB DDR4',
                'tipe_produk' => 'regular',
                'harga' => 650000,
                'stok' => 30,
                'jumlah_terjual' => 50,
                'kondisi' => 'baru',
                'masa_garansi_bulan' => 12,
                'deskripsi' => 'RAM DDR4 16GB untuk desktop.',
                'gambar_url' => 'https://example.com/products/kingston-16gb.jpg',
                'rating_rata_rata' => 4.60,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_produk' => 4,
                'id_kategori' => 4,
                'id_merek' => 5,
                'sku' => 'SMS-980-1TB',
                'nama_produk' => 'Samsung SSD 980 1TB',
                'tipe_produk' => 'regular',
                'harga' => 1100000,
                'stok' => 15,
                'jumlah_terjual' => 28,
                'kondisi' => 'baru',
                'masa_garansi_bulan' => 60,
                'deskripsi' => 'SSD NVMe M.2 kapasitas 1TB.',
                'gambar_url' => 'https://example.com/products/samsung-980.jpg',
                'rating_rata_rata' => 4.90,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_produk' => 5,
                'id_kategori' => 5,
                'id_merek' => 6,
                'sku' => 'ASUS-B550M',
                'nama_produk' => 'ASUS Prime B550M-A',
                'tipe_produk' => 'regular',
                'harga' => 1850000,
                'stok' => 12,
                'jumlah_terjual' => 18,
                'kondisi' => 'baru',
                'masa_garansi_bulan' => 36,
                'deskripsi' => 'Motherboard AM4 chipset B550.',
                'gambar_url' => 'https://example.com/products/asus-b550m.jpg',
                'rating_rata_rata' => 4.70,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_produk' => 6,
                'id_kategori' => 6,
                'id_merek' => 6,
                'sku' => 'PC-GAMING-001',
                'nama_produk' => 'Gaming PC Ryzen 5 RTX 4060',
                'tipe_produk' => 'prebuild',
                'harga' => 12500000,
                'stok' => 5,
                'jumlah_terjual' => 8,
                'kondisi' => 'baru',
                'masa_garansi_bulan' => 12,
                'deskripsi' => 'PC gaming prebuild dengan Ryzen 5 dan RTX 4060.',
                'gambar_url' => 'https://example.com/products/gaming-pc.jpg',
                'rating_rata_rata' => 4.90,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | SPESIFIKASI PRODUK
        |--------------------------------------------------------------------------
        */

        DB::table('spesifikasi_produk')->insert([
            [
                'id_spesifikasi' => 1,
                'id_produk' => 1,
                'nama_atribut' => 'Core',
                'nilai_atribut' => '6 Core / 12 Thread',
            ],
            [
                'id_spesifikasi' => 2,
                'id_produk' => 1,
                'nama_atribut' => 'Socket',
                'nilai_atribut' => 'AM4',
            ],
            [
                'id_spesifikasi' => 3,
                'id_produk' => 2,
                'nama_atribut' => 'VRAM',
                'nilai_atribut' => '8GB GDDR6',
            ],
            [
                'id_spesifikasi' => 4,
                'id_produk' => 3,
                'nama_atribut' => 'Kapasitas',
                'nilai_atribut' => '16GB',
            ],
            [
                'id_spesifikasi' => 5,
                'id_produk' => 4,
                'nama_atribut' => 'Kapasitas',
                'nilai_atribut' => '1TB',
            ],
            [
                'id_spesifikasi' => 6,
                'id_produk' => 5,
                'nama_atribut' => 'Socket',
                'nilai_atribut' => 'AM4',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | KERANJANG
        |--------------------------------------------------------------------------
        */

        DB::table('keranjang')->insert([
            [
                'id_keranjang' => 1,
                'id_user' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_keranjang' => 2,
                'id_user' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | ITEM KERANJANG
        |--------------------------------------------------------------------------
        */

        DB::table('item_keranjang')->insert([
            [
                'id_item_keranjang' => 1,
                'id_keranjang' => 1,
                'id_produk' => 3,
                'jumlah' => 2,
                'harga_satuan' => 650000,
            ],
            [
                'id_item_keranjang' => 2,
                'id_keranjang' => 1,
                'id_produk' => 4,
                'jumlah' => 1,
                'harga_satuan' => 1100000,
            ],
            [
                'id_item_keranjang' => 3,
                'id_keranjang' => 2,
                'id_produk' => 1,
                'jumlah' => 1,
                'harga_satuan' => 1650000,
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | PESANAN
        |--------------------------------------------------------------------------
        */

        DB::table('pesanan')->insert([
            [
                'id_pesanan' => 1,
                'nomor_pesanan' => 'ORD-20261001-001',
                'id_user' => 1,
                'id_alamat' => 1,
                'subtotal' => 6450000,
                'biaya_pengiriman' => 25000,
                'pajak' => 645000,
                'total_pembayaran' => 7120000,
                'status_pemesanan' => 'selesai',
                'tanggal_pesanan' => '2026-10-01 10:30:00',
            ],
            [
                'id_pesanan' => 2,
                'nomor_pesanan' => 'ORD-20261002-002',
                'id_user' => 2,
                'id_alamat' => 3,
                'subtotal' => 1650000,
                'biaya_pengiriman' => 20000,
                'pajak' => 165000,
                'total_pembayaran' => 1835000,
                'status_pemesanan' => 'dikirim',
                'tanggal_pesanan' => '2026-10-02 14:15:00',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | ITEM PESANAN
        |--------------------------------------------------------------------------
        */

        DB::table('item_pesanan')->insert([
            [
                'id_item_pesanan' => 1,
                'id_pesanan' => 1,
                'id_produk' => 1,
                'jumlah' => 1,
                'harga_satuan' => 1650000,
                'subtotal_item' => 1650000,
            ],
            [
                'id_item_pesanan' => 2,
                'id_pesanan' => 1,
                'id_produk' => 2,
                'jumlah' => 1,
                'harga_satuan' => 4800000,
                'subtotal_item' => 4800000,
            ],
            [
                'id_item_pesanan' => 3,
                'id_pesanan' => 2,
                'id_produk' => 1,
                'jumlah' => 1,
                'harga_satuan' => 1650000,
                'subtotal_item' => 1650000,
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | PEMBAYARAN
        |--------------------------------------------------------------------------
        */

        DB::table('pembayaran')->insert([
            [
                'id_pembayaran' => 1,
                'id_pesanan' => 1,
                'metode_pembayaran' => 'bank_transfer',
                'status_pembayaran' => 'dibayar',
                'jumlah_bayar' => 7120000,
                'kode_transaksi_gateway' => 'TRX-001',
                'tanggal_pembayaran' => '2026-10-01 10:45:00',
                'batas_waktu_pembayaran' => '2026-10-01 23:59:59',
            ],
            [
                'id_pembayaran' => 2,
                'id_pesanan' => 2,
                'metode_pembayaran' => 'e_wallet',
                'status_pembayaran' => 'dibayar',
                'jumlah_bayar' => 1835000,
                'kode_transaksi_gateway' => 'TRX-002',
                'tanggal_pembayaran' => '2026-10-02 14:30:00',
                'batas_waktu_pembayaran' => '2026-10-02 23:59:59',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | INVOICE
        |--------------------------------------------------------------------------
        */

        DB::table('invoice')->insert([
            [
                'id_invoice' => 1,
                'id_pesanan' => 1,
                'nomor_invoice' => 'INV-20261001-001',
                'url_pdf_invoice' => 'invoices/INV-20261001-001.pdf',
                'tanggal_terbit' => '2026-10-01 11:00:00',
            ],
            [
                'id_invoice' => 2,
                'id_pesanan' => 2,
                'nomor_invoice' => 'INV-20261002-002',
                'url_pdf_invoice' => 'invoices/INV-20261002-002.pdf',
                'tanggal_terbit' => '2026-10-02 15:00:00',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | PENGIRIMAN
        |--------------------------------------------------------------------------
        */

        DB::table('pengiriman')->insert([
            [
                'id_pengiriman' => 1,
                'id_pesanan' => 1,
                'kurir' => 'JNE',
                'layanan' => 'REG',
                'nomor_resi' => 'JNE123456789',
                'status_pengiriman' => 'diterima',
                'tanggal_pengiriman' => '2026-10-02 09:00:00',
                'tanggal_diterima' => '2026-10-04 15:00:00',
            ],
            [
                'id_pengiriman' => 2,
                'id_pesanan' => 2,
                'kurir' => 'SiCepat',
                'layanan' => 'REG',
                'nomor_resi' => 'SCP987654321',
                'status_pengiriman' => 'dikirim',
                'tanggal_pengiriman' => '2026-10-03 10:00:00',
                'tanggal_diterima' => null,
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | ULASAN
        |--------------------------------------------------------------------------
        */

        DB::table('ulasan')->insert([
            [
                'id_ulasan' => 1,
                'id_user' => 1,
                'id_produk' => 1,
                'rating' => 5,
                'komentar' => 'Processor bekerja dengan sangat baik untuk gaming dan multitasking.',
                'created_at' => '2026-10-05 10:00:00',
            ],
            [
                'id_ulasan' => 2,
                'id_user' => 1,
                'id_produk' => 2,
                'rating' => 5,
                'komentar' => 'Performa gaming sangat bagus.',
                'created_at' => '2026-10-05 10:05:00',
            ],
            [
                'id_ulasan' => 3,
                'id_user' => 2,
                'id_produk' => 1,
                'rating' => 4,
                'komentar' => 'Performanya bagus dan sesuai dengan harga.',
                'created_at' => '2026-10-05 11:00:00',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | PREBUILD KOMPONEN
        |--------------------------------------------------------------------------
        */

        DB::table('prebuild_komponen')->insert([
            [
                'id_prebuild_komponen' => 1,
                'id_produk_parent' => 6,
                'id_produk_child' => 1,
                'jumlah' => 1,
            ],
            [
                'id_prebuild_komponen' => 2,
                'id_produk_parent' => 6,
                'id_produk_child' => 2,
                'jumlah' => 1,
            ],
            [
                'id_prebuild_komponen' => 3,
                'id_produk_parent' => 6,
                'id_produk_child' => 3,
                'jumlah' => 2,
            ],
            [
                'id_prebuild_komponen' => 4,
                'id_produk_parent' => 6,
                'id_produk_child' => 4,
                'jumlah' => 1,
            ],
            [
                'id_prebuild_komponen' => 5,
                'id_produk_parent' => 6,
                'id_produk_child' => 5,
                'jumlah' => 1,
            ],
        ]);
    }
}