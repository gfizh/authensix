<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Produk | AuthenSIX Computer</title>
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
      --accent-blue: #0ea5e9;
      --accent-blue-hover: #0284c7;
      --banner-bg: #eff6ff;
      --banner-border: #bfdbfe;
      --banner-text: #1d4ed8;
      --badge-active: #22c55e;
      --badge-inactive-bg: #e2e8f0;
      --badge-inactive-text: #64748b;
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

    /* Breadcrumbs */
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
      width: 260px;
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
      transition: border-color 0.15s ease;
    }

    .search-input:focus {
      border-color: var(--primary);
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
      max-width: 1300px;
      width: 100%;
    }

    /* Page Header */
    .content-header {
      margin-bottom: 20px;
    }

    .title-row {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .back-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 32px;
      height: 32px;
      border-radius: 8px;
      color: var(--text-main);
      transition: background 0.15s ease;
    }

    .back-btn:hover {
      background: #e2e8f0;
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
      margin-left: 44px;
    }

    /* Blue Info Banner */
    .info-banner {
      background: var(--banner-bg);
      border: 1px solid var(--banner-border);
      border-radius: 10px;
      padding: 14px 20px;
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 24px;
      color: var(--banner-text);
      font-size: 13.5px;
    }

    .info-banner-icon {
      width: 20px;
      height: 20px;
      flex-shrink: 0;
      fill: currentColor;
    }

    .info-banner b {
      font-weight: 700;
    }

    /* Error Alert */
    .alert-error {
      background: #fef2f2;
      border: 1px solid #fecaca;
      border-radius: 10px;
      padding: 14px 20px;
      margin-bottom: 24px;
      color: #991b1b;
      font-size: 13.5px;
    }

    .alert-error ul {
      margin-left: 20px;
      margin-top: 6px;
    }

    /* =====================================================
       TWO-COLUMN FORM GRID
       ===================================================== */
    .form-grid {
      display: grid;
      grid-template-columns: 1.8fr 1.2fr;
      gap: 24px;
      align-items: start;
    }

    .form-column {
      display: flex;
      flex-direction: column;
      gap: 24px;
    }

    .form-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 24px 26px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .form-card-title {
      font-size: 18px;
      font-weight: 750;
      color: #0f172a;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 18px;
    }

    .form-group:last-child {
      margin-bottom: 0;
    }

    .form-label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: #1e293b;
      margin-bottom: 8px;
    }

    .form-input,
    .form-select,
    .form-textarea {
      width: 100%;
      border: 1.5px solid #cbd5e1;
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 13.5px;
      color: var(--text-main);
      background: #ffffff;
      outline: none;
      transition: border-color 0.15s ease, box-shadow 0.15s ease;
      font-family: inherit;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(22, 115, 230, 0.12);
    }

    .select-wrap {
      position: relative;
    }

    .form-select {
      appearance: none;
      padding-right: 36px;
      cursor: pointer;
    }

    .select-arrow {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      pointer-events: none;
      color: var(--text-muted);
    }

    .form-textarea {
      resize: vertical;
      min-height: 120px;
      line-height: 1.5;
    }

    /* 2x2 Grid for Price & Stock */
    .grid-2x2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px 20px;
    }

    /* =====================================================
       RIGHT COLUMN: IMAGE & SUMMARY
       ===================================================== */
    /* Image Upload Box */
    .dropzone-box {
      border: 1.5px dashed #cbd5e1;
      border-radius: 12px;
      background: #f8fafc;
      height: 180px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      margin-bottom: 16px;
      overflow: hidden;
      position: relative;
      transition: border-color 0.15s ease;
    }

    .dropzone-box:hover {
      border-color: var(--primary);
    }

    .dropzone-placeholder {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 4px;
      padding: 16px;
    }

    .dropzone-title {
      font-size: 13px;
      font-weight: 600;
      color: var(--text-muted);
    }

    .dropzone-desc {
      font-size: 11px;
      color: var(--text-sub);
    }

    .dropzone-preview {
      width: 100%;
      height: 100%;
      object-fit: contain;
      display: none;
      background: #ffffff;
    }

    .btn-upload-file {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      width: 100%;
      padding: 10px 16px;
      border: 1.5px solid #cbd5e1;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      color: #1e293b;
      background: #ffffff;
      transition: all 0.15s ease;
    }

    .btn-upload-file:hover {
      border-color: var(--primary);
      color: var(--primary);
      background: #f0f7ff;
    }

    .btn-upload-file svg {
      width: 16px;
      height: 16px;
      color: var(--primary);
    }

    /* Summary Card */
    .summary-list {
      display: flex;
      flex-direction: column;
      gap: 12px;
      margin-bottom: 24px;
      border-bottom: 1px solid var(--border-light);
      padding-bottom: 20px;
    }

    .summary-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 13px;
    }

    .summary-label {
      color: var(--text-muted);
    }

    .summary-val {
      font-weight: 600;
      color: var(--text-main);
      text-align: right;
      max-width: 180px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .summary-status-badge {
      display: inline-block;
      font-size: 11px;
      font-weight: 600;
      padding: 4px 14px;
      border-radius: 9999px;
      background: var(--badge-inactive-bg);
      color: var(--badge-inactive-text);
      transition: all 0.2s ease;
    }

    .summary-status-badge.active {
      background: #dcfce7;
      color: #16a34a;
    }

    /* Action Buttons */
    .summary-actions {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .btn-save-product {
      width: 100%;
      background: var(--accent-blue);
      color: #ffffff;
      font-size: 13.5px;
      font-weight: 700;
      padding: 11px 20px;
      border-radius: 8px;
      box-shadow: 0 1px 3px rgba(14, 165, 233, 0.25);
      transition: background 0.15s ease;
      text-align: center;
    }

    .btn-save-product:hover {
      background: var(--accent-blue-hover);
    }

    .btn-cancel {
      width: 100%;
      background: #ffffff;
      border: 1.5px solid #fca5a5;
      color: #ef4444;
      font-size: 13.5px;
      font-weight: 600;
      padding: 10px 20px;
      border-radius: 8px;
      text-align: center;
      transition: background 0.15s ease, border-color 0.15s ease;
      display: block;
    }

    .btn-cancel:hover {
      background: #fef2f2;
      border-color: #ef4444;
    }

    /* =====================================================
       RESPONSIVE BREAKPOINTS
       ===================================================== */
    @media (max-width: 1024px) {
      .form-grid {
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
      .grid-2x2 {
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
      .content-header p {
        margin-left: 0;
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

      <a href="{{ route('admin.manajemen_produk', ['tab' => 'pesanan']) }}" class="nav-item">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
          <rect x="9" y="3" width="6" height="4" rx="1"/>
          <path d="M9 12h6"/>
          <path d="M9 16h6"/>
        </svg>
        <span>Manajemen Pesanan</span>
      </a>

      <a href="{{ route('admin.manajemen_produk', ['tab' => 'produk']) }}" class="nav-item">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
          <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
          <line x1="12" y1="22.08" x2="12" y2="12"/>
        </svg>
        <span>Product</span>
      </a>

      <a href="{{ route('admin.tambah_produk') }}" class="nav-item active" aria-current="page">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <circle cx="12" cy="12" r="9"/>
          <line x1="12" y1="8" x2="12" y2="16"/>
          <line x1="8" y1="12" x2="16" y2="12"/>
        </svg>
        <span>Tambah Produk</span>
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

    <!-- Top Navbar with Breadcrumb -->
    @php $isEdit = isset($product); @endphp
    <header class="top-header">
      <nav class="breadcrumb-nav" aria-label="Breadcrumb">
        <a href="{{ route('admin.dashboard-admin') }}">Dashboard</a>
        <span class="breadcrumb-sep">&gt;</span>
        <a href="{{ route('admin.manajemen_produk', ['tab' => 'produk']) }}">Produk</a>
        <span class="breadcrumb-sep">&gt;</span>
        <span class="breadcrumb-current">{{ $isEdit ? 'Edit Produk' : 'Tambah Produk' }}</span>
      </nav>

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
            {{ strtoupper(substr(auth()->user()->nama_lengkap ?? auth()->user()->username ?? 'A', 0, 2)) }}
          </div>
          <span class="header-username">{{ strtoupper(auth()->user()->nama_lengkap ?? auth()->user()->username ?? 'ADMIN') }}</span>
        </div>
      </div>
    </header>

    <!-- Content Area -->
    <main class="dashboard-content">

      <!-- Header & Back Button -->
      <div class="content-header">
        <div class="title-row">
          <a href="{{ route('admin.dashboard-admin') }}" class="back-btn" title="Kembali">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <line x1="19" y1="12" x2="5" y2="12"/>
              <polyline points="12 19 5 12 12 5"/>
            </svg>
          </a>
          <h1>{{ $isEdit ? 'Edit Produk' : 'Tambah Produk' }}</h1>
        </div>
        <p>{{ $isEdit ? 'Perbarui informasi produk berikut sesuai kebutuhan.' : 'Lengkapi informasi berikut untuk menambahkan produk baru.' }}</p>
      </div>

      <!-- Blue Info Banner -->
      <div class="info-banner" role="status">
        <svg class="info-banner-icon" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        <span>Anda sedang {{ $isEdit ? 'mengubah' : 'menambahkan' }} : <b>{{ $isEdit ? $product->nama_produk : 'produk baru' }}</b></span>
      </div>

      <!-- Validation Error Display -->
      @if ($errors->any())
        <div class="alert-error" role="alert">
          <strong>Perhatian:</strong> Beberapa data belum lengkap atau tidak valid:
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Add / Edit Product Form -->
      <form action="{{ $isEdit ? route('admin.products.update', $product->id_produk) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="formTambahProduk">
        @csrf
        @if ($isEdit)
          @method('PUT')
        @endif

        <!-- Hidden Database Requirements -->
        <input type="hidden" name="sku" id="skuField" value="{{ old('sku', $product->sku ?? '') }}">
        <input type="hidden" name="tipe_produk" value="{{ old('tipe_produk', $product->tipe_produk ?? 'regular') }}">
        <input type="hidden" name="kondisi" value="{{ old('kondisi', $product->kondisi ?? 'baru') }}">
        <input type="hidden" name="masa_garansi_bulan" value="{{ old('masa_garansi_bulan', $product->masa_garansi_bulan ?? 12) }}">

        <div class="form-grid">

          <!-- Left Column -->
          <div class="form-column">

            <!-- Card 1: Informasi Produk -->
            <div class="form-card">
              <h2 class="form-card-title">Informasi produk</h2>

              <div class="form-group">
                <label class="form-label" for="inputNama">Nama Produk</label>
                <input
                  type="text"
                  name="nama_produk"
                  id="inputNama"
                  class="form-input"
                  placeholder=""
                  value="{{ old('nama_produk', $product->nama_produk ?? '') }}"
                  required
                >
              </div>

              <div class="form-group">
                <label class="form-label" for="selectBrand">Brand</label>
                <div class="select-wrap">
                  <select name="id_merek" id="selectBrand" class="form-select" required>
                    <option value="" disabled {{ old('id_merek', $product->id_merek ?? null) ? '' : 'selected' }}>Pilih Brand</option>
                    @foreach ($brands as $brand)
                      <option value="{{ $brand->id_merek }}" {{ (string) old('id_merek', $product->id_merek ?? '') === (string) $brand->id_merek ? 'selected' : '' }}>
                        {{ $brand->nama_merek }}
                      </option>
                    @endforeach
                  </select>
                  <svg class="select-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="6 9 12 15 18 9"/>
                  </svg>
                </div>
              </div>

              <div class="form-group">
                <label class="form-label" for="selectKategori">Kategori</label>
                <div class="select-wrap">
                  <select name="id_kategori" id="selectKategori" class="form-select" required>
                    <option value="" disabled {{ old('id_kategori', $product->id_kategori ?? null) ? '' : 'selected' }}>Pilih Kategori</option>
                    @foreach ($categories as $cat)
                      <option value="{{ $cat->id_kategori }}" {{ (string) old('id_kategori', $product->id_kategori ?? '') === (string) $cat->id_kategori ? 'selected' : '' }}>
                        {{ $cat->nama_kategori }}
                      </option>
                    @endforeach
                  </select>
                  <svg class="select-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="6 9 12 15 18 9"/>
                  </svg>
                </div>
              </div>

              <div class="form-group">
                <label class="form-label" for="inputDeskripsi">Deskripsi Produk</label>
                <textarea
                  name="deskripsi"
                  id="inputDeskripsi"
                  class="form-textarea"
                  placeholder=""
                  required
                >{{ old('deskripsi', $product->deskripsi ?? '') }}</textarea>
              </div>
            </div>

            <!-- Card 2: Harga & Stok -->
            <div class="form-card">
              <h2 class="form-card-title">Harga &amp; Stok</h2>

              <div class="grid-2x2">
                <div class="form-group">
                  <label class="form-label" for="inputHarga">Harga Jual (Rp)</label>
                  <input
                    type="number"
                    name="harga"
                    id="inputHarga"
                    class="form-input"
                    min="0"
                    step="1"
                    placeholder=""
                    value="{{ old('harga', isset($product) ? (int) $product->harga : '') }}"
                    required
                  >
                </div>

                <div class="form-group">
                  <label class="form-label" for="inputDiskon">Harga diskon (Rp)</label>
                  <input
                    type="number"
                    name="harga_diskon"
                    id="inputDiskon"
                    class="form-input"
                    min="0"
                    step="1"
                    placeholder=""
                    value="{{ old('harga_diskon', isset($product) && $product->harga_diskon !== null ? (int) $product->harga_diskon : '') }}"
                  >
                </div>

                <div class="form-group">
                  <label class="form-label" for="inputStok">Stok Produk</label>
                  <input
                    type="number"
                    name="stok"
                    id="inputStok"
                    class="form-input"
                    min="0"
                    step="1"
                    placeholder=""
                    value="{{ old('stok', $product->stok ?? '') }}"
                    required
                  >
                </div>

                <div class="form-group">
                  <label class="form-label" for="inputBerat">Berat (gram)</label>
                  <input
                    type="number"
                    name="berat_gram"
                    id="inputBerat"
                    class="form-input"
                    min="0"
                    step="1"
                    placeholder=""
                    value="{{ old('berat_gram', old('berat', $product->berat_gram ?? '')) }}"
                  >
                </div>
              </div>
            </div>

          </div>

          <!-- Right Column -->
          <div class="form-column">

            <!-- Card 3: Gambar Produk -->
            <div class="form-card">
              <h2 class="form-card-title">Gambar produk</h2>

              @php
                $hasExistingImg = $isEdit && $product->gambar_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->gambar_path);
              @endphp
              <div class="dropzone-box" id="dropzoneBox">
                <div class="dropzone-placeholder" id="dropzonePlaceholder" @if($hasExistingImg) style="display:none;" @endif>
                  <div class="dropzone-title">Tidak ada gambar</div>
                  <div class="dropzone-desc">JPG, PNG, atau WEBP</div>
                </div>
                <img src="{{ $hasExistingImg ? $product->gambar_url : '' }}"
                     alt="Preview Gambar"
                     class="dropzone-preview"
                     id="imagePreview"
                     @if($hasExistingImg) style="display:block;" @endif>
              </div>

              <input
                type="file"
                name="gambar"
                id="fileGambarInput"
                accept="image/png,image/jpeg,image/webp"
                style="display: none;"
              >

              <button type="button" class="btn-upload-file" id="btnUploadTrigger">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                  <line x1="12" y1="5" x2="12" y2="19"/>
                  <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                <span>Upload Gambar</span>
              </button>
            </div>

            <!-- Card 4: Ringkasan Produk -->
            <div class="form-card">
              <h2 class="form-card-title">Ringkasan produk</h2>

              <div class="summary-list">
                <div class="summary-item">
                  <span class="summary-label">Nama produk</span>
                  <span class="summary-val" id="sumName">-</span>
                </div>

                <div class="summary-item">
                  <span class="summary-label">Kategori</span>
                  <span class="summary-val" id="sumCategory">-</span>
                </div>

                <div class="summary-item">
                  <span class="summary-label">Harga jual</span>
                  <span class="summary-val" id="sumPrice">-</span>
                </div>

                <div class="summary-item">
                  <span class="summary-label">Harga diskon</span>
                  <span class="summary-val" id="sumDiscount">-</span>
                </div>

                <div class="summary-item">
                  <span class="summary-label">Stok tersedia</span>
                  <span class="summary-val" id="sumStock">-</span>
                </div>

                <div class="summary-item">
                  <span class="summary-label">Status produk</span>
                  <span class="summary-status-badge" id="sumStatus">Tidak Aktif</span>
                </div>
              </div>

              <div class="summary-actions">
                <button type="submit" class="btn-save-product">Simpan Produk</button>
                <a href="{{ route('admin.dashboard-admin') }}" class="btn-cancel">Batal</a>
              </div>
            </div>

          </div>

        </div>
      </form>

    </main>
  </div>

  <!-- Interactive JavaScript for Live Summary & Image Preview -->
  <script>
    const inputNama = document.getElementById('inputNama');
    const selectKategori = document.getElementById('selectKategori');
    const inputHarga = document.getElementById('inputHarga');
    const inputDiskon = document.getElementById('inputDiskon');
    const inputStok = document.getElementById('inputStok');
    const skuField = document.getElementById('skuField');

    const sumName = document.getElementById('sumName');
    const sumCategory = document.getElementById('sumCategory');
    const sumPrice = document.getElementById('sumPrice');
    const sumDiscount = document.getElementById('sumDiscount');
    const sumStock = document.getElementById('sumStock');
    const sumStatus = document.getElementById('sumStatus');

    const fileInput = document.getElementById('fileGambarInput');
    const btnUpload = document.getElementById('btnUploadTrigger');
    const dropzonePlaceholder = document.getElementById('dropzonePlaceholder');
    const imagePreview = document.getElementById('imagePreview');

    const formatRupiah = (number) => {
      if (!number || isNaN(number)) return '-';
      return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
    };

    function updateSummary() {
      // Nama
      const nameVal = inputNama.value.trim();
      sumName.textContent = nameVal || '-';

      // Kategori
      const selectedOption = selectKategori.options[selectKategori.selectedIndex];
      sumCategory.textContent = (selectedOption && selectedOption.value) ? selectedOption.text : '-';

      // Harga Jual
      const hargaVal = parseFloat(inputHarga.value);
      sumPrice.textContent = hargaVal > 0 ? formatRupiah(hargaVal) : '-';

      // Harga Diskon
      const diskonVal = parseFloat(inputDiskon.value);
      sumDiscount.textContent = diskonVal > 0 ? formatRupiah(diskonVal) : '-';

      // Stok
      const stokVal = parseInt(inputStok.value, 10);
      sumStock.textContent = !isNaN(stokVal) ? stokVal : '-';

      // Status
      if (nameVal && hargaVal > 0 && !isNaN(stokVal) && stokVal > 0) {
        sumStatus.textContent = 'Aktif';
        sumStatus.classList.add('active');
      } else {
        sumStatus.textContent = 'Tidak Aktif';
        sumStatus.classList.remove('active');
      }
    }

    inputNama.addEventListener('input', updateSummary);
    selectKategori.addEventListener('change', updateSummary);
    inputHarga.addEventListener('input', updateSummary);
    inputDiskon.addEventListener('input', updateSummary);
    inputStok.addEventListener('input', updateSummary);

    // Initial update if old values exist
    updateSummary();

    // Image Upload & Preview
    btnUpload.addEventListener('click', () => {
      fileInput.click();
    });

    fileInput.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (event) => {
          imagePreview.src = event.target.result;
          imagePreview.style.display = 'block';
          dropzonePlaceholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
      }
    });
  </script>
</body>
</html>
