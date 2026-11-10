<header class="navbar">
  <a href="{{ route('dashboard') }}" class="logo" aria-label="AuthenSIX Computer">
    Authen<b>SIX</b>
    <small>COMPUTER</small>
  </a>

  <a href="{{ route('kategori') }}" class="kategori" aria-haspopup="true" @if (request()->routeIs('kategori')) aria-current="page" @endif>
    Kategori
    <svg class="icon" style="width:14px;height:14px" viewBox="0 0 24 24" aria-hidden="true">
      <path d="M6 9l6 6 6-6" fill="currentColor"/>
    </svg>
  </a>

  <form class="search" role="search" method="GET" action="{{ route('dashboard') }}">
    <button type="submit" aria-label="Cari">
      <svg class="icon" style="width:18px;height:18px" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
    </button>
    <input type="search" name="search" value="{{ request('search') }}" aria-label="Cari produk">
    @if (request()->filled('category'))
      <input type="hidden" name="category" value="{{ request('category') }}">
    @endif
  </form>

  <span class="nav-spacer"></span>

  <a href="{{ route('cart.index') }}" aria-label="Keranjang" title="Keranjang">
    <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.6 12.4a1 1 0 0 0 1 .8h8.8a1 1 0 0 0 1-.8L20 7H6"/></svg>
  </a>

  @auth
    @if (auth()->user()->isAdmin())
      <a href="{{ route('admin.dashboard-admin') }}" class="admin-pill" style="font-size: 12px; font-weight: 600; background: #e0f2fe; color: #0284c7; padding: 5px 10px; border-radius: 6px; text-decoration: none; white-space: nowrap;">
        Panel Admin
      </a>
    @else
      <a href="{{ route('riwayat-pesanan') }}"
         style="display: inline-flex; align-items: center; gap: 6px; font-size: 12.5px; font-weight: 600; padding: 6px 12px; border-radius: 8px; text-decoration: none; white-space: nowrap; {{ request()->routeIs('riwayat-pesanan') ? 'background: #1673e6; color: #ffffff;' : 'background: #eff6ff; color: #1673e6;' }}">
        <svg style="width: 15px; height: 15px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
          <rect x="9" y="3" width="6" height="4" rx="1"/>
        </svg>
        <span>Riwayat Pesanan</span>
      </a>
    @endif
    <form method="POST" action="{{ route('logout') }}" style="display: inline-flex; align-items: center; margin-left: 4px;">
      @csrf
      <button type="submit" title="Keluar" aria-label="Keluar" style="color: #64748b; padding: 4px; display: inline-flex; align-items: center; cursor: pointer;">
        <svg class="icon" style="width: 20px; height: 20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
          <polyline points="16 17 21 12 16 7"/>
          <line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
      </button>
    </form>
  @endauth
</header>
