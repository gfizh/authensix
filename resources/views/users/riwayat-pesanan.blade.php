<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Riwayat Pesanan | AuthenSIX Computer</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;750&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg: #f8fafc;
      --card-bg: #ffffff;
      --border: #e2e8f0;
      --border-light: #f1f5f9;
      --text-main: #0f172a;
      --text-muted: #64748b;
      --text-sub: #94a3b8;
      --primary: #1673e6;
      --primary-hover: #1361c4;
      --primary-light: #e0f2fe;
      --badge-green-bg: #dcfce7;
      --badge-green-text: #16a34a;
      --badge-pending-bg: #fef3c7;
      --badge-pending-text: #b45309;
      --badge-process-bg: #e0f2fe;
      --badge-process-text: #0369a1;
      --badge-shipped-bg: #dbeafe;
      --badge-shipped-text: #1d4ed8;
      --badge-cancel-bg: #fee2e2;
      --badge-cancel-text: #b91c1c;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: "Inter", system-ui, -apple-system, sans-serif;
      background: var(--bg);
      color: var(--text-main);
      -webkit-font-smoothing: antialiased;
      min-height: 100vh;
    }

    a {
      color: inherit;
      text-decoration: none;
    }

    button {
      font: inherit;
      color: inherit;
      background: none;
      border: 0;
      cursor: pointer;
    }

    /* =====================================================
       NAVBAR (Consistent Customer Store Navigation)
       ===================================================== */
    .navbar {
      display: flex;
      align-items: center;
      gap: 16px;
      padding: 14px 24px;
      border-bottom: 1px solid var(--border);
      background: #ffffff;
      position: sticky;
      top: 0;
      z-index: 90;
    }

    .logo {
      font-size: 22px;
      font-weight: 750;
      letter-spacing: -0.5px;
      white-space: nowrap;
    }

    .logo b {
      color: var(--primary);
    }

    .logo small {
      display: block;
      font-size: 8px;
      font-weight: 600;
      letter-spacing: 3px;
      text-align: center;
      color: var(--text-muted);
      margin-top: -2px;
    }

    .kategori {
      font-weight: 600;
      font-size: 14.5px;
      display: flex;
      align-items: center;
      gap: 6px;
      color: var(--text-main);
      transition: color 0.15s ease;
    }

    .kategori:hover {
      color: var(--primary);
    }

    .search {
      flex: 1;
      max-width: 320px;
      display: flex;
      align-items: center;
      gap: 8px;
      border: 1.5px solid #cbd5e1;
      border-radius: 9999px;
      padding: 0 14px;
      height: 38px;
      background: #ffffff;
      transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .search input {
      flex: 1;
      border: 0;
      outline: 0;
      font: inherit;
      min-width: 0;
      background: transparent;
      color: var(--text-main);
      font-size: 13px;
    }

    .search button {
      display: grid;
      place-items: center;
      color: var(--text-muted);
    }

    .search:focus-within {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(22, 115, 230, 0.12);
    }

    .nav-spacer {
      flex: 1;
    }

    .icon {
      width: 22px;
      height: 22px;
      stroke: currentColor;
      fill: none;
      stroke-width: 2;
      stroke-linecap: round;
      stroke-linejoin: round;
    }

    /* =====================================================
       PAGE CONTAINER & HEADER
       ===================================================== */
    .page-shell {
      max-width: 1180px;
      margin: 0 auto;
      padding: 28px 24px 56px;
    }

    .breadcrumb-nav {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 13px;
      color: var(--text-muted);
      margin-bottom: 14px;
    }

    .breadcrumb-nav a:hover {
      color: var(--primary);
    }

    .breadcrumb-current {
      color: var(--text-main);
      font-weight: 700;
    }

    .content-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      flex-wrap: wrap;
      margin-bottom: 24px;
    }

    .content-header h1 {
      font-size: 26px;
      font-weight: 750;
      color: #0f172a;
      letter-spacing: -0.5px;
    }

    .content-header p {
      font-size: 14px;
      color: var(--text-muted);
      margin-top: 4px;
    }

    .btn-primary-cta {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: var(--primary);
      color: #ffffff;
      font-size: 13px;
      font-weight: 600;
      padding: 9px 18px;
      border-radius: 8px;
      box-shadow: 0 1px 2px rgba(22, 115, 230, 0.2);
      transition: background 0.15s ease;
    }

    .btn-primary-cta:hover {
      background: var(--primary-hover);
    }

    /* Flash Banners */
    .alert-banner {
      padding: 13px 18px;
      border-radius: 10px;
      font-size: 13.5px;
      font-weight: 500;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .alert-success {
      background: #ecfdf5;
      border: 1px solid #a7f3d0;
      color: #065f46;
    }

    .alert-error {
      background: #fef2f2;
      border: 1px solid #fecaca;
      color: #991b1b;
    }

    /* =====================================================
       SUMMARY STATS CARDS (Admin Dashboard Style)
       ===================================================== */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
      margin-bottom: 22px;
    }

    .stat-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 16px 20px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .stat-icon {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      background: var(--primary-light);
      color: #0284c7;
      display: grid;
      place-items: center;
    }

    .stat-icon svg {
      width: 20px;
      height: 20px;
    }

    .stat-label {
      font-size: 13px;
      color: var(--text-muted);
      font-weight: 500;
      margin-top: 12px;
    }

    .stat-value {
      font-size: 24px;
      font-weight: 750;
      color: #0f172a;
      margin-top: 2px;
    }

    /* =====================================================
       FILTER BAR CARD
       ===================================================== */
    .filter-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 16px 20px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 14px;
      flex-wrap: wrap;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .filter-pills {
      display: flex;
      align-items: center;
      gap: 8px;
      flex-wrap: wrap;
    }

    .filter-pill {
      padding: 6px 14px;
      border-radius: 9999px;
      font-size: 12.5px;
      font-weight: 600;
      color: var(--text-muted);
      background: #f8fafc;
      border: 1px solid var(--border);
      transition: all 0.15s ease;
    }

    .filter-pill:hover {
      border-color: #cbd5e1;
      color: var(--text-main);
    }

    .filter-pill.active {
      background: var(--primary);
      border-color: var(--primary);
      color: #ffffff;
    }

    .order-search-form {
      position: relative;
      width: 260px;
    }

    .order-search-icon {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      width: 16px;
      height: 16px;
      color: var(--text-muted);
      pointer-events: none;
    }

    .order-search-input {
      width: 100%;
      height: 36px;
      border: 1.5px solid #cbd5e1;
      border-radius: 9999px;
      padding: 0 14px 0 36px;
      font-size: 12.5px;
      outline: none;
      color: var(--text-main);
      background: #ffffff;
    }

    .order-search-input:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(22, 115, 230, 0.12);
    }

    /* =====================================================
       ORDER HISTORY CARDS
       ===================================================== */
    .orders-stack {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .order-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
      transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .order-card:hover {
      border-color: #cbd5e1;
      box-shadow: 0 6px 18px -6px rgba(15, 23, 42, 0.06);
    }

    .order-card-header {
      padding: 15px 22px;
      background: #fafafa;
      border-bottom: 1px solid var(--border-light);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      flex-wrap: wrap;
    }

    .order-meta-left {
      display: flex;
      align-items: center;
      gap: 14px;
      flex-wrap: wrap;
    }

    .order-number {
      font-size: 14px;
      font-weight: 750;
      color: #0f172a;
    }

    .order-date {
      font-size: 12.5px;
      color: var(--text-muted);
    }

    .order-invoice-tag {
      font-size: 11.5px;
      font-weight: 600;
      color: #475569;
      background: #f1f5f9;
      padding: 3px 9px;
      border-radius: 6px;
    }

    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 11.5px;
      font-weight: 700;
      padding: 4px 12px;
      border-radius: 9999px;
      text-transform: capitalize;
    }

    .status-pending {
      background: var(--badge-pending-bg);
      color: var(--badge-pending-text);
    }

    .status-diproses {
      background: var(--badge-process-bg);
      color: var(--badge-process-text);
    }

    .status-dikirim {
      background: var(--badge-shipped-bg);
      color: var(--badge-shipped-text);
    }

    .status-selesai {
      background: var(--badge-green-bg);
      color: var(--badge-green-text);
    }

    .status-dibatalkan {
      background: var(--badge-cancel-bg);
      color: var(--badge-cancel-text);
    }

    .order-card-body {
      padding: 18px 22px;
    }

    .items-list {
      display: flex;
      flex-direction: column;
      gap: 14px;
    }

    .item-row {
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .item-thumb {
      width: 56px;
      height: 56px;
      border-radius: 10px;
      background: #f8fafc;
      border: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      flex-shrink: 0;
      padding: 4px;
    }

    .item-thumb img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
    }

    .item-details {
      flex: 1;
      min-width: 0;
    }

    .item-name {
      font-size: 14px;
      font-weight: 650;
      color: #0f172a;
      display: inline-block;
    }

    .item-name:hover {
      color: var(--primary);
    }

    .item-qty-price {
      font-size: 12.5px;
      color: var(--text-muted);
      margin-top: 3px;
    }

    .item-subtotal {
      font-size: 13.5px;
      font-weight: 700;
      color: #0f172a;
      white-space: nowrap;
      text-align: right;
    }

    .shipping-info-row {
      margin-top: 16px;
      padding-top: 14px;
      border-top: 1px dashed var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      flex-wrap: wrap;
      font-size: 12px;
      color: var(--text-muted);
    }

    .tracking-chip {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #eff6ff;
      border: 1px solid #bfdbfe;
      color: #1d4ed8;
      font-weight: 600;
      padding: 5px 12px;
      border-radius: 8px;
      font-size: 12px;
    }

    .order-card-footer {
      padding: 15px 22px;
      background: #fafafa;
      border-top: 1px solid var(--border-light);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      flex-wrap: wrap;
    }

    .price-breakdown {
      display: flex;
      flex-direction: column;
      gap: 3px;
    }

    .price-meta {
      font-size: 12px;
      color: var(--text-muted);
    }

    .price-grand-total {
      font-size: 15px;
      font-weight: 750;
      color: #0f172a;
    }

    .price-grand-total b {
      color: var(--primary);
      font-size: 17px;
      margin-left: 4px;
    }

    .order-actions {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
    }

    .btn-outline-neutral {
      border: 1.5px solid #cbd5e1;
      color: #334155;
      background: #ffffff;
      font-size: 12.5px;
      font-weight: 600;
      padding: 8px 14px;
      border-radius: 8px;
      transition: all 0.15s ease;
    }

    .btn-outline-neutral:hover {
      border-color: var(--primary);
      color: var(--primary);
      background: #eff6ff;
    }

    .btn-cancel-order {
      border: 1.5px solid #fca5a5;
      color: #ef4444;
      background: #ffffff;
      font-size: 12.5px;
      font-weight: 600;
      padding: 8px 16px;
      border-radius: 8px;
      transition: all 0.15s ease;
    }

    .btn-cancel-order:hover {
      background: #fef2f2;
      border-color: #ef4444;
    }

    .status-note {
      font-size: 12px;
      font-weight: 500;
      color: var(--text-muted);
    }

    .empty-card {
      background: var(--card-bg);
      border: 1px dashed #cbd5e1;
      border-radius: 14px;
      padding: 56px 24px;
      text-align: center;
    }

    .empty-card h2 {
      font-size: 17px;
      font-weight: 700;
      color: #0f172a;
      margin-top: 12px;
    }

    .empty-card p {
      font-size: 13.5px;
      color: var(--text-muted);
      margin: 6px 0 20px;
    }

    .pagination-wrap {
      margin-top: 22px;
      display: flex;
      justify-content: center;
    }

    @media (max-width: 900px) {
      .stats-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 600px) {
      .navbar {
        flex-wrap: wrap;
        padding: 12px 16px;
      }
      .search {
        order: 5;
        max-width: none;
        flex-basis: 100%;
      }
      .page-shell {
        padding: 18px 14px 40px;
      }
      .stats-grid {
        grid-template-columns: 1fr;
      }
      .order-search-form {
        width: 100%;
      }
      .order-card-header,
      .order-card-body,
      .order-card-footer {
        padding: 14px 16px;
      }
    }
  </style>
</head>
<body>

  @include('body.navbar')

  <main class="page-shell">
    <nav class="breadcrumb-nav" aria-label="Breadcrumb">
      <a href="{{ route('dashboard') }}">Katalog</a>
      <span>&gt;</span>
      <span class="breadcrumb-current">Riwayat Pesanan</span>
    </nav>

    <div class="content-header">
      <div>
        <h1>Riwayat Pesanan Saya</h1>
        <p>Pantau daftar produk pesanan, total pembayaran, dan kelola pembatalan pesanan.</p>
      </div>

      <a href="{{ route('dashboard') }}" class="btn-primary-cta">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
        </svg>
        <span>Belanja di Katalog</span>
      </a>
    </div>

    @if (session('success'))
      <div class="alert-banner alert-success" role="status">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <polyline points="20 6 9 17 4 12"/>
        </svg>
        <span>{{ session('success') }}</span>
      </div>
    @endif

    @if (session('error'))
      <div class="alert-banner alert-error" role="alert">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <span>{{ session('error') }}</span>
      </div>
    @endif

    <!-- Summary Cards -->
    <section class="stats-grid" aria-label="Ringkasan pesanan saya">
      <div class="stat-card">
        <div class="stat-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
            <rect x="9" y="3" width="6" height="4" rx="1"/>
          </svg>
        </div>
        <div class="stat-label">Total Pesanan</div>
        <div class="stat-value">{{ $orderStats['total'] }}</div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background:#fef3c7;color:#b45309;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
          </svg>
        </div>
        <div class="stat-label">Menunggu / Diproses</div>
        <div class="stat-value">{{ $orderStats['pending'] + $orderStats['diproses'] }}</div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background:#dbeafe;color:#1d4ed8;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
            <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
          </svg>
        </div>
        <div class="stat-label">Dalam Pengiriman</div>
        <div class="stat-value">{{ $orderStats['dikirim'] }}</div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background:#dcfce7;color:#16a34a;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
          </svg>
        </div>
        <div class="stat-label">Selesai / Dibatalkan</div>
        <div class="stat-value">{{ $orderStats['selesai'] }} <span style="font-size:13px;font-weight:500;color:#64748b;">/ {{ $orderStats['dibatalkan'] }} batal</span></div>
      </div>
    </section>

    <!-- Filter & Search Bar -->
    <section class="filter-card" aria-label="Filter riwayat pesanan">
      <div class="filter-pills">
        <a href="{{ route('riwayat-pesanan', ['search' => $filters['search'] ?? null]) }}"
           class="filter-pill {{ empty($filters['status']) ? 'active' : '' }}">
          Semua ({{ $orderStats['total'] }})
        </a>
        <a href="{{ route('riwayat-pesanan', ['status' => 'pending', 'search' => $filters['search'] ?? null]) }}"
           class="filter-pill {{ ($filters['status'] ?? '') === 'pending' ? 'active' : '' }}">
          Pending ({{ $orderStats['pending'] }})
        </a>
        <a href="{{ route('riwayat-pesanan', ['status' => 'diproses', 'search' => $filters['search'] ?? null]) }}"
           class="filter-pill {{ ($filters['status'] ?? '') === 'diproses' ? 'active' : '' }}">
          Diproses ({{ $orderStats['diproses'] }})
        </a>
        <a href="{{ route('riwayat-pesanan', ['status' => 'dikirim', 'search' => $filters['search'] ?? null]) }}"
           class="filter-pill {{ ($filters['status'] ?? '') === 'dikirim' ? 'active' : '' }}">
          Dikirim ({{ $orderStats['dikirim'] }})
        </a>
        <a href="{{ route('riwayat-pesanan', ['status' => 'selesai', 'search' => $filters['search'] ?? null]) }}"
           class="filter-pill {{ ($filters['status'] ?? '') === 'selesai' ? 'active' : '' }}">
          Selesai ({{ $orderStats['selesai'] }})
        </a>
        <a href="{{ route('riwayat-pesanan', ['status' => 'dibatalkan', 'search' => $filters['search'] ?? null]) }}"
           class="filter-pill {{ ($filters['status'] ?? '') === 'dibatalkan' ? 'active' : '' }}">
          Dibatalkan ({{ $orderStats['dibatalkan'] }})
        </a>
      </div>

      <form method="GET" action="{{ route('riwayat-pesanan') }}" class="order-search-form">
        @if (!empty($filters['status']))
          <input type="hidden" name="status" value="{{ $filters['status'] }}">
        @endif
        <svg class="order-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input
          type="search"
          name="search"
          value="{{ $filters['search'] ?? '' }}"
          class="order-search-input"
          placeholder="Cari no. pesanan / produk..."
          aria-label="Cari pesanan"
        >
      </form>
    </section>

    <!-- Order List -->
    <section class="orders-stack" aria-label="Daftar pesanan">
      @forelse ($orders as $order)
        <article class="order-card">
          <header class="order-card-header">
            <div class="order-meta-left">
              <span class="order-number">{{ $order->nomor_pesanan }}</span>
              <span class="order-date">
                {{ $order->tanggal_pesanan ? $order->tanggal_pesanan->format('d M Y, H:i') : '-' }}
              </span>
              @if ($order->invoice)
                <span class="order-invoice-tag">{{ $order->invoice->nomor_invoice }}</span>
              @endif
            </div>

            <span class="status-badge status-{{ $order->status_pemesanan }}">
              {{ ucfirst($order->status_pemesanan) }}
            </span>
          </header>

          <div class="order-card-body">
            <div class="items-list">
              @forelse ($order->items as $item)
                <div class="item-row">
                  <div class="item-thumb">
                    @if ($item->produk?->gambar_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->produk->gambar_path))
                      <img src="{{ $item->produk->gambar_url }}" alt="{{ $item->produk->nama_produk }}">
                    @else
                      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5">
                        <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                      </svg>
                    @endif
                  </div>

                  <div class="item-details">
                    @if ($item->produk)
                      <a href="{{ route('produk.detail', $item->id_produk) }}" class="item-name">
                        {{ $item->produk->nama_produk }}
                      </a>
                    @else
                      <span class="item-name">Produk #{{ $item->id_produk }}</span>
                    @endif
                    <div class="item-qty-price">
                      {{ $item->jumlah }} barang &times; Rp {{ number_format((float) $item->harga_satuan, 0, ',', '.') }}
                    </div>
                  </div>

                  <div class="item-subtotal">
                    Rp {{ number_format((float) $item->subtotal_item, 0, ',', '.') }}
                  </div>
                </div>
              @empty
                <p style="font-size:13px;color:#64748b;">Tidak ada item produk pada pesanan ini.</p>
              @endforelse
            </div>

            @if ($order->alamat || $order->pengiriman)
              <div class="shipping-info-row">
                <div>
                  @if ($order->alamat)
                    <strong>Tujuan:</strong> {{ $order->alamat->nama_penerima }} &mdash; {{ $order->alamat->alamat_lengkap }}, {{ $order->alamat->kota }}
                  @endif
                </div>

                @if ($order->pengiriman)
                  <div class="tracking-chip">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
                      <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                    </svg>
                    <span>{{ $order->pengiriman->kurir }} ({{ $order->pengiriman->layanan }}) &middot; Resi: {{ $order->pengiriman->nomor_resi }}</span>
                  </div>
                @endif
              </div>
            @endif
          </div>

          <footer class="order-card-footer">
            <div class="price-breakdown">
              <span class="price-meta">
                Subtotal: Rp {{ number_format((float) $order->subtotal, 0, ',', '.') }}
                &middot; Ongkir: Rp {{ number_format((float) $order->biaya_pengiriman, 0, ',', '.') }}
                @if ((float) $order->pajak > 0)
                  &middot; Pajak: Rp {{ number_format((float) $order->pajak, 0, ',', '.') }}
                @endif
              </span>
              <div class="price-grand-total">
                Total Harga: <b>Rp {{ number_format((float) $order->total_pembayaran, 0, ',', '.') }}</b>
              </div>
            </div>

            <div class="order-actions">
              <a href="{{ route('checkout.success', ['order_id' => $order->id_pesanan]) }}" class="btn-outline-neutral">
                Lihat Struk
              </a>

              @if ($order->canBeCancelled())
                <form action="{{ route('riwayat-pesanan.cancel', $order->id_pesanan) }}"
                      method="POST"
                      onsubmit="return confirm('Yakin ingin membatalkan pesanan {{ $order->nomor_pesanan }}?')">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="btn-cancel-order">Batalkan Pesanan</button>
                </form>
              @elseif ($order->status_pemesanan === 'dikirim')
                <span class="status-note">Pesanan sedang dikirim oleh admin</span>
              @elseif ($order->status_pemesanan === 'dibatalkan')
                <span class="status-note" style="color:#dc2626;">Pesanan dibatalkan</span>
              @endif
            </div>
          </footer>
        </article>
      @empty
        <div class="empty-card">
          <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.6" style="margin:0 auto;">
            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
            <rect x="9" y="3" width="6" height="4" rx="1"/>
          </svg>
          <h2>Belum ada riwayat pesanan</h2>
          <p>Pesanan yang Anda checkout akan muncul di sini beserta status pengirimannya.</p>
          <a href="{{ route('dashboard') }}" class="btn-primary-cta">Mulai Belanja</a>
        </div>
      @endforelse
    </section>

    @if ($orders->hasPages())
      <div class="pagination-wrap">{{ $orders->links() }}</div>
    @endif
  </main>

</body>
</html>
