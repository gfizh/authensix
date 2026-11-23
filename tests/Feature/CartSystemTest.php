<?php

namespace Tests\Feature;

use App\Models\AlamatPengiriman;
use App\Models\ItemKeranjang;
use App\Models\Kategori;
use App\Models\Keranjang;
use App\Models\Merek;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CartSystemTest extends TestCase
{
    use RefreshDatabase;

    private function createCustomer(string $username = 'customer1'): User
    {
        return User::create([
            'nama_lengkap' => 'Customer '.$username,
            'nomor_telepon' => '081234567890',
            'username' => $username,
            'password_hash' => Hash::make('password'),
            'role' => 'customer',
            'is_active' => true,
        ]);
    }

    private function createProductFixture(array $overrides = []): Produk
    {
        $category = Kategori::firstOrCreate(
            ['slug' => 'processor'],
            ['nama_kategori' => 'Processor', 'deskripsi' => 'CPU']
        );

        $brand = Merek::firstOrCreate(
            ['nama_merek' => 'AMD'],
            ['logo_url' => '']
        );

        return Produk::create(array_merge([
            'id_kategori' => $category->id_kategori,
            'id_merek' => $brand->id_merek,
            'sku' => 'SKU-'.uniqid(),
            'nama_produk' => 'AMD Ryzen 5 5600',
            'tipe_produk' => 'regular',
            'harga' => 1650000,
            'stok' => 5,
            'jumlah_terjual' => 0,
            'kondisi' => 'baru',
            'masa_garansi_bulan' => 36,
            'deskripsi' => 'Prosesor desktop 6 core 12 thread',
            'gambar_path' => 'products/ryzen5.webp',
            'rating_rata_rata' => 4.8,
            'is_active' => true,
        ], $overrides));
    }

    public function test_guest_cannot_access_cart_endpoints(): void
    {
        $this->get(route('cart.index'))->assertRedirect(route('login'));
        $this->post(route('cart.store'), ['id_produk' => 1])->assertRedirect(route('login'));
    }

    public function test_empty_cart_shows_empty_state_without_dummy_items(): void
    {
        $customer = $this->createCustomer();

        $this->actingAs($customer)
            ->get(route('cart.index'))
            ->assertOk()
            ->assertSee('Keranjang kamu masih kosong')
            ->assertDontSee('GIGABYTE VGA NVIDIA GEFORCE RTX 5060 TI EAGLE ICE');
    }

    public function test_customer_can_add_product_to_cart_via_json_and_accumulate_quantity(): void
    {
        $customer = $this->createCustomer();
        $product = $this->createProductFixture(['stok' => 5]);

        // First add: qty 2
        $this->actingAs($customer)
            ->postJson(route('cart.store'), [
                'id_produk' => $product->id_produk,
                'jumlah' => 2,
            ])
            ->assertCreated()
            ->assertJson([
                'success' => true,
                'cart_count' => 2,
            ]);

        // Second add: qty 2 -> total 4
        $this->actingAs($customer)
            ->postJson(route('cart.store'), [
                'id_produk' => $product->id_produk,
                'jumlah' => 2,
            ])
            ->assertCreated()
            ->assertJson([
                'success' => true,
                'cart_count' => 4,
            ]);

        $this->assertDatabaseHas('item_keranjang', [
            'id_produk' => $product->id_produk,
            'jumlah' => 4,
        ]);

        // Exceeding stock: adding 2 more -> 6 > 5
        $this->actingAs($customer)
            ->postJson(route('cart.store'), [
                'id_produk' => $product->id_produk,
                'jumlah' => 2,
            ])
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_customer_cannot_add_inactive_or_out_of_stock_product(): void
    {
        $customer = $this->createCustomer();
        $inactive = $this->createProductFixture(['is_active' => false, 'stok' => 5]);
        $outOfStock = $this->createProductFixture(['is_active' => true, 'stok' => 0]);

        $this->actingAs($customer)
            ->postJson(route('cart.store'), ['id_produk' => $inactive->id_produk, 'jumlah' => 1])
            ->assertStatus(422)
            ->assertJson(['success' => false]);

        $this->actingAs($customer)
            ->postJson(route('cart.store'), ['id_produk' => $outOfStock->id_produk, 'jumlah' => 1])
            ->assertStatus(422)
            ->assertJson(['success' => false]);
    }

    public function test_cart_page_displays_database_items_and_navbar_badge(): void
    {
        $customer = $this->createCustomer();
        $product = $this->createProductFixture([
            'nama_produk' => 'Kingston Fury 16GB DDR4',
            'harga' => 650000,
            'stok' => 10,
        ]);

        $this->actingAs($customer)
            ->post(route('cart.store'), [
                'id_produk' => $product->id_produk,
                'jumlah' => 3,
            ])
            ->assertRedirect();

        $this->actingAs($customer)
            ->get(route('cart.index'))
            ->assertOk()
            ->assertSee('Kingston Fury 16GB DDR4')
            ->assertSee('Rp650.000')
            ->assertSee('name="btn_qty"', false)
            ->assertSee('<span id="nav-cart-badge" class="nav-cart-badge" >3</span>', false);
    }

    public function test_customer_can_update_quantity_with_decrement_and_increment_buttons_and_direct_input(): void
    {
        $customer = $this->createCustomer();
        $product = $this->createProductFixture(['stok' => 5]);

        $cart = Keranjang::create(['id_user' => $customer->id_user]);
        $item = ItemKeranjang::create([
            'id_keranjang' => $cart->id_keranjang,
            'id_produk' => $product->id_produk,
            'jumlah' => 3,
            'harga_satuan' => $product->harga,
        ]);

        // Simulate clicking decrement button (`btn_qty=2` while `qty=3` is also submitted by the form)
        $this->actingAs($customer)
            ->from(route('cart.index'))
            ->patch(route('cart.update', $item->id_item_keranjang), [
                'btn_qty' => 2,
                'qty' => 3,
            ])
            ->assertRedirect(route('cart.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('item_keranjang', [
            'id_item_keranjang' => $item->id_item_keranjang,
            'jumlah' => 2,
        ]);

        // Direct input update (`qty=4`)
        $this->actingAs($customer)
            ->from(route('cart.index'))
            ->patch(route('cart.update', $item->id_item_keranjang), [
                'qty' => 4,
            ])
            ->assertRedirect(route('cart.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('item_keranjang', [
            'id_item_keranjang' => $item->id_item_keranjang,
            'jumlah' => 4,
        ]);

        // Exceed stock check
        $this->actingAs($customer)
            ->from(route('cart.index'))
            ->patch(route('cart.update', $item->id_item_keranjang), [
                'qty' => 10,
            ])
            ->assertRedirect(route('cart.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('item_keranjang', [
            'id_item_keranjang' => $item->id_item_keranjang,
            'jumlah' => 4,
        ]);
    }

    public function test_customer_cannot_modify_another_customers_cart_item(): void
    {
        $customerA = $this->createCustomer('custA');
        $customerB = $this->createCustomer('custB');
        $product = $this->createProductFixture(['stok' => 5]);

        $cartA = Keranjang::create(['id_user' => $customerA->id_user]);
        $itemA = ItemKeranjang::create([
            'id_keranjang' => $cartA->id_keranjang,
            'id_produk' => $product->id_produk,
            'jumlah' => 2,
            'harga_satuan' => $product->harga,
        ]);

        $this->actingAs($customerB)
            ->patchJson(route('cart.update', $itemA->id_item_keranjang), ['qty' => 1])
            ->assertNotFound();

        $this->actingAs($customerB)
            ->deleteJson(route('cart.destroy', $itemA->id_item_keranjang))
            ->assertNotFound();

        $this->assertDatabaseHas('item_keranjang', [
            'id_item_keranjang' => $itemA->id_item_keranjang,
            'jumlah' => 2,
        ]);
    }

    public function test_customer_can_delete_single_item_and_clear_entire_cart(): void
    {
        $customer = $this->createCustomer();
        $product1 = $this->createProductFixture(['nama_produk' => 'Product One', 'stok' => 5]);
        $product2 = $this->createProductFixture(['nama_produk' => 'Product Two', 'stok' => 5]);

        $cart = Keranjang::create(['id_user' => $customer->id_user]);
        $item1 = ItemKeranjang::create([
            'id_keranjang' => $cart->id_keranjang,
            'id_produk' => $product1->id_produk,
            'jumlah' => 1,
            'harga_satuan' => $product1->harga,
        ]);
        $item2 = ItemKeranjang::create([
            'id_keranjang' => $cart->id_keranjang,
            'id_produk' => $product2->id_produk,
            'jumlah' => 2,
            'harga_satuan' => $product2->harga,
        ]);

        // Delete item 1
        $this->actingAs($customer)
            ->from(route('cart.index'))
            ->delete(route('cart.destroy', $item1->id_item_keranjang))
            ->assertRedirect(route('cart.index'));

        $this->assertDatabaseMissing('item_keranjang', ['id_item_keranjang' => $item1->id_item_keranjang]);
        $this->assertDatabaseHas('item_keranjang', ['id_item_keranjang' => $item2->id_item_keranjang]);

        // Clear remaining items
        $this->actingAs($customer)
            ->from(route('cart.index'))
            ->delete(route('cart.clear'))
            ->assertRedirect(route('cart.index'));

        $this->assertDatabaseMissing('item_keranjang', ['id_keranjang' => $cart->id_keranjang]);
    }

    public function test_partial_checkout_only_processes_selected_cart_items(): void
    {
        $customer = $this->createCustomer();
        $product1 = $this->createProductFixture(['nama_produk' => 'Selected Product', 'harga' => 1000000, 'stok' => 5]);
        $product2 = $this->createProductFixture(['nama_produk' => 'Unselected Product', 'harga' => 500000, 'stok' => 5]);

        $cart = Keranjang::create(['id_user' => $customer->id_user]);
        $item1 = ItemKeranjang::create([
            'id_keranjang' => $cart->id_keranjang,
            'id_produk' => $product1->id_produk,
            'jumlah' => 2,
            'harga_satuan' => $product1->harga,
        ]);
        $item2 = ItemKeranjang::create([
            'id_keranjang' => $cart->id_keranjang,
            'id_produk' => $product2->id_produk,
            'jumlah' => 1,
            'harga_satuan' => $product2->harga,
        ]);

        $address = AlamatPengiriman::create([
            'id_user' => $customer->id_user,
            'label_alamat' => 'Rumah',
            'nama_penerima' => $customer->nama_lengkap,
            'nomor_telepon' => '081234567890',
            'alamat_lengkap' => 'Jl. Merdeka No. 1',
            'kota' => 'Jakarta',
            'provinsi' => 'DKI Jakarta',
            'kode_pos' => '10110',
            'is_primary' => true,
        ]);

        $response = $this->actingAs($customer)
            ->postJson('/api/checkout', [
                'id_alamat' => $address->id_alamat,
                'biaya_pengiriman' => 25000,
                'pajak' => 0,
                'item_ids' => [$item1->id_item_keranjang],
            ]);

        $response->assertCreated()
            ->assertJson([
                'success' => true,
            ]);

        // Item 1 removed from cart, Item 2 still in cart
        $this->assertDatabaseMissing('item_keranjang', ['id_item_keranjang' => $item1->id_item_keranjang]);
        $this->assertDatabaseHas('item_keranjang', ['id_item_keranjang' => $item2->id_item_keranjang, 'jumlah' => 1]);

        // Stock decremented only for product1
        $this->assertDatabaseHas('produk', [
            'id_produk' => $product1->id_produk,
            'stok' => 3,
            'jumlah_terjual' => 2,
        ]);
        $this->assertDatabaseHas('produk', [
            'id_produk' => $product2->id_produk,
            'stok' => 5,
            'jumlah_terjual' => 0,
        ]);

        // Order total reflects only item1 (2 * 1,000,000 + 25,000 = 2,025,000)
        $this->assertDatabaseHas('pesanan', [
            'id_user' => $customer->id_user,
            'subtotal' => 2000000,
            'total_pembayaran' => 2025000,
        ]);
    }

    public function test_detail_produk_page_uses_web_cart_store_route(): void
    {
        $customer = $this->createCustomer();
        $product = $this->createProductFixture();

        $this->actingAs($customer)
            ->get(route('produk.detail', $product->id_produk))
            ->assertOk()
            ->assertSee(route('cart.store'), false)
            ->assertSee('csrf-token', false);
    }
}
