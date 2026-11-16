@php
    $dashboardActive = request()->routeIs('admin.dashboard', 'admin.dashboard-admin');
    $ordersActive = request()->routeIs('admin.manajemen_produk')
        && request()->query('tab', 'pesanan') === 'pesanan';
    $productsActive = request()->routeIs('admin.manajemen_produk')
        && request()->query('tab') === 'produk';
    $addProductActive = request()->routeIs(
        'admin.tambah_produk',
        'admin.products.create',
        'admin.products.edit'
    );
@endphp

<aside class="sidebar" aria-label="Menu Admin">
  <div class="sidebar-brand">
    <a href="{{ route('admin.dashboard-admin') }}" class="brand-link" aria-label="AuthenSIX Computer">
      <div class="brand-text">Authen<b>SIX</b></div>
      <small class="brand-sub">COMPUTER</small>
    </a>
  </div>

  <nav class="sidebar-nav">
    <a href="{{ route('admin.dashboard-admin') }}"
       class="nav-item {{ $dashboardActive ? 'active' : '' }}"
       @if ($dashboardActive) aria-current="page" @endif>
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
        <polyline points="9 22 9 12 15 12 15 22"/>
      </svg>
      <span>Dashboard</span>
    </a>

    <a href="{{ route('admin.manajemen_produk', ['tab' => 'pesanan']) }}"
       class="nav-item {{ $ordersActive ? 'active' : '' }}"
       @if ($ordersActive) aria-current="page" @endif>
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
        <rect x="9" y="3" width="6" height="4" rx="1"/>
        <path d="M9 12h6"/>
        <path d="M9 16h6"/>
      </svg>
      <span>Manajemen Pesanan</span>
    </a>

    <a href="{{ route('admin.manajemen_produk', ['tab' => 'produk']) }}"
       class="nav-item {{ $productsActive ? 'active' : '' }}"
       @if ($productsActive) aria-current="page" @endif>
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
        <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
        <line x1="12" y1="22.08" x2="12" y2="12"/>
      </svg>
      <span>Product</span>
    </a>

    <a href="{{ route('admin.tambah_produk') }}"
       class="nav-item {{ $addProductActive ? 'active' : '' }}"
       @if ($addProductActive) aria-current="page" @endif>
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <circle cx="12" cy="12" r="9"/>
        <line x1="12" y1="8" x2="12" y2="16"/>
        <line x1="8" y1="12" x2="16" y2="12"/>
      </svg>
      <span>Tambah Produk</span>
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="user-card">
      <div class="user-avatar" title="{{ auth()->user()->nama_lengkap ?? 'Admin' }}">
        {{ strtoupper(substr(auth()->user()->nama_lengkap ?? auth()->user()->username ?? 'A', 0, 2)) }}
      </div>
      <div class="user-meta">
        <div class="user-name">{{ auth()->user()->nama_lengkap ?? auth()->user()->username ?? 'Admin' }}</div>
        <div class="user-role">{{ auth()->user()->isAdmin() ? 'administrator' : (auth()->user()->role ?? 'administrator') }}</div>
      </div>
      <form method="POST" action="{{ route('logout') }}" class="logout-form">
        @csrf
        <button type="submit" class="logout-btn" title="Keluar dari akun">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            <polyline points="16 17 21 12 16 7"/>
            <line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
        </button>
      </form>
    </div>
  </div>
</aside>
