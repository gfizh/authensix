<?php

namespace Tests\Feature;

use App\Models\AlamatPengiriman;
use App\Models\ItemPesanan;
use App\Models\Kategori;
use App\Models\Merek;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CheckoutSuccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('checkout.success'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_checkout_success_page(): void
    {
        $user = User::create([
            'nama_lengkap' => 'Budi Santoso',
            'nomor_telepon' => '081234567890',
            'username' => 'budi',
            'password_hash' => Hash::make('password'),
            'role' => 'customer',
        ]);

        $this->actingAs($user)
            ->get(route('checkout.success'))
            ->assertOk()
            ->assertSee('Transaksi')
            ->assertSee('Berhasil !')
            ->assertSee('No. invoice')
            ->assertSee('Tanggal')
            ->assertSee('Metode Bayar')
            ->assertSee('Lunas')
            ->assertSee('Estimasi pengiriman:')
            ->assertSee('No. resi akan dikirim via email')
            ->assertSee('Terimakasih telah berbelanja di Authen')
            ->assertSee('Kembali ke katalog');
    }

    public function test_checkout_success_page_shows_order_details(): void
    {
        $user = User::create([
            'nama_lengkap' => 'Ahmad Hafizh',
            'nomor_telepon' => '081298765432',
            'username' => 'ahmad',
            'password_hash' => Hash::make('password'),
            'role' => 'customer',
        ]);

        $category = Kategori::create([
            'nama_kategori' => 'Kartu Grafis',
            'slug' => 'kartu-grafis',
            'deskripsi' => 'GPU',
        ]);

        $merek = Merek::create([
            'nama_merek' => 'Gigabyte',
            'logo_url' => '',
        ]);

        $product = Produk::create([
            'id_kategori' => $category->id_kategori,
            'id_merek' => $merek->id_merek,
            'sku' => 'GV-N5060TEI-16GD',
            'nama_produk' => 'GIGABYTE VGA NVIDIA GEFORCE RTX 5060 TI EAGLE ICE 16GB GDDR7 OC WHITE',
            'tipe_produk' => 'regular',
            'harga' => 34430000,
            'stok' => 5,
            'jumlah_terjual' => 1,
            'kondisi' => 'baru',
            'masa_garansi_bulan' => 36,
            'deskripsi' => 'VGA RTX 5060 Ti White Edition',
            'gambar_url' => '',
            'rating_rata_rata' => 5.0,
            'is_active' => true,
        ]);

        $alamat = AlamatPengiriman::create([
            'id_user' => $user->id_user,
            'label_alamat' => 'Rumah',
            'nama_penerima' => 'Ahmad Hafizh',
            'nomor_telepon' => '081298765432',
            'alamat_lengkap' => 'Jl. Kebon Jeruk No. 5',
            'kota' => 'Jakarta Barat',
            'provinsi' => 'DKI Jakarta',
            'kode_pos' => '11530',
            'is_primary' => true,
        ]);

        $order = Pesanan::create([
            'nomor_pesanan' => 'ORD-20261027-9999',
            'id_user' => $user->id_user,
            'id_alamat' => $alamat->id_alamat,
            'subtotal' => 34430000,
            'biaya_pengiriman' => 0,
            'pajak' => 0,
            'total_pembayaran' => 34430000,
            'status_pemesanan' => 'selesai',
            'tanggal_pesanan' => now(),
        ]);

        ItemPesanan::create([
            'id_pesanan' => $order->id_pesanan,
            'id_produk' => $product->id_produk,
            'jumlah' => 1,
            'harga_satuan' => 34430000,
            'subtotal_item' => 34430000,
        ]);

        $this->actingAs($user)
            ->get(route('checkout.success', ['order_id' => $order->id_pesanan]))
            ->assertOk()
            ->assertSee('GIGABYTE VGA NVIDIA GEFORCE RTX 5060 TI EAGLE ICE 16GB GDDR7 OC WHITE')
            ->assertSee('Rp34.430.000')
            ->assertSee('INV-20261027-9999')
            ->assertSee('Lunas');
    }
}
