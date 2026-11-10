<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $activeTab === 'produk' ? 'Manajemen Produk' : 'Manajemen Pesanan' }} | AuthenSIX Computer</title>
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
      --sidebar-width: 240px;
      --header-height: 64px;
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
      display: flex;
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
       SIDEBAR (Consistent with Dashboard Admin)
       ===================================================== */
    .sidebar {
      width: var(--sidebar-width);
      background: #ffffff;
      border-right: 1px solid var(--border);
      display: flex;
      flex-direction: column;
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      z-index: 100;
    }

    .sidebar-brand {
      padding: 24px 24px 20px;
    }

    .brand-link {
      display: inline-block;
    }

    .brand-text {
      font-size: 21px;
      font-weight: 750;
      letter-spacing: -0.5px;
      color: #0f172a;
    }

    .brand-text b {
      color: var(--primary);
    }

    .brand-sub {
      display: block;
      font-size: 8px;
      font-weight: 600;
      letter-spacing: 3px;
      text-align: center;
      color: var(--text-muted);
      margin-top: -3px;
    }

    .sidebar-nav {
      padding: 16px 14px;
      display: flex;
      flex-direction: column;
      gap: 6px;
      flex: 1;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 16px;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 500;
      color: var(--text-muted);
      transition: all 0.15s ease;
    }

    .nav-item:hover {
      background: #f1f5f9;
      color: var(--text-main);
    }

    .nav-item.active {
      background: var(--primary);
      color: #ffffff;
      font-weight: 600;
    }

    .nav-icon {
      width: 20px;
      height: 20px;
      stroke-width: 2;
      flex-shrink: 0;
    }

    .sidebar-footer {
      padding: 16px 14px;
      border-top: 1px solid var(--border-light);
    }

    .user-card {
      background: #f8fafc;
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 10px 12px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .user-avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: #e2e8f0;
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 13px;
      color: #0284c7;
    }

    .user-meta {
      flex: 1;
      min-width: 0;
    }

    .user-name {
      font-size: 13px;
      font-weight: 700;
      color: var(--text-main);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .user-role {
      font-size: 11px;
      color: var(--text-muted);
      text-transform: capitalize;
    }

    .logout-form {
      display: flex;
      align-items: center;
    }

    .logout-btn {
      color: var(--text-muted);
      padding: 6px;
      border-radius: 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.15s ease;
    }

    .logout-btn:hover {
      color: #ef4444;
      background: #fee2e2;
    }

    .logout-btn svg {
      width: 18px;
      height: 18px;
    }

    /* =====================================================
       MAIN LAYOUT & TOP HEADER
       ===================================================== */
    .app-main {
      margin-left: var(--sidebar-width);
      flex: 1;
      display: flex;
      flex-direction: column;
      min-width: 0;
    }

    .top-header {
      height: var(--header-height);
      background: #ffffff;
      border-bottom: 1px solid var(--border);
      padding: 0 32px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 90;
      gap: 16px;
    }

    .breadcrumb-nav {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 13.5px;
      color: var(--text-muted);
    }

    .breadcrumb-nav a:hover {
      color: var(--primary);
    }

    .breadcrumb-sep {
      color: var(--text-sub);
    }

    .breadcrumb-current {
      color: var(--text-main);
      font-weight: 700;
    }

    .header-right {
      display: flex;
      align-items: center;
      gap: 18px;
    }

    .search-box {
      position: relative;
      width: 280px;
    }

    .search-icon {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      width: 17px;
      height: 17px;
      color: var(--text-muted);
      pointer-events: none;
    }

    .search-input {
      width: 100%;
      height: 38px;
      border: 1.5px solid #cbd5e1;
      border-radius: 9999px;
      padding: 0 14px 0 38px;
      font-size: 13px;
      color: var(--text-main);
      background: #ffffff;
      outline: none;
      transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .search-input:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(22, 115, 230, 0.12);
    }

    .header-divider {
      width: 1px;
      height: 28px;
      background: var(--border);
    }

    .header-user {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .header-avatar {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      background: #0284c7;
      color: #ffffff;
      font-weight: 700;
      font-size: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .header-username {
      font-size: 13px;
      font-weight: 650;
      letter-spacing: 0.3px;
      color: var(--text-main);
    }

    /* =====================================================
       CONTENT AREA
       ===================================================== */
    .dashboard-content {
      padding: 28px 32px 48px;
      max-width: 1400px;
      width: 100%;
    }

    .content-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
      gap: 16px;
      flex-wrap: wrap;
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

    .content-actions {
      display: flex;
      align-items: center;
      gap: 12px;
      flex-wrap: wrap;
    }

    .tab-switcher {
      display: inline-flex;
      border: 1.5px solid #cbd5e1;
      border-radius: 10px;
      overflow: hidden;
      background: #ffffff;
    }

    .tab-switch-link {
      padding: 8px 16px;
      font-size: 13px;
      font-weight: 600;
      color: var(--text-muted);
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.15s ease;
    }

    .tab-switch-link:hover {
      background: #f8fafc;
      color: var(--text-main);
    }

    .tab-switch-link.active {
      background: #eff6ff;
      color: var(--primary);
    }

    .tab-count {
      font-size: 11px;
      padding: 1px 7px;
      border-radius: 9999px;
      background: #f1f5f9;
      color: var(--text-muted);
      font-weight: 700;
    }

    .tab-switch-link.active .tab-count {
      background: var(--primary);
      color: #ffffff;
    }

    .btn-add-product {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: var(--primary);
      color: #ffffff;
      font-size: 13px;
      font-weight: 600;
      padding: 9px 18px;
      border-radius: 8px;
      box-shadow: 0 1px 2px rgba(22, 115, 230, 0.2);
      transition: background 0.15s ease;
    }

    .btn-add-product:hover {
      background: var(--primary-hover);
    }

    /* Flash Alerts */
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
       STAT CARDS
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

    .stat-top {
      display: flex;
      align-items: center;
      justify-content: space-between;
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
       MAIN CARD & FILTERS
       ===================================================== */
    .main-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 20px 24px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .filter-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      flex-wrap: wrap;
      margin-bottom: 18px;
      padding-bottom: 16px;
      border-bottom: 1px solid var(--border-light);
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
      display: inline-flex;
      align-items: center;
      gap: 6px;
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

    /* =====================================================
       TABLES & ORDER ROWS
       ===================================================== */
    .table-responsive {
      width: 100%;
      overflow-x: auto;
    }

    .data-table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
    }

    .data-table thead th {
      font-size: 12px;
      font-weight: 600;
      color: var(--text-muted);
      padding: 11px 14px;
      border-bottom: 1px solid var(--border);
      background: #fafafa;
      white-space: nowrap;
    }

    .data-table thead th:first-child {
      border-radius: 8px 0 0 8px;
    }

    .data-table thead th:last-child {
      border-radius: 0 8px 8px 0;
      text-align: right;
    }

    .data-table tbody td {
      padding: 16px 14px;
      border-bottom: 1px solid var(--border-light);
      font-size: 13px;
      vertical-align: top;
    }

    .data-table tbody tr:hover td {
      background: #fafcff;
    }

    /* Order Specific Cells */
    .order-id {
      font-weight: 700;
      color: #0f172a;
      font-size: 13.5px;
    }

    .order-date {
      font-size: 11.5px;
      color: var(--text-muted);
      margin-top: 3px;
    }

    .customer-name {
      font-weight: 600;
      color: #1e293b;
      margin-top: 8px;
      font-size: 12.5px;
    }

    .customer-addr {
      font-size: 11.5px;
      color: var(--text-muted);
      margin-top: 2px;
      max-width: 240px;
      line-height: 1.4;
    }

    .order-items-list {
      display: flex;
      flex-direction: column;
      gap: 8px;
      min-width: 260px;
    }

    .order-item-row {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .item-thumb {
      width: 38px;
      height: 38px;
      border-radius: 6px;
      border: 1px solid var(--border);
      background: #f8fafc;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      flex-shrink: 0;
      padding: 2px;
    }

    .item-thumb img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
    }

    .item-info {
      min-width: 0;
      flex: 1;
    }

    .item-name {
      font-weight: 600;
      color: #0f172a;
      font-size: 12.5px;
      line-height: 1.3;
    }

    .item-meta {
      font-size: 11.5px;
      color: var(--text-muted);
      margin-top: 2px;
    }

    .price-total {
      font-weight: 750;
      font-size: 14px;
      color: var(--primary);
      white-space: nowrap;
    }

    .price-sub {
      font-size: 11px;
      color: var(--text-muted);
      margin-top: 3px;
      white-space: nowrap;
    }

    /* Status Badges */
    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 11.5px;
      font-weight: 700;
      padding: 4px 12px;
      border-radius: 9999px;
      text-transform: capitalize;
      white-space: nowrap;
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

    .status-selesai,
    .status-aktif {
      background: var(--badge-green-bg);
      color: var(--badge-green-text);
    }

    .status-dibatalkan,
    .status-nonaktif {
      background: var(--badge-cancel-bg);
      color: var(--badge-cancel-text);
    }

    .resi-chip {
      margin-top: 6px;
      display: inline-block;
      font-size: 11px;
      font-weight: 600;
      color: #0369a1;
      background: #f0f9ff;
      border: 1px solid #bae6fd;
      border-radius: 6px;
      padding: 3px 8px;
    }

    /* Action Buttons */
    .actions-col {
      text-align: right;
      min-width: 190px;
    }

    .action-stack {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: 8px;
    }

    .action-row {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: 8px;
      flex-wrap: wrap;
    }

    .btn-acc-ship {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: var(--primary);
      color: #ffffff;
      font-size: 12px;
      font-weight: 600;
      padding: 7px 14px;
      border-radius: 8px;
      transition: background 0.15s ease;
      white-space: nowrap;
    }

    .btn-acc-ship:hover {
      background: var(--primary-hover);
    }

    .btn-complete {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      background: #16a34a;
      color: #ffffff;
      font-size: 12px;
      font-weight: 600;
      padding: 7px 14px;
      border-radius: 8px;
      transition: background 0.15s ease;
    }

    .btn-complete:hover {
      background: #15803d;
    }

    .btn-outline-danger {
      border: 1.5px solid #fca5a5;
      color: #ef4444;
      background: #ffffff;
      font-size: 12px;
      font-weight: 600;
      padding: 6px 12px;
      border-radius: 8px;
      transition: all 0.15s ease;
    }

    .btn-outline-danger:hover {
      background: #fef2f2;
      border-color: #ef4444;
    }

    .btn-outline-neutral {
      border: 1.5px solid #cbd5e1;
      color: #334155;
      background: #ffffff;
      font-size: 12px;
      font-weight: 600;
      padding: 6px 12px;
      border-radius: 8px;
      transition: all 0.15s ease;
    }

    .btn-outline-neutral:hover {
      border-color: var(--primary);
      color: var(--primary);
      background: #eff6ff;
    }

    .resi-details {
      width: 100%;
      max-width: 240px;
      text-align: left;
    }

    .resi-details summary {
      font-size: 11px;
      color: var(--text-muted);
      cursor: pointer;
      text-align: right;
      user-select: none;
    }

    .resi-details summary:hover {
      color: var(--primary);
    }

    .resi-inputs {
      margin-top: 8px;
      padding: 10px;
      background: #f8fafc;
      border: 1px solid var(--border);
      border-radius: 8px;
      display: grid;
      gap: 6px;
    }

    .resi-input {
      width: 100%;
      border: 1px solid #cbd5e1;
      border-radius: 6px;
      padding: 6px 8px;
      font-size: 11.5px;
      background: #ffffff;
      outline: none;
    }

    .resi-input:focus {
      border-color: var(--primary);
    }

    .empty-state {
      text-align: center;
      padding: 48px 20px;
      color: var(--text-muted);
      font-size: 13.5px;
    }

    .pagination-wrap {
      margin-top: 20px;
      display: flex;
      justify-content: flex-end;
    }

    /* =====================================================
       RESPONSIVE
       ===================================================== */
    @media (max-width: 1100px) {
      .stats-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 768px) {
      :root {
        --sidebar-width: 70px;
      }
      .brand-sub,
      .nav-item span,
      .user-meta,
      .header-username {
        display: none;
      }
      .nav-item {
        justify-content: center;
        padding: 12px;
      }
      .sidebar-brand {
        padding: 16px 8px;
        text-align: center;
      }
      .user-card {
        padding: 6px;
        justify-content: center;
      }
      .logout-btn {
        display: none;
      }
      .stats-grid {
        grid-template-columns: 1fr;
      }
      .top-header {
        padding: 0 16px;
      }
      .search-box {
        width: 160px;
      }
      .dashboard-content {
        padding: 16px;
      }
    }
  </style>
</head>
<body>

  <!-- =====================================================
       LEFT SIDEBAR
       ===================================================== -->
  <aside class="sidebar" aria-label="Menu Admin">
    <div class="sidebar-brand">
      <a href="{{ route('admin.dashboard-admin') }}" class="brand-link" aria-label="AuthenSIX Computer">
        <div class="brand-text">Authen<b>SIX</b></div>
        <small class="brand-sub">COMPUTER</small>
      </a>
    </div>

    <nav class="sidebar-nav">
      <a href="{{ route('admin.dashboard-admin') }}" class="nav-item">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
          <polyline points="9 22 9 12 15 12 15 22"/>
        </svg>
        <span>Dashboard</span>
      </a>

      <a href="{{ route('admin.manajemen_produk', ['tab' => 'pesanan']) }}"
         class="nav-item {{ $activeTab === 'pesanan' ? 'active' : '' }}"
         @if($activeTab === 'pesanan') aria-current="page" @endif>
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
          <rect x="9" y="3" width="6" height="4" rx="1"/>
          <path d="M9 12h6"/>
          <path d="M9 16h6"/>
        </svg>
        <span>Manajemen Pesanan</span>
      </a>

      <a href="{{ route('admin.manajemen_produk', ['tab' => 'produk']) }}"
         class="nav-item {{ $activeTab === 'produk' ? 'active' : '' }}"
         @if($activeTab === 'produk') aria-current="page" @endif>
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
          <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
          <line x1="12" y1="22.08" x2="12" y2="12"/>
        </svg>
        <span>Product</span>
      </a>

      <a href="{{ route('admin.tambah_produk') }}" class="nav-item">
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

  <!-- =====================================================
       MAIN LAYOUT
       ===================================================== -->
  <div class="app-main">
    <header class="top-header">
      <nav class="breadcrumb-nav" aria-label="Breadcrumb">
        <a href="{{ route('admin.dashboard-admin') }}">Dashboard</a>
        <span class="breadcrumb-sep">&gt;</span>
        <span class="breadcrumb-current">{{ $activeTab === 'produk' ? 'Manajemen Produk' : 'Manajemen Pesanan' }}</span>
      </nav>

      <div class="header-right">
        <form method="GET" action="{{ route('admin.manajemen_produk') }}" class="search-box">
          <input type="hidden" name="tab" value="{{ $activeTab }}">
          @if ($activeTab === 'pesanan' && !empty($filters['status']))
            <input type="hidden" name="status" value="{{ $filters['status'] }}">
          @endif
          <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <circle cx="11" cy="11" r="8"/>
            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
          </svg>
          <input
            type="search"
            name="search"
            value="{{ $filters['search'] ?? '' }}"
            class="search-input"
            placeholder="{{ $activeTab === 'produk' ? 'Cari produk atau SKU...' : 'Cari order, kostumer, produk...' }}"
            aria-label="Cari"
          >
        </form>

        <div class="header-divider"></div>

        <div class="header-user">
          <div class="header-avatar">
            {{ strtoupper(substr(auth()->user()->nama_lengkap ?? auth()->user()->username ?? 'A', 0, 2)) }}
          </div>
          <span class="header-username">{{ strtoupper(auth()->user()->nama_lengkap ?? auth()->user()->username ?? 'ADMIN') }}</span>
        </div>
      </div>
    </header>

    <main class="dashboard-content">
      <div class="content-header">
        <div>
          <h1>{{ $activeTab === 'produk' ? 'Manajemen Produk' : 'Manajemen Pesanan' }}</h1>
          <p>
            {{ $activeTab === 'produk'
                ? 'Kelola katalog produk, stok barang, dan status penayangan toko.'
                : 'Pantau seluruh pesanan kostumer dan konfirmasi status pengiriman (ACC Dikirim).' }}
          </p>
        </div>

        <div class="content-actions">
          <div class="tab-switcher" role="tablist" aria-label="Mode manajemen">
            <a href="{{ route('admin.manajemen_produk', ['tab' => 'pesanan']) }}"
               class="tab-switch-link {{ $activeTab === 'pesanan' ? 'active' : '' }}"
               role="tab"
               aria-selected="{{ $activeTab === 'pesanan' ? 'true' : 'false' }}">
              <span>Pesanan</span>
              <span class="tab-count">{{ $orderStats['total'] }}</span>
            </a>
            <a href="{{ route('admin.manajemen_produk', ['tab' => 'produk']) }}"
               class="tab-switch-link {{ $activeTab === 'produk' ? 'active' : '' }}"
               role="tab"
               aria-selected="{{ $activeTab === 'produk' ? 'true' : 'false' }}">
              <span>Produk</span>
              <span class="tab-count">{{ $productStats['total'] }}</span>
            </a>
          </div>

          <a href="{{ route('admin.tambah_produk') }}" class="btn-add-product">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <line x1="12" y1="5" x2="12" y2="19"/>
              <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            <span>Tambah produk</span>
          </a>
        </div>
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
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
          </svg>
          <span>{{ session('error') }}</span>
        </div>
      @endif

      @if ($activeTab === 'pesanan')
        <!-- =====================================================
             TAB 1: MANAJEMEN PESANAN
             ===================================================== -->
        <section class="stats-grid" aria-label="Ringkasan pesanan">
          <div class="stat-card">
            <div class="stat-top">
              <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                  <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
              </div>
            </div>
            <div class="stat-label">Total pesanan</div>
            <div class="stat-value">{{ $orderStats['total'] }}</div>
          </div>

          <div class="stat-card">
            <div class="stat-top">
              <div class="stat-icon" style="background:#fef3c7;color:#b45309;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
              </div>
            </div>
            <div class="stat-label">Perlu ACC / Pending</div>
            <div class="stat-value">{{ $orderStats['pending'] + $orderStats['diproses'] }}</div>
          </div>

          <div class="stat-card">
            <div class="stat-top">
              <div class="stat-icon" style="background:#dbeafe;color:#1d4ed8;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
                  <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                </svg>
              </div>
            </div>
            <div class="stat-label">Sedang dikirim</div>
            <div class="stat-value">{{ $orderStats['dikirim'] }}</div>
          </div>

          <div class="stat-card">
            <div class="stat-top">
              <div class="stat-icon" style="background:#dcfce7;color:#16a34a;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
              </div>
            </div>
            <div class="stat-label">Selesai / Dibatalkan</div>
            <div class="stat-value">{{ $orderStats['selesai'] }} <span style="font-size:13px;font-weight:500;color:#64748b;">/ {{ $orderStats['dibatalkan'] }} batal</span></div>
          </div>
        </section>

        <section class="main-card" aria-label="Daftar semua pesanan kostumer">
          <div class="filter-bar">
            <div class="filter-pills">
              <a href="{{ route('admin.manajemen_produk', ['tab' => 'pesanan', 'search' => $filters['search'] ?? null]) }}"
                 class="filter-pill {{ empty($filters['status']) ? 'active' : '' }}">
                Semua ({{ $orderStats['total'] }})
              </a>
              <a href="{{ route('admin.manajemen_produk', ['tab' => 'pesanan', 'status' => 'pending', 'search' => $filters['search'] ?? null]) }}"
                 class="filter-pill {{ ($filters['status'] ?? '') === 'pending' ? 'active' : '' }}">
                Pending ({{ $orderStats['pending'] }})
              </a>
              <a href="{{ route('admin.manajemen_produk', ['tab' => 'pesanan', 'status' => 'diproses', 'search' => $filters['search'] ?? null]) }}"
                 class="filter-pill {{ ($filters['status'] ?? '') === 'diproses' ? 'active' : '' }}">
                Diproses ({{ $orderStats['diproses'] }})
              </a>
              <a href="{{ route('admin.manajemen_produk', ['tab' => 'pesanan', 'status' => 'dikirim', 'search' => $filters['search'] ?? null]) }}"
                 class="filter-pill {{ ($filters['status'] ?? '') === 'dikirim' ? 'active' : '' }}">
                Dikirim ({{ $orderStats['dikirim'] }})
              </a>
              <a href="{{ route('admin.manajemen_produk', ['tab' => 'pesanan', 'status' => 'selesai', 'search' => $filters['search'] ?? null]) }}"
                 class="filter-pill {{ ($filters['status'] ?? '') === 'selesai' ? 'active' : '' }}">
                Selesai ({{ $orderStats['selesai'] }})
              </a>
              <a href="{{ route('admin.manajemen_produk', ['tab' => 'pesanan', 'status' => 'dibatalkan', 'search' => $filters['search'] ?? null]) }}"
                 class="filter-pill {{ ($filters['status'] ?? '') === 'dibatalkan' ? 'active' : '' }}">
                Dibatalkan ({{ $orderStats['dibatalkan'] }})
              </a>
            </div>
          </div>

          <div class="table-responsive">
            <table class="data-table">
              <thead>
                <tr>
                  <th scope="col">Pesanan &amp; Kostumer</th>
                  <th scope="col">Daftar Produk</th>
                  <th scope="col">Total Harga</th>
                  <th scope="col">Status</th>
                  <th scope="col">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($orders as $order)
                  <tr>
                    <td>
                      <div class="order-id">{{ $order->nomor_pesanan }}</div>
                      <div class="order-date">
                        {{ $order->tanggal_pesanan ? $order->tanggal_pesanan->format('d M Y, H:i') : '-' }}
                      </div>
                      <div class="customer-name">
                        {{ $order->user->nama_lengkap ?? 'Kostumer' }}
                        @if ($order->user?->nomor_telepon)
                          <span style="font-weight:400;color:#64748b;">({{ $order->user->nomor_telepon }})</span>
                        @endif
                      </div>
                      @if ($order->alamat)
                        <div class="customer-addr">
                          {{ $order->alamat->alamat_lengkap }}, {{ $order->alamat->kota }}
                        </div>
                      @endif
                    </td>

                    <td>
                      <div class="order-items-list">
                        @forelse ($order->items as $item)
                          <div class="order-item-row">
                            <div class="item-thumb">
                              @if ($item->produk?->gambar_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->produk->gambar_path))
                                <img src="{{ $item->produk->gambar_url }}" alt="{{ $item->produk->nama_produk }}">
                              @else
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5">
                                  <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                                </svg>
                              @endif
                            </div>
                            <div class="item-info">
                              <div class="item-name">{{ $item->produk->nama_produk ?? 'Produk #'.$item->id_produk }}</div>
                              <div class="item-meta">
                                {{ $item->jumlah }} &times; Rp {{ number_format((float) $item->harga_satuan, 0, ',', '.') }}
                                = <strong>Rp {{ number_format((float) $item->subtotal_item, 0, ',', '.') }}</strong>
                              </div>
                            </div>
                          </div>
                        @empty
                          <span style="color:#94a3b8;font-size:12px;">Tidak ada item produk.</span>
                        @endforelse
                      </div>
                    </td>

                    <td>
                      <div class="price-total">Rp {{ number_format((float) $order->total_pembayaran, 0, ',', '.') }}</div>
                      <div class="price-sub">Subtotal: Rp {{ number_format((float) $order->subtotal, 0, ',', '.') }}</div>
                      <div class="price-sub">Ongkir: Rp {{ number_format((float) $order->biaya_pengiriman, 0, ',', '.') }}</div>
                    </td>

                    <td>
                      <span class="status-badge status-{{ $order->status_pemesanan }}">
                        {{ ucfirst($order->status_pemesanan) }}
                      </span>
                      @if ($order->pengiriman)
                        <div>
                          <span class="resi-chip">
                            {{ $order->pengiriman->kurir }} &middot; {{ $order->pengiriman->nomor_resi }}
                          </span>
                        </div>
                      @endif
                    </td>

                    <td class="actions-col">
                      @if (in_array($order->status_pemesanan, ['pending', 'diproses'], true))
                        <div class="action-stack">
                          <form action="{{ route('admin.orders.status', $order->id_pesanan) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status_pemesanan" value="dikirim">

                            <div class="action-row">
                              <button type="submit" class="btn-acc-ship">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                  <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                <span>ACC Dikirim</span>
                              </button>
                            </div>

                            <details class="resi-details">
                              <summary>+ Atur kurir &amp; resi (opsional)</summary>
                              <div class="resi-inputs">
                                <input type="text" name="kurir" class="resi-input" placeholder="Kurir (cth: JNE / SiCepat)">
                                <input type="text" name="layanan" class="resi-input" placeholder="Layanan (cth: REG)">
                                <input type="text" name="nomor_resi" class="resi-input" placeholder="No. Resi (otomatis jika kosong)">
                              </div>
                            </details>
                          </form>

                          <form action="{{ route('admin.orders.status', $order->id_pesanan) }}" method="POST"
                                onsubmit="return confirm('Batalkan pesanan {{ $order->nomor_pesanan }} dan kembalikan stok produk?')">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status_pemesanan" value="dibatalkan">
                            <button type="submit" class="btn-outline-danger">Batalkan</button>
                          </form>
                        </div>
                      @elseif ($order->status_pemesanan === 'dikirim')
                        <form action="{{ route('admin.orders.status', $order->id_pesanan) }}" method="POST">
                          @csrf
                          @method('PATCH')
                          <input type="hidden" name="status_pemesanan" value="selesai">
                          <button type="submit" class="btn-complete">Tandai Selesai</button>
                        </form>
                      @else
                        <span style="font-size:12px;color:#94a3b8;font-weight:500;">
                          {{ $order->status_pemesanan === 'selesai' ? 'Pesanan Selesai' : 'Dibatalkan' }}
                        </span>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="empty-state">
                      Belum ada pesanan kostumer yang sesuai dengan filter.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          @if ($orders->hasPages())
            <div class="pagination-wrap">{{ $orders->links() }}</div>
          @endif
        </section>

      @else
        <!-- =====================================================
             TAB 2: MANAJEMEN PRODUK
             ===================================================== -->
        <section class="stats-grid" aria-label="Ringkasan produk">
          <div class="stat-card">
            <div class="stat-top">
              <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                </svg>
              </div>
            </div>
            <div class="stat-label">Total produk</div>
            <div class="stat-value">{{ $productStats['total'] }}</div>
          </div>

          <div class="stat-card">
            <div class="stat-top">
              <div class="stat-icon" style="background:#dcfce7;color:#16a34a;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="20 6 9 17 4 12"/>
                </svg>
              </div>
            </div>
            <div class="stat-label">Produk aktif</div>
            <div class="stat-value">{{ $productStats['aktif'] }}</div>
          </div>

          <div class="stat-card">
            <div class="stat-top">
              <div class="stat-icon" style="background:#fee2e2;color:#b91c1c;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                </svg>
              </div>
            </div>
            <div class="stat-label">Produk nonaktif</div>
            <div class="stat-value">{{ $productStats['nonaktif'] }}</div>
          </div>

          <div class="stat-card">
            <div class="stat-top">
              <div class="stat-icon" style="background:#fef3c7;color:#b45309;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                  <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
              </div>
            </div>
            <div class="stat-label">Stok menipis (&le; 5)</div>
            <div class="stat-value">{{ $productStats['stok_menipis'] }}</div>
          </div>
        </section>

        <section class="main-card" aria-label="Daftar produk toko">
          <div class="filter-bar">
            <div class="filter-pills">
              <a href="{{ route('admin.manajemen_produk', ['tab' => 'produk', 'product_status' => 'all', 'search' => $filters['search'] ?? null]) }}"
                 class="filter-pill {{ ($filters['product_status'] ?? 'all') === 'all' ? 'active' : '' }}">
                Semua ({{ $productStats['total'] }})
              </a>
              <a href="{{ route('admin.manajemen_produk', ['tab' => 'produk', 'product_status' => 'active', 'search' => $filters['search'] ?? null]) }}"
                 class="filter-pill {{ ($filters['product_status'] ?? '') === 'active' ? 'active' : '' }}">
                Aktif ({{ $productStats['aktif'] }})
              </a>
              <a href="{{ route('admin.manajemen_produk', ['tab' => 'produk', 'product_status' => 'inactive', 'search' => $filters['search'] ?? null]) }}"
                 class="filter-pill {{ ($filters['product_status'] ?? '') === 'inactive' ? 'active' : '' }}">
                Nonaktif ({{ $productStats['nonaktif'] }})
              </a>
            </div>
          </div>

          <div class="table-responsive">
            <table class="data-table">
              <thead>
                <tr>
                  <th scope="col">Produk</th>
                  <th scope="col">Kategori &amp; Merek</th>
                  <th scope="col">Harga</th>
                  <th scope="col">Stok</th>
                  <th scope="col">Status</th>
                  <th scope="col">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($products as $produk)
                  <tr>
                    <td>
                      <div class="order-item-row">
                        <div class="item-thumb" style="width:48px;height:42px;">
                          @if ($produk->gambar_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($produk->gambar_path))
                            <img src="{{ $produk->gambar_url }}" alt="{{ $produk->nama_produk }}">
                          @else
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5">
                              <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                            </svg>
                          @endif
                        </div>
                        <div class="item-info">
                          <div class="item-name">{{ $produk->nama_produk }}</div>
                          <div class="item-meta">SKU: {{ $produk->sku }} &middot; {{ $produk->jumlah_terjual }} terjual</div>
                        </div>
                      </div>
                    </td>

                    <td>
                      <div style="font-weight:600;color:#1e293b;">{{ $produk->kategori->nama_kategori ?? '-' }}</div>
                      <div style="font-size:11.5px;color:#64748b;margin-top:2px;">{{ $produk->merek->nama_merek ?? '-' }}</div>
                    </td>

                    <td class="price-total" style="color:#0f172a;">
                      Rp {{ number_format((float) $produk->harga, 0, ',', '.') }}
                    </td>

                    <td>
                      <span style="font-weight:700;color:{{ $produk->stok <= 5 ? '#dc2626' : '#0f172a' }};">
                        {{ $produk->stok }}
                      </span>
                    </td>

                    <td>
                      <span class="status-badge {{ $produk->is_active ? 'status-aktif' : 'status-nonaktif' }}">
                        {{ $produk->is_active ? 'Aktif' : 'Nonaktif' }}
                      </span>
                    </td>

                    <td class="actions-col">
                      <div class="action-row">
                        <a href="{{ route('admin.products.edit', $produk->id_produk) }}" class="btn-outline-neutral">Edit</a>
                        <form action="{{ route('admin.products.status', $produk->id_produk) }}" method="POST">
                          @csrf
                          @method('PATCH')
                          <button type="submit" class="btn-outline-danger">
                            {{ $produk->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="empty-state">
                      Belum ada produk yang cocok.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          @if ($products->hasPages())
            <div class="pagination-wrap">{{ $products->links() }}</div>
          @endif
        </section>
      @endif
    </main>
  </div>
</body>
</html>
