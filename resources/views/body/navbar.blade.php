<style>
  .navbar {
    position: sticky !important;
    top: 0 !important;
    z-index: 1000 !important;
    background: #ffffff !important;
    border-bottom: 1px solid #e2e8f0 !important;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04) !important;
    min-height: 64px;
    display: flex !important;
    align-items: center !important;
    gap: 16px !important;
    padding: 10px 24px !important;
    width: 100% !important;
    box-sizing: border-box !important;
  }

  .navbar .logo {
    display: inline-block;
    text-decoration: none;
    font-size: 21px;
    font-weight: 750;
    letter-spacing: -0.5px;
    color: #0f172a;
    line-height: 1.1;
    margin-right: 4px;
  }
  .navbar .logo b {
    color: #1673e6;
  }
  .navbar .logo small {
    display: block;
    font-size: 8px;
    font-weight: 600;
    letter-spacing: 3px;
    text-align: center;
    color: #64748b;
    margin-top: 1px;
  }

  .nav-btn-kategori {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 12px;
    border-radius: 8px;
    color: #334155;
    font-size: 13.5px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.15s ease;
  }
  .nav-btn-kategori:hover,
  .nav-btn-kategori.active {
    background: #f1f5f9;
    color: #1673e6;
  }

  .navbar-search {
    flex: 1;
    max-width: 360px;
    display: flex;
    align-items: center;
    gap: 8px;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    padding: 0 12px;
    height: 38px;
    background: #ffffff;
    transition: all 0.15s ease;
  }
  .navbar-search:focus-within {
    border-color: #1673e6;
    box-shadow: 0 0 0 3px rgba(22, 115, 230, 0.12);
  }
  .navbar-search button {
    display: grid;
    place-items: center;
    color: #94a3b8;
    background: none;
    border: 0;
    padding: 0;
    cursor: pointer;
  }
  .navbar-search button:hover {
    color: #1673e6;
  }
  .navbar-search input {
    flex: 1;
    border: 0;
    outline: 0;
    font: inherit;
    min-width: 0;
    background: transparent;
    color: #0f172a;
    font-size: 13px;
  }

  .navbar-nav-right {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-left: auto;
  }

  .nav-cart-btn {
    position: relative;
    display: grid;
    place-items: center;
    width: 38px;
    height: 38px;
    border-radius: 10px;
    color: #475569;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    transition: all 0.15s ease;
    text-decoration: none;
  }
  .nav-cart-badge {
    position: absolute;
    top: -6px;
    right: -6px;
    min-width: 18px;
    height: 18px;
    padding: 0 5px;
    border-radius: 999px;
    background: #1673e6;
    color: #ffffff;
    font-size: 10.5px;
    font-weight: 700;
    display: grid;
    place-items: center;
    border: 2px solid #ffffff;
    line-height: 1;
  }
  .nav-cart-badge[hidden] {
    display: none !important;
  }
  .nav-cart-btn:hover,
  .nav-cart-btn.active {
    background: #eff6ff;
    color: #1673e6;
    border-color: #bfdbfe;
  }

  .nav-order-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    font-weight: 600;
    padding: 8px 14px;
    border-radius: 10px;
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.15s ease;
    border: 1px solid transparent;
  }
  .nav-order-pill.active {
    background: #1673e6;
    color: #ffffff;
    border-color: #1673e6;
  }
  .nav-order-pill:not(.active) {
    background: #f8fafc;
    color: #334155;
    border-color: #e2e8f0;
  }
  .nav-order-pill:not(.active):hover {
    background: #eff6ff;
    color: #1673e6;
    border-color: #bfdbfe;
  }

  .nav-admin-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
    background: #e0f2fe;
    color: #0284c7;
    border: 1px solid #bae6fd;
    padding: 7px 12px;
    border-radius: 8px;
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.15s ease;
  }
  .nav-admin-pill:hover {
    background: #bae6fd;
    color: #0369a1;
  }

  .nav-user-chip {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 4px 6px 4px 10px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
  }
  .nav-user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #e0f2fe;
    color: #0284c7;
    font-size: 12.5px;
    font-weight: 700;
    display: grid;
    place-items: center;
    flex-shrink: 0;
  }
  .nav-user-meta {
    display: flex;
    flex-direction: column;
    line-height: 1.25;
    text-align: left;
  }
  .nav-user-name {
    font-size: 12.5px;
    font-weight: 700;
    color: #0f172a;
    max-width: 140px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .nav-user-sub {
    font-size: 11px;
    color: #64748b;
    font-weight: 500;
  }
  .nav-user-sub .handle {
    color: #94a3b8;
  }
  .nav-logout-btn {
    display: grid;
    place-items: center;
    width: 28px;
    height: 28px;
    border-radius: 6px;
    color: #94a3b8;
    background: none;
    border: 0;
    cursor: pointer;
    transition: all 0.15s ease;
  }
  .nav-logout-btn:hover {
    background: #fee2e2;
    color: #ef4444;
  }

  .nav-login-btn {
    display: inline-flex;
    align-items: center;
    font-size: 13px;
    font-weight: 600;
    color: #1673e6;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    padding: 7px 16px;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.15s ease;
  }
  .nav-login-btn:hover {
    background: #1673e6;
    color: #ffffff;
  }

  @media (max-width: 768px) {
    .navbar {
      flex-wrap: wrap !important;
      padding: 10px 16px !important;
      gap: 10px !important;
    }
    .navbar-search {
      order: 10;
      max-width: 100% !important;
      flex-basis: 100%;
    }
    .nav-user-meta {
      display: none;
    }
  }
</style>

<header class="navbar">
  <a href="{{ route('dashboard') }}" class="logo" aria-label="AuthenSIX Computer">
    Authen<b>SIX</b>
    <small>COMPUTER</small>
  </a>

  <a href="{{ route('kategori') }}" class="nav-btn-kategori {{ request()->routeIs('kategori') ? 'active' : '' }}" aria-haspopup="true" @if (request()->routeIs('kategori')) aria-current="page" @endif>
    <svg style="width:15px;height:15px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
      <rect x="3" y="3" width="7" height="7" rx="1.5"/>
      <rect x="14" y="3" width="7" height="7" rx="1.5"/>
      <rect x="14" y="14" width="7" height="7" rx="1.5"/>
      <rect x="3" y="14" width="7" height="7" rx="1.5"/>
    </svg>
    <span>Kategori</span>
  </a>

  <form class="navbar-search" role="search" method="GET" action="{{ route('dashboard') }}">
    <button type="submit" aria-label="Cari">
      <svg style="width:17px;height:17px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
        <circle cx="11" cy="11" r="8"/>
        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
      </svg>
    </button>
    <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama produk atau kategori..." aria-label="Cari nama produk atau kategori">
    @if (request()->filled('category'))
      <input type="hidden" name="category" value="{{ request('category') }}">
    @endif
  </form>

  @php
    $navCartCount = 0;
    if (auth()->check()) {
      $navCart = \App\Models\Keranjang::where('id_user', auth()->id())->first();
      $navCartCount = $navCart ? (int) $navCart->items()->sum('jumlah') : 0;
    }
  @endphp

  <div class="navbar-nav-right">
    <a href="{{ route('cart.index') }}" class="nav-cart-btn {{ request()->routeIs('cart.index') ? 'active' : '' }}" aria-label="Keranjang" title="Keranjang Belanja">
      <svg style="width:19px;height:19px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/>
        <path d="M2 3h3l2.6 12.4a1 1 0 0 0 1 .8h8.8a1 1 0 0 0 1-.8L20 7H6"/>
      </svg>
      <span id="nav-cart-badge" class="nav-cart-badge" @if ($navCartCount <= 0) hidden @endif>{{ $navCartCount }}</span>
    </a>

    @auth
      @if (auth()->user()->isAdmin())
        <a href="{{ route('admin.dashboard-admin') }}" class="nav-admin-pill" title="Panel Admin">
          <svg style="width:14px;height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="7" height="7"/>
            <rect x="14" y="3" width="7" height="7"/>
            <rect x="14" y="14" width="7" height="7"/>
            <rect x="3" y="14" width="7" height="7"/>
          </svg>
          <span>Panel Admin</span>
        </a>
      @else
        <a href="{{ route('riwayat-pesanan') }}" class="nav-order-pill {{ request()->routeIs('riwayat-pesanan') ? 'active' : '' }}" title="Riwayat Pesanan">
          <svg style="width:15px;height:15px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
            <rect x="9" y="3" width="6" height="4" rx="1"/>
            <path d="M9 12h6"/>
            <path d="M9 16h6"/>
          </svg>
          <span>Riwayat Pesanan</span>
        </a>
      @endif

      <!-- User Profile Card in Navbar -->
      <div class="nav-user-chip" title="{{ auth()->user()->nama_lengkap ?? auth()->user()->username }}">
        <div class="nav-user-avatar">
          {{ strtoupper(substr(auth()->user()->nama_lengkap ?? auth()->user()->username ?? 'U', 0, 2)) }}
        </div>
        <div class="nav-user-meta">
          <div class="nav-user-name">{{ auth()->user()->nama_lengkap ?? auth()->user()->username }}</div>
          <div class="nav-user-sub">
            <span>{{ auth()->user()->isAdmin() ? 'Admin' : 'Customer' }}</span>
            <span class="handle">&middot; @php echo '@' . (auth()->user()->username ?? ''); @endphp</span>
          </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" style="display:inline-flex;margin:0;">
          @csrf
          <button type="submit" class="nav-logout-btn" title="Keluar dari akun" aria-label="Keluar">
            <svg style="width:16px;height:16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
              <polyline points="16 17 21 12 16 7"/>
              <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
          </button>
        </form>
      </div>
    @else
      <a href="{{ route('login') }}" class="nav-login-btn">Masuk</a>
    @endauth
  </div>
</header>
