<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_dashboard(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_home_page_redirects_to_login(): void
    {
        $this->get(route('home'))
            ->assertRedirect(route('login'));
    }

    public function test_login_page_is_separate_from_the_home_page(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('name="username"', false)
            ->assertSee(route('login.store'));
    }

    public function test_user_can_log_in_and_dashboard_shows_their_name_and_catalog_data(): void
    {
        $user = $this->createUser();
        $this->createProductWithPlaceholderImage();

        $this->withSession(['url.intended' => route('login')]);

        $this->post(route('login.store'), [
            'username' => $user->username,
            'password' => 'password123',
        ])->assertRedirect(route('dashboard'));

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Nama Pengguna')
            ->assertSee('Customer')
            ->assertSee('@pengguna')
            ->assertSee('Produk dari database')
            ->assertSee('Foto belum tersedia')
            ->assertDontSee('https://example.com/products/demo.jpg');
    }

    public function test_admin_user_dashboard_shows_admin_badge(): void
    {
        $admin = User::create([
            'nama_lengkap' => 'Admin Utama',
            'nomor_telepon' => '081111111111',
            'username' => 'adminutama',
            'password_hash' => Hash::make('secret123'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Admin Utama')
            ->assertSee('Administrator')
            ->assertSee('@adminutama');
    }

    public function test_invalid_credentials_return_to_login_with_an_error(): void
    {
        $this->from(route('login'))->post(route('login.store'), [
            'username' => 'unknown',
            'password' => 'wrong-password',
        ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('username');
    }

    public function test_dashboard_can_search_products_by_sku(): void
    {
        $this->actingAs($this->createUser());
        $this->createProductWithPlaceholderImage();

        $this->get(route('dashboard', ['search' => 'TEST-CPU-001']))
            ->assertOk()
            ->assertSee('Hasil pencarian')
            ->assertSee('Produk dari database');
    }

    public function test_dashboard_displays_products_seeded_by_demo_seeder(): void
    {
        $this->seed(DemoSeeder::class);
        $this->actingAs($this->createUser());

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('AMD Ryzen 5 5600')
            ->assertSee('NVIDIA GeForce RTX 4060')
            ->assertSee('Foto belum tersedia');
    }

    public function test_user_can_log_out(): void
    {
        $user = $this->createUser();
        $this->actingAs($user);

        $this->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    private function createUser(): User
    {
        return User::create([
            'nama_lengkap' => 'Nama Pengguna',
            'nomor_telepon' => '081234567890',
            'username' => 'pengguna',
            'password_hash' => Hash::make('password123'),
            'role' => 'customer',
        ]);
    }

    private function createProductWithPlaceholderImage(): void
    {
        $now = now();
        $categoryId = DB::table('kategori')->insertGetId([
            'nama_kategori' => 'Processor',
            'slug' => 'processor',
            'deskripsi' => 'Komponen komputer',
        ]);
        $brandId = DB::table('merek')->insertGetId([
            'nama_merek' => 'Demo Brand',
            'logo_url' => '',
        ]);

        DB::table('produk')->insert([
            'id_kategori' => $categoryId,
            'id_merek' => $brandId,
            'sku' => 'TEST-CPU-001',
            'nama_produk' => 'Produk dari database',
            'tipe_produk' => 'regular',
            'harga' => 1000000,
            'stok' => 10,
            'jumlah_terjual' => 2,
            'kondisi' => 'baru',
            'masa_garansi_bulan' => 12,
            'deskripsi' => 'Data produk untuk pengujian.',
            'gambar_path' => 'products/demo.jpg',
            'rating_rata_rata' => 4.50,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
