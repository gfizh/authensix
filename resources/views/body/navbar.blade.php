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

  <a href="{{ route('cart.index') }}" aria-label="Keranjang">
    <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.6 12.4a1 1 0 0 0 1 .8h8.8a1 1 0 0 0 1-.8L20 7H6"/></svg>
  </a>
</header>