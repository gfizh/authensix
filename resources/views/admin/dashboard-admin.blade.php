<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin | AuthenSIX Computer</title>
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
      --badge-active: #22c55e;
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
       SIDEBAR
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
      font-size: 14.5px;
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
    }

    /* Sidebar Footer / User Profile */
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
      overflow: hidden;
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 13px;
      color: #0284c7;
    }

    .user-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
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
       MAIN LAYOUT
       ===================================================== */
    .app-main {
      margin-left: var(--sidebar-width);
      flex: 1;
      display: flex;
      flex-direction: column;
      min-width: 0;
    }

    /* =====================================================
       TOP HEADER NAVBAR
       ===================================================== */
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
    }

    .header-page-title {
      font-size: 16px;
      font-weight: 600;
      color: var(--text-main);
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

    .notif-btn {
      position: relative;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: grid;
      place-items: center;
      color: var(--text-muted);
      transition: background 0.15s ease;
    }

    .notif-btn:hover {
      background: #f1f5f9;
      color: var(--text-main);
    }

    .notif-dot {
      position: absolute;
      top: 6px;
      right: 6px;
      width: 8px;
      height: 8px;
      background: #ef4444;
      border: 2px solid #ffffff;
      border-radius: 50%;
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
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .header-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .header-username {
      font-size: 13px;
      font-weight: 650;
      letter-spacing: 0.3px;
      color: var(--text-main);
    }

    /* =====================================================
       CONTENT WRAPPER
       ===================================================== */
    .dashboard-content {
      padding: 28px 32px 48px;
      max-width: 1400px;
      width: 100%;
    }

    /* Section Header */
    .content-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 24px;
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
    }

    .time-dropdown {
      position: relative;
    }

    .time-select {
      appearance: none;
      background: #ffffff;
      border: 1.5px solid #cbd5e1;
      border-radius: 8px;
      padding: 8px 34px 8px 14px;
      font-size: 13px;
      font-weight: 500;
      color: var(--text-main);
      cursor: pointer;
      outline: none;
      transition: border-color 0.15s ease;
    }

    .time-select:focus {
      border-color: var(--primary);
    }

    .time-dropdown svg {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      pointer-events: none;
      color: var(--text-muted);
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

    /* =====================================================
       ROW 1: 4 STAT CARDS
       ===================================================== */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
      margin-bottom: 24px;
    }

    .stat-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 18px 20px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
      transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .stat-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    }

    .stat-top {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .stat-icon {
      width: 40px;
      height: 40px;
      border-radius: 10px;
      background: var(--primary-light);
      color: #0284c7;
      display: grid;
      place-items: center;
    }

    .stat-icon svg {
      width: 22px;
      height: 22px;
    }

    .stat-badge {
      font-size: 11px;
      font-weight: 700;
      color: var(--badge-green-text);
      background: var(--badge-green-bg);
      padding: 3px 8px;
      border-radius: 9999px;
    }

    .stat-label {
      font-size: 13px;
      color: var(--text-muted);
      font-weight: 500;
      margin-top: 14px;
    }

    .stat-value {
      font-size: 26px;
      font-weight: 750;
      color: #0f172a;
      letter-spacing: -0.5px;
      margin-top: 2px;
    }

    /* =====================================================
       ROW 2: SALES CHART & TOP PRODUCTS
       ===================================================== */
    .row-middle {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 16px;
      margin-bottom: 24px;
    }

    /* Left Card: Sales Chart */
    .chart-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 22px 24px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
      display: flex;
      flex-direction: column;
    }

    .chart-header {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      margin-bottom: 24px;
    }

    .chart-title {
      font-size: 16px;
      font-weight: 700;
      color: #0f172a;
    }

    .chart-sub {
      font-size: 13px;
      color: var(--text-muted);
      margin-top: 2px;
    }

    .chart-toggle {
      display: inline-flex;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      overflow: hidden;
      background: #ffffff;
    }

    .chart-toggle-btn {
      padding: 6px 14px;
      font-size: 12px;
      font-weight: 500;
      color: var(--text-muted);
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.15s ease;
    }

    .chart-toggle-btn.active {
      background: #ede9fe;
      color: #4f46e5;
      font-weight: 600;
    }

    /* Chart Canvas Area */
    .chart-body {
      display: flex;
      gap: 16px;
      height: 220px;
      align-items: stretch;
      margin-top: auto;
    }

    .chart-y-axis {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      font-size: 11px;
      color: var(--text-muted);
      text-align: right;
      padding-bottom: 24px;
      width: 50px;
      flex-shrink: 0;
    }

    .chart-bars-wrap {
      flex: 1;
      display: flex;
      flex-direction: column;
      position: relative;
    }

    .chart-grid-lines {
      position: absolute;
      inset: 0 0 24px 0;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      pointer-events: none;
    }

    .grid-line {
      width: 100%;
      height: 1px;
      background: #f1f5f9;
    }

    .chart-bars {
      position: relative;
      flex: 1;
      display: flex;
      align-items: flex-end;
      justify-content: space-around;
      padding-bottom: 24px;
      z-index: 1;
    }

    .bar-col {
      display: flex;
      flex-direction: column;
      align-items: center;
      position: relative;
      height: 100%;
      justify-content: flex-end;
      width: 44px;
    }

    .bar-rect {
      width: 100%;
      max-width: 38px;
      background: #38bdf8;
      border-radius: 4px 4px 0 0;
      transition: height 0.4s cubic-bezier(0.4, 0, 0.2, 1), background 0.15s ease;
      position: relative;
      cursor: pointer;
    }

    .bar-col:hover .bar-rect {
      background: var(--primary);
    }

    .bar-label {
      position: absolute;
      bottom: -22px;
      font-size: 11px;
      color: var(--text-muted);
      font-weight: 500;
      white-space: nowrap;
    }

    /* Tooltip */
    .bar-tooltip {
      position: absolute;
      bottom: calc(100% + 6px);
      left: 50%;
      transform: translateX(-50%);
      background: #0f172a;
      color: #ffffff;
      font-size: 11px;
      font-weight: 600;
      padding: 4px 8px;
      border-radius: 6px;
      white-space: nowrap;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.15s ease, transform 0.15s ease;
      z-index: 20;
    }

    .bar-col:hover .bar-tooltip {
      opacity: 1;
      transform: translateX(-50%) translateY(-2px);
    }

    /* Right Card: Top Selling Products */
    .top-products-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 22px 20px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
      display: flex;
      flex-direction: column;
    }

    .top-products-title {
      font-size: 16px;
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 16px;
    }

    .top-products-list {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .top-product-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 8px 6px;
      border-radius: 10px;
      transition: background 0.15s ease;
    }

    .top-product-item:hover {
      background: #f8fafc;
    }

    .top-product-thumb {
      width: 52px;
      height: 52px;
      border-radius: 8px;
      background: #f8fafc;
      border: 1px solid var(--border);
      overflow: hidden;
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 4px;
    }

    .top-product-thumb img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
    }

    .top-product-meta {
      flex: 1;
      min-width: 0;
    }

    .top-product-name {
      font-size: 13px;
      font-weight: 600;
      color: #1e293b;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      display: block;
    }

    .top-product-sold {
      font-size: 12.5px;
      font-weight: 700;
      color: #0f172a;
      margin-top: 3px;
    }

    /* =====================================================
       ROW 3: RECENT PRODUCTS TABLE
       ===================================================== */
    .table-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 22px 24px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .table-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 16px;
    }

    .table-title {
      font-size: 16px;
      font-weight: 700;
      color: #0f172a;
    }

    .link-manage-all {
      font-size: 13px;
      font-weight: 600;
      color: var(--primary);
      transition: color 0.15s ease;
    }

    .link-manage-all:hover {
      text-decoration: underline;
    }

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
      padding: 10px 14px;
      border-bottom: 1px solid var(--border);
      background: #fafafa;
    }

    .data-table thead th:first-child {
      border-radius: 8px 0 0 8px;
    }

    .data-table thead th:last-child {
      border-radius: 0 8px 8px 0;
      text-align: right;
    }

    .data-table tbody td {
      padding: 14px;
      border-bottom: 1px solid var(--border-light);
      font-size: 13px;
      vertical-align: middle;
    }

    .data-table tbody tr:hover td {
      background: #fafafa;
    }

    .col-product {
      display: flex;
      align-items: center;
      gap: 12px;
      min-width: 240px;
    }

    .product-thumb {
      width: 48px;
      height: 40px;
      border-radius: 6px;
      background: #f8fafc;
      border: 1px solid var(--border-light);
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      padding: 2px;
    }

    .product-thumb img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
    }

    .product-name {
      font-weight: 600;
      color: #0f172a;
      line-height: 1.35;
    }

    .col-price {
      font-weight: 700;
      color: #0f172a;
      white-space: nowrap;
    }

    .col-stock {
      color: var(--text-muted);
      font-weight: 500;
    }

    .badge-status {
      display: inline-block;
      font-size: 11px;
      font-weight: 600;
      padding: 3px 12px;
      border-radius: 9999px;
      text-transform: capitalize;
    }

    .badge-status.active {
      background: var(--badge-active);
      color: #ffffff;
    }

    .badge-status.inactive {
      background: #94a3b8;
      color: #ffffff;
    }

    .col-actions {
      text-align: right;
      white-space: nowrap;
    }

    .action-icon-btn {
      color: var(--text-muted);
      padding: 6px;
      border-radius: 6px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: all 0.15s ease;
    }

    .action-icon-btn:hover {
      color: var(--primary);
      background: #eff6ff;
    }

    .action-icon-btn svg {
      width: 16px;
      height: 16px;
    }

    /* =====================================================
       RESPONSIVE BREAKPOINTS
       ===================================================== */
    @media (max-width: 1100px) {
      .stats-grid {
        grid-template-columns: repeat(2, 1fr);
      }
      .row-middle {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 768px) {
      :root {
        --sidebar-width: 70px;
      }
      .brand-sub,
      .brand-text span,
      .nav-item span,
      .user-meta {
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
        width: 180px;
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
      <!-- Active Dashboard Link -->
      <a href="{{ route('admin.dashboard-admin') }}" class="nav-item active" aria-current="page">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
          <polyline points="9 22 9 12 15 12 15 22"/>
        </svg>
        <span>Dashboard</span>
      </a>

      <!-- Product Link -->
      <a href="{{ route('admin.tambah_produk') }}" class="nav-item">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
          <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
          <line x1="12" y1="22.08" x2="12" y2="12"/>
        </svg>
        <span>Product</span>
      </a>
    </nav>

    <!-- Sidebar Bottom User Profile Card -->
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

    <!-- Top Navbar -->
    <header class="top-header">
      <div class="header-page-title">Dashboard</div>

      <div class="header-right">
        <!-- Search bar -->
        <div class="search-box">
          <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <circle cx="11" cy="11" r="8"/>
            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
          </svg>
          <input type="text" class="search-input" placeholder="Cari..." aria-label="Cari">
        </div>

        <!-- Notification bell -->
        <button type="button" class="notif-btn" aria-label="Notifikasi">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
          </svg>
          <span class="notif-dot"></span>
        </button>

        <div class="header-divider"></div>

        <!-- Admin Profile -->
        <div class="header-user">
          <div class="header-avatar">
            {{ strtoupper(substr(auth()->user()->nama_lengkap ?? auth()->user()->username ?? 'G', 0, 2)) }}
          </div>
          <span class="header-username">{{ strtoupper(auth()->user()->nama_lengkap ?? auth()->user()->username ?? 'ADMIN') }}</span>
        </div>
      </div>
    </header>

    <!-- Content Area -->
    <main class="dashboard-content">

      <!-- Section Title & Actions -->
      <div class="content-header">
        <div>
          <h1>Dashboard</h1>
          <p>Ringkasan aktivitas toko dan penjualan</p>
        </div>

        <div class="content-actions">
          <div class="time-dropdown">
            <select class="time-select" aria-label="Filter waktu">
              <option value="7">7 terakhir</option>
              <option value="30">30 hari terakhir</option>
              <option value="bulan">Bulan ini</option>
              <option value="tahun">Tahun ini</option>
            </select>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <polyline points="6 9 12 15 18 9"/>
            </svg>
          </div>

          <a href="{{ route('admin.products.create') }}" class="btn-add-product">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <line x1="12" y1="5" x2="12" y2="19"/>
              <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            <span>Tambah produk</span>
          </a>
        </div>
      </div>

      <!-- =====================================================
           ROW 1: 4 STAT CARDS
           ===================================================== -->
      <section class="stats-grid" aria-label="Statistik utama">
        <!-- Card 1: Total Produk -->
        <div class="stat-card">
          <div class="stat-top">
            <div class="stat-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                <line x1="12" y1="22.08" x2="12" y2="12"/>
              </svg>
            </div>
            @if(!empty($stats['persentase_produk']))
              <span class="stat-badge">{{ $stats['persentase_produk'] }}</span>
            @endif
          </div>
          <div class="stat-label">Total produk</div>
          <div class="stat-value">{{ $stats['total_produk'] ?? 0 }}</div>
        </div>

        <!-- Card 2: Total Pesanan -->
        <div class="stat-card">
          <div class="stat-top">
            <div class="stat-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="9" cy="21" r="1"/>
                <circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
              </svg>
            </div>
            @if(!empty($stats['persentase_pesanan']))
              <span class="stat-badge">{{ $stats['persentase_pesanan'] }}</span>
            @endif
          </div>
          <div class="stat-label">Total pesanan</div>
          <div class="stat-value">{{ $stats['total_pesanan'] ?? 0 }}</div>
        </div>

        <!-- Card 3: Total Pelanggan -->
        <div class="stat-card">
          <div class="stat-top">
            <div class="stat-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
              </svg>
            </div>
            @if(!empty($stats['persentase_pelanggan']))
              <span class="stat-badge">{{ $stats['persentase_pelanggan'] }}</span>
            @endif
          </div>
          <div class="stat-label">Total pelanggan</div>
          <div class="stat-value">{{ $stats['total_pelanggan'] ?? 0 }}</div>
        </div>

        <!-- Card 4: Total Pendapatan -->
        <div class="stat-card">
          <div class="stat-top">
            <div class="stat-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <rect x="2" y="4" width="20" height="16" rx="2"/>
                <line x1="2" y1="10" x2="22" y2="10"/>
              </svg>
            </div>
            @if(!empty($stats['persentase_pendapatan']))
              <span class="stat-badge">{{ $stats['persentase_pendapatan'] }}</span>
            @endif
          </div>
          <div class="stat-label">Total pendapatan</div>
          <div class="stat-value">Rp {{ number_format((float) ($stats['total_pendapatan'] ?? 0), 0, ',', '.') }}</div>
        </div>
      </section>

      <!-- =====================================================
           ROW 2: GRAFIK PENJUALAN & PRODUK TERLARIS
           ===================================================== -->
      <div class="row-middle">

        <!-- Left: Grafik Penjualan -->
        <section class="chart-card" aria-label="Grafik performa penjualan">
          <div class="chart-header">
            <div>
              <h2 class="chart-title">Grafik Penjualan</h2>
              <p class="chart-sub">Performa pendapatan harian</p>
            </div>

            <div class="chart-toggle">
              <button type="button" class="chart-toggle-btn active" id="btnHarian">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                  <polyline points="20 6 9 17 4 12"/>
                </svg>
                <span>Harian</span>
              </button>
              <button type="button" class="chart-toggle-btn" id="btnMingguan">
                <span>Mingguan</span>
              </button>
            </div>
          </div>

          @php
            // Ambil data grafik dari controller atau buat fallback terstruktur
            $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            $chartData = $grafikPenjualan ?? [];

            // Cari nilai maksimum agar persentase tinggi bar akurat
            $maxVal = 0;
            foreach ($hariList as $h) {
                $val = (float) ($chartData[$h]['total'] ?? 0);
                if ($val > $maxVal) {
                    $maxVal = $val;
                }
            }
            if ($maxVal <= 0) {
                $maxVal = 10000000; // baseline 10jt
            }
          @endphp

          <div class="chart-body">
            <!-- Y-Axis Ticks -->
            <div class="chart-y-axis">
              <span>Rp. {{ number_format($maxVal, 0, ',', '.') }}</span>
              <span>Rp. {{ number_format($maxVal * 0.75, 0, ',', '.') }}</span>
              <span>Rp. {{ number_format($maxVal * 0.5, 0, ',', '.') }}</span>
              <span>Rp. {{ number_format($maxVal * 0.25, 0, ',', '.') }}</span>
              <span>Rp. 0</span>
            </div>

            <!-- Bars Area with Grid Lines -->
            <div class="chart-bars-wrap">
              <div class="chart-grid-lines">
                <div class="grid-line"></div>
                <div class="grid-line"></div>
                <div class="grid-line"></div>
                <div class="grid-line"></div>
                <div class="grid-line"></div>
              </div>

              <div class="chart-bars">
                @foreach ($hariList as $hari)
                  @php
                    $nominal = (float) ($chartData[$hari]['total'] ?? 0);
                    $trxCount = (int) ($chartData[$hari]['transaksi'] ?? 0);
                    // Hitung tinggi dalam persentase (min 6% agar bar terlihat cantik)
                    $heightPercent = $nominal > 0 ? max(8, round(($nominal / $maxVal) * 100)) : 6;
                  @endphp
                  <div class="bar-col">
                    <div class="bar-tooltip">
                      {{ $hari }}: Rp {{ number_format($nominal, 0, ',', '.') }} ({{ $trxCount }} transaksi)
                    </div>
                    <div class="bar-rect" style="height: {{ $heightPercent }}%;"></div>
                    <span class="bar-label">{{ $hari }}</span>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </section>

        <!-- Right: Produk Terlaris -->
        <section class="top-products-card" aria-label="Produk terlaris">
          <h2 class="top-products-title">Produk Terlaris</h2>

          @php
            $bestSellers = $produkTerlaris ?? \App\Models\Produk::where('is_active', true)->orderByDesc('jumlah_terjual')->limit(4)->get();
          @endphp

          <div class="top-products-list">
            @forelse ($bestSellers as $topItem)
              <div class="top-product-item">
                <div class="top-product-thumb">
                  @if ($topItem->gambar_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($topItem->gambar_path))
                    <img src="{{ $topItem->gambar_url }}" alt="{{ $topItem->nama_produk }}">
                  @else
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5">
                      <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                      <circle cx="8.5" cy="8.5" r="1.5"/>
                      <polyline points="21 15 16 10 5 21"/>
                    </svg>
                  @endif
                </div>
                <div class="top-product-meta">
                  <a href="{{ route('admin.products.edit', $topItem->id_produk) }}" class="top-product-name" title="{{ $topItem->nama_produk }}">
                    {{ $topItem->nama_produk }}
                  </a>
                  <div class="top-product-sold">{{ $topItem->jumlah_terjual }} Terjual</div>
                </div>
              </div>
            @empty
              <p style="font-size: 13px; color: var(--text-muted); padding: 12px 0;">Belum ada data penjualan.</p>
            @endforelse
          </div>
        </section>

      </div>

      <!-- =====================================================
           ROW 3: PRODUK TERBARU TABLE
           ===================================================== -->
      <section class="table-card" aria-label="Daftar produk terbaru">
        <div class="table-header">
          <h2 class="table-title">Produk Terbaru</h2>
          <a href="{{ route('admin.products.index') }}" class="link-manage-all">Kelola semua</a>
        </div>

        <div class="table-responsive">
          <table class="data-table">
            <thead>
              <tr>
                <th scope="col">Produk</th>
                <th scope="col">Harga</th>
                <th scope="col">Stok</th>
                <th scope="col">Status</th>
                <th scope="col" style="text-align: right;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($produkTerbaru as $produk)
                <tr>
                  <td>
                    <div class="col-product">
                      <div class="product-thumb">
                        @if ($produk->gambar_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($produk->gambar_path))
                          <img src="{{ $produk->gambar_url }}" alt="{{ $produk->nama_produk }}">
                        @else
                          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                          </svg>
                        @endif
                      </div>
                      <div class="product-name">{{ $produk->nama_produk }}</div>
                    </div>
                  </td>
                  <td class="col-price">
                    Rp. {{ number_format((float) $produk->harga, 0, ',', '.') }}
                  </td>
                  <td class="col-stock">
                    {{ $produk->stok }}
                  </td>
                  <td>
                    <span class="badge-status {{ $produk->is_active ? 'active' : 'inactive' }}">
                      {{ $produk->is_active ? 'aktif' : 'nonaktif' }}
                    </span>
                  </td>
                  <td class="col-actions">
                    <a href="{{ route('admin.products.edit', $produk->id_produk) }}" class="action-icon-btn" title="Edit Produk">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/>
                      </svg>
                    </a>
                    <form action="{{ route('admin.products.status', $produk->id_produk) }}" method="POST" style="display: inline-block;">
                      @csrf
                      @method('PATCH')
                      <button type="submit" class="action-icon-btn" title="Ubah Status">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <polyline points="3 6 5 6 21 6"/>
                          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 24px;">
                    Belum ada produk terbaru.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </section>

    </main>
  </div>

  <script>
    // Toggle Button Harian / Mingguan
    const btnHarian = document.getElementById('btnHarian');
    const btnMingguan = document.getElementById('btnMingguan');

    if (btnHarian && btnMingguan) {
      btnHarian.addEventListener('click', () => {
        btnHarian.classList.add('active');
        btnMingguan.classList.remove('active');
      });

      btnMingguan.addEventListener('click', () => {
        btnMingguan.classList.add('active');
        btnHarian.classList.remove('active');
      });
    }
  </script>
</body>
</html>
