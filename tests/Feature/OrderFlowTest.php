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

class OrderFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_view_order_history_with_products_and_total_price(): void
    {
        [$customer, $order, $product] = $this->createCustomerOrderFixture('pending');

        $this->actingAs($customer)
            ->get(route('riwayat-pesanan'))
            ->assertOk()
            ->assertSee('Riwayat Pesanan Saya')
            ->assertSee($order->nomor_pesanan)
            ->assertSee($product->nama_produk)
            ->assertSee('Rp 2.025.000')
            ->assertSee('Batalkan Pesanan');
    }

    public function test_customer_can_cancel_pending_order_and_restore_stock(): void
    {
        [$customer, $order, $product] = $this->createCustomerOrderFixture('pending');
        $initialStock = $product->stok;
        $initialSold = $product->jumlah_terjual;

        $this->actingAs($customer)
            ->from(route('riwayat-pesanan'))
            ->patch(route('riwayat-pesanan.cancel', $order->id_pesanan))
            ->assertRedirect(route('riwayat-pesanan'));

        $this->assertDatabaseHas('pesanan', [
            'id_pesanan' => $order->id_pesanan,
            'status_pemesanan' => 'dibatalkan',
        ]);

        $this->assertDatabaseHas('produk', [
            'id_produk' => $product->id_produk,
            'stok' => $initialStock + 2,
            'jumlah_terjual' => max(0, $initialSold - 2),
        ]);
    }

    public function test_customer_cannot_cancel_another_customers_order_or_shipped_order(): void
    {
        [$customer, $order] = $this->createCustomerOrderFixture('pending');
        $otherCustomer = User::create([
            'nama_lengkap' => 'Pengguna Lain',
            'nomor_telepon' => '089999999999',
            'username' => 'otheruser',
            'password_hash' => Hash::make('password'),
            'role' => 'customer',
        ]);

        $this->actingAs($otherCustomer)
            ->patch(route('riwayat-pesanan.cancel', $order->id_pesanan))
            ->assertForbidden();

        $order->update(['status_pemesanan' => 'dikirim']);

        $this->actingAs($customer)
            ->from(route('riwayat-pesanan'))
            ->patch(route('riwayat-pesanan.cancel', $order->id_pesanan))
            ->assertRedirect(route('riwayat-pesanan'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('pesanan', [
            'id_pesanan' => $order->id_pesanan,
            'status_pemesanan' => 'dikirim',
        ]);
    }

    public function test_admin_can_view_all_orders_and_approve_dikirim_synced_to_customer(): void
    {
        $admin = $this->createAdmin();
        [$customer, $order, $product] = $this->createCustomerOrderFixture('pending');

        $this->actingAs($admin)
            ->get(route('admin.manajemen_produk'))
            ->assertOk()
            ->assertSee('Manajemen Pesanan')
            ->assertSee($order->nomor_pesanan)
            ->assertSee($customer->nama_lengkap)
            ->assertSee($product->nama_produk)
            ->assertSee('ACC Dikirim');

        $this->actingAs($admin)
            ->from(route('admin.manajemen_produk'))
            ->patch(route('admin.orders.status', $order->id_pesanan), [
                'status_pemesanan' => 'dikirim',
                'kurir' => 'JNE',
                'layanan' => 'YES',
                'nomor_resi' => 'JNE-ACC-001',
            ])
            ->assertRedirect(route('admin.manajemen_produk'));

        $this->assertDatabaseHas('pesanan', [
            'id_pesanan' => $order->id_pesanan,
            'status_pemesanan' => 'dikirim',
        ]);

        $this->assertDatabaseHas('pengiriman', [
            'id_pesanan' => $order->id_pesanan,
            'kurir' => 'JNE',
            'nomor_resi' => 'JNE-ACC-001',
            'status_pengiriman' => 'dikirim',
        ]);

        // Kostumer melihat status sudah Dikirim beserta nomor resi & tidak bisa batal lagi.
        $this->actingAs($customer)
            ->get(route('riwayat-pesanan'))
            ->assertOk()
            ->assertSee('Dikirim')
            ->assertSee('JNE-ACC-001')
            ->assertDontSee('Batalkan Pesanan');
    }

    public function test_admin_cannot_ship_order_cancelled_by_customer_and_can_view_products_tab(): void
    {
        $admin = $this->createAdmin();
        [$customer, $order, $product] = $this->createCustomerOrderFixture('pending');

        $this->actingAs($customer)
            ->patch(route('riwayat-pesanan.cancel', $order->id_pesanan));

        $this->actingAs($admin)
            ->from(route('admin.manajemen_produk'))
            ->patch(route('admin.orders.status', $order->id_pesanan), [
                'status_pemesanan' => 'dikirim',
            ])
            ->assertRedirect(route('admin.manajemen_produk'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('pesanan', [
            'id_pesanan' => $order->id_pesanan,
            'status_pemesanan' => 'dibatalkan',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.manajemen_produk', ['tab' => 'produk']))
            ->assertOk()
            ->assertSee('Manajemen Produk')
            ->assertSee($product->nama_produk);

        $this->actingAs($customer)
            ->get(route('admin.manajemen_produk'))
            ->assertForbidden();
    }

    public function test_admin_can_delete_product_from_product_management(): void
    {
        $admin = $this->createAdmin();
        $category = Kategori::create(['nama_kategori' => 'Audio', 'slug' => 'audio', 'deskripsi' => 'Perangkat Audio']);
        $brand = Merek::create(['nama_merek' => 'Sony', 'slug' => 'sony', 'logo_url' => '']);
        $product = Produk::create([
            'id_kategori' => $category->id_kategori,
            'id_merek' => $brand->id_merek,
            'nama_produk' => 'Sony WH-1000XM5',
            'sku' => 'SNY-WH5-001',
            'tipe_produk' => 'regular',
            'kondisi' => 'baru',
            'masa_garansi_bulan' => 12,
            'deskripsi' => 'Headphone noise cancelling',
            'gambar_path' => '',
            'rating_rata_rata' => 5.0,
            'jumlah_terjual' => 0,
            'harga' => 4500000,
            'stok' => 10,
            'is_active' => true,
        ]);

        // Saat produk masih aktif: tombol Edit aktif, tombol Hapus dalam kondisi disabled
        $this->actingAs($admin)
            ->get(route('admin.manajemen_produk', ['tab' => 'produk']))
            ->assertOk()
            ->assertSee('Edit')
            ->assertSee(route('admin.products.edit', $product->id_produk))
            ->assertSee('Nonaktifkan')
            ->assertSee('Hapus')
            ->assertSee('btn-disabled')
            ->assertSee('Nonaktifkan produk terlebih dahulu sebelum menghapus')
            ->assertDontSee("openDeleteModal('{$product->id_produk}'", false);

        // Halaman edit memiliki modal pop out card konfirmasi perubahan & dirty check notice
        $this->actingAs($admin)
            ->get(route('admin.products.edit', $product->id_produk))
            ->assertOk()
            ->assertSee('Konfirmasi Perubahan Produk')
            ->assertSee('noChangesAlert')
            ->assertSee('modalChangesList')
            ->assertSee('Belum ada perubahan data!');

        // Nonaktifkan produk terlebih dahulu
        $this->actingAs($admin)
            ->patch(route('admin.products.status', $product->id_produk));

        $this->assertDatabaseHas('produk', [
            'id_produk' => $product->id_produk,
            'is_active' => false,
        ]);

        // Setelah dinonaktifkan: tombol Edit dinonaktifkan (disabled), tombol Hapus aktif dan modal pop out card konfirmasi hapus tersedia
        $this->actingAs($admin)
            ->get(route('admin.manajemen_produk', ['tab' => 'produk']))
            ->assertOk()
            ->assertSee('Aktifkan')
            ->assertSee('Aktifkan produk terlebih dahulu untuk dapat mengedit')
            ->assertSee("openDeleteModal('{$product->id_produk}'", false)
            ->assertSee('Konfirmasi Hapus Produk');

        // Eksekusi hapus produk
        $this->actingAs($admin)
            ->delete(route('admin.products.destroy', $product->id_produk))
            ->assertRedirect(route('admin.products.index'))
            ->assertSessionHas('success', 'Produk berhasil dihapus.');

        $this->assertDatabaseMissing('produk', [
            'id_produk' => $product->id_produk,
        ]);
    }

    public function test_customer_and_admin_can_search_products_by_category_keyword(): void
    {
        $admin = $this->createAdmin();
        $customer = User::create([
            'nama_lengkap' => 'Search Tester',
            'nomor_telepon' => '089999999999',
            'username' => 'searchtester',
            'password_hash' => Hash::make('password'),
            'role' => 'customer',
        ]);

        $catKeyboard = Kategori::create([
            'nama_kategori' => 'Keyboard Mechanical',
            'slug' => 'keyboard-mechanical',
            'deskripsi' => 'Aksesoris Keyboard',
        ]);
        $brand = Merek::create([
            'nama_merek' => 'Keychron',
            'logo_url' => '',
        ]);

        $product = Produk::create([
            'id_kategori' => $catKeyboard->id_kategori,
            'id_merek' => $brand->id_merek,
            'nama_produk' => 'K2 Pro Wireless',
            'sku' => 'KEY-K2-001',
            'tipe_produk' => 'regular',
            'kondisi' => 'baru',
            'masa_garansi_bulan' => 12,
            'deskripsi' => 'Custom mechanical keyboard',
            'gambar_path' => '',
            'rating_rata_rata' => 4.9,
            'jumlah_terjual' => 15,
            'harga' => 1500000,
            'stok' => 5,
            'is_active' => true,
        ]);

        // Customer mencari dengan kata kunci kategori "Mechanical"
        $this->actingAs($customer)
            ->get(route('dashboard', ['search' => 'Mechanical']))
            ->assertOk()
            ->assertSee('K2 Pro Wireless')
            ->assertSee('Keyboard Mechanical');

        // Admin mencari dengan kata kunci kategori "Mechanical" di tab produk
        $this->actingAs($admin)
            ->get(route('admin.manajemen_produk', ['tab' => 'produk', 'search' => 'Mechanical']))
            ->assertOk()
            ->assertSee('K2 Pro Wireless')
            ->assertSee('Keyboard Mechanical');

        // Admin mencari dengan kata kunci nama produk "K2 Pro"
        $this->actingAs($admin)
            ->get(route('admin.manajemen_produk', ['tab' => 'produk', 'search' => 'K2 Pro']))
            ->assertOk()
            ->assertSee('K2 Pro Wireless');
    }

    private function createAdmin(): User
    {
        return User::create([
            'nama_lengkap' => 'Admin Store',
            'nomor_telepon' => '081111111111',
            'username' => 'adminstore',
            'password_hash' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
    }

    /**
     * @return array{0: User, 1: Pesanan, 2: Produk}
     */
    private function createCustomerOrderFixture(string $status): array
    {
        $customer = User::create([
            'nama_lengkap' => 'Budi Customer',
            'nomor_telepon' => '081234567890',
            'username' => 'budicust',
            'password_hash' => Hash::make('password'),
            'role' => 'customer',
        ]);

        $category = Kategori::create([
            'nama_kategori' => 'RAM',
            'slug' => 'ram',
            'deskripsi' => 'Memori RAM',
        ]);

        $brand = Merek::create([
            'nama_merek' => 'Corsair',
            'logo_url' => '',
        ]);

        $product = Produk::create([
            'id_kategori' => $category->id_kategori,
            'id_merek' => $brand->id_merek,
            'sku' => 'RAM-CORS-16',
            'nama_produk' => 'Corsair Vengeance LPX 16GB',
            'tipe_produk' => 'regular',
            'harga' => 1000000,
            'stok' => 8,
            'jumlah_terjual' => 5,
            'kondisi' => 'baru',
            'masa_garansi_bulan' => 24,
            'deskripsi' => 'RAM DDR4 high performance',
            'gambar_path' => '',
            'rating_rata_rata' => 4.8,
            'is_active' => true,
        ]);

        $alamat = AlamatPengiriman::create([
            'id_user' => $customer->id_user,
            'label_alamat' => 'Rumah',
            'nama_penerima' => 'Budi Customer',
            'nomor_telepon' => '081234567890',
            'alamat_lengkap' => 'Jl. Merdeka No. 10',
            'kota' => 'Bandung',
            'provinsi' => 'Jawa Barat',
            'kode_pos' => '40111',
            'is_primary' => true,
        ]);

        $order = Pesanan::create([
            'nomor_pesanan' => 'ORD-20261110-0001',
            'id_user' => $customer->id_user,
            'id_alamat' => $alamat->id_alamat,
            'subtotal' => 2000000,
            'biaya_pengiriman' => 25000,
            'pajak' => 0,
            'total_pembayaran' => 2025000,
            'status_pemesanan' => $status,
            'tanggal_pesanan' => now(),
        ]);

        ItemPesanan::create([
            'id_pesanan' => $order->id_pesanan,
            'id_produk' => $product->id_produk,
            'jumlah' => 2,
            'harga_satuan' => 1000000,
            'subtotal_item' => 2000000,
        ]);

        return [$customer, $order, $product];
    }
}
