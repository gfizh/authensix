<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buat Akun | Super Admin AuthenSIX</title>
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
      --primary: #0ea5e9;
      --primary-hover: #0284c7;
      --primary-light: #e0f2fe;
      --sidebar-width: 240px;
      --header-height: 64px;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: "Inter", system-ui, -apple-system, sans-serif;
      background: var(--bg);
      color: var(--text-main);
      -webkit-font-smoothing: antialiased;
      display: flex;
      min-height: 100vh;
    }

    a { color: inherit; text-decoration: none; }
    button { font: inherit; color: inherit; background: none; border: 0; cursor: pointer; }

    /* =====================================================
       SIDEBAR & TOP HEADER (Consistent)
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

    .sidebar-brand { padding: 24px 24px 16px; }
    .brand-link { display: inline-block; }
    .brand-text { font-size: 21px; font-weight: 750; letter-spacing: -0.5px; color: #0f172a; }
    .brand-text b { color: #1673e6; }
    .brand-sub {
      display: block; font-size: 8px; font-weight: 600; letter-spacing: 3px;
      text-align: center; color: var(--text-muted); margin-top: -3px;
    }
    .superadmin-badge-label {
      display: inline-block; font-size: 9.5px; font-weight: 750; letter-spacing: 1.5px;
      color: var(--primary); text-transform: uppercase; margin-top: 10px;
      padding: 3px 8px; background: #f0f9ff; border-radius: 6px;
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
    .nav-item:hover { background: #f1f5f9; color: var(--text-main); }
    .nav-item.active { background: var(--primary); color: #ffffff; font-weight: 600; }
    .nav-icon { width: 19px; height: 19px; flex-shrink: 0; }

    .sidebar-footer { padding: 16px 14px; border-top: 1px solid var(--border-light); }
    .user-card {
      background: #f8fafc; border: 1px solid var(--border); border-radius: 12px;
      padding: 10px 12px; display: flex; align-items: center; gap: 10px;
    }
    .user-avatar {
      width: 36px; height: 36px; border-radius: 50%; background: #e0f2fe; color: #0369a1;
      display: flex; align-items: center; justify-content: center; font-weight: 750; font-size: 13px;
    }
    .user-meta { flex: 1; min-width: 0; }
    .user-name { font-size: 13px; font-weight: 700; color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .user-role { font-size: 11px; color: var(--text-muted); text-transform: capitalize; }
    .logout-form { display: flex; align-items: center; }
    .logout-btn { color: var(--text-muted); padding: 6px; border-radius: 6px; transition: all 0.15s ease; }
    .logout-btn:hover { color: #ef4444; background: #fee2e2; }
    .logout-btn svg { width: 18px; height: 18px; }

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
    }
    .breadcrumb-nav { display: flex; align-items: center; gap: 8px; font-size: 13px; }
    .breadcrumb-root { color: var(--text-muted); }
    .breadcrumb-sep { color: #cbd5e1; font-size: 12px; }
    .breadcrumb-current { color: var(--text-main); font-weight: 600; }
    .header-right { display: flex; align-items: center; gap: 16px; }
    .btn-notification {
      position: relative; width: 36px; height: 36px; border-radius: 10px; display: grid;
      place-items: center; color: #475569; transition: background 0.15s ease;
    }
    .btn-notification:hover { background: #f1f5f9; }
    .btn-notification svg { width: 19px; height: 19px; }
    .notification-badge-dot {
      position: absolute; top: 8px; right: 8px; width: 7px; height: 7px; border-radius: 50%;
      background: #ef4444; border: 1.5px solid #ffffff;
    }
    .header-user-badge { display: flex; align-items: center; gap: 10px; padding: 4px 8px; }
    .header-avatar {
      width: 34px; height: 34px; border-radius: 50%; background: #e0f2fe; color: #0284c7;
      display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12.5px;
    }
    .header-name { font-size: 13px; font-weight: 700; color: var(--text-main); }
    .header-role { font-size: 11px; color: var(--text-muted); }

    /* =====================================================
       PAGE CONTENT & 2-COLUMN LAYOUT
       ===================================================== */
    .dashboard-content {
      padding: 28px 32px 56px;
      max-width: 1240px;
      width: 100%;
    }

    .content-header { margin-bottom: 24px; }
    .content-header h1 {
      font-size: 24px;
      font-weight: 750;
      color: #0f172a;
      letter-spacing: -0.5px;
    }
    .content-header p {
      font-size: 13.5px;
      color: var(--text-muted);
      margin-top: 4px;
    }

    .create-grid {
      display: grid;
      grid-template-columns: 1.65fr 1fr;
      gap: 24px;
      align-items: flex-start;
    }

    .form-card {
      background: #ffffff;
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 24px;
      box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
      margin-bottom: 20px;
    }

    .form-card-title {
      font-size: 16px;
      font-weight: 700;
      color: #0f172a;
    }
    .form-card-subtitle {
      font-size: 12px;
      color: var(--text-muted);
      margin-top: 2px;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 18px;
    }
    .form-group:last-child { margin-bottom: 0; }

    .form-label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: #334155;
      margin-bottom: 7px;
    }

    .form-input-wrap {
      position: relative;
      display: flex;
      align-items: center;
    }

    .form-input-icon {
      position: absolute;
      left: 14px;
      width: 17px;
      height: 17px;
      color: #94a3b8;
      pointer-events: none;
    }

    .form-input {
      width: 100%;
      height: 42px;
      padding: 0 14px;
      border: 1px solid var(--border);
      border-radius: 10px;
      font-size: 13.5px;
      color: var(--text-main);
      background: #ffffff;
      transition: border-color 0.15s ease, box-shadow 0.15s ease;
      outline: none;
    }
    .form-input.has-icon { padding-left: 40px; }
    .form-input:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.12);
    }

    .grid-2col {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }

    .form-hint {
      font-size: 12px;
      color: var(--text-sub);
      margin-top: 6px;
    }

    .select-wrap {
      position: relative;
      display: flex;
      align-items: center;
    }
    .form-select {
      width: 100%;
      height: 42px;
      padding: 0 36px 0 14px;
      border: 1px solid var(--border);
      border-radius: 10px;
      font-size: 13.5px;
      color: var(--text-main);
      background: #ffffff;
      outline: none;
      cursor: pointer;
      appearance: none;
      transition: border-color 0.15s ease;
    }
    .form-select:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.12);
    }
    .select-chevron {
      position: absolute;
      right: 14px;
      width: 15px;
      height: 15px;
      color: #94a3b8;
      pointer-events: none;
    }

    .checkbox-label {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 13px;
      color: #334155;
      cursor: pointer;
      user-select: none;
    }
    .checkbox-custom {
      width: 18px;
      height: 18px;
      accent-color: var(--primary);
      cursor: pointer;
    }

    /* Form Action Footer */
    .form-action-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      margin-top: 12px;
    }
    .footer-review-text {
      font-size: 12px;
      color: var(--text-sub);
    }
    .footer-btns {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .btn-cancel {
      padding: 9px 20px;
      border: 1px solid var(--border);
      border-radius: 10px;
      font-size: 13.5px;
      font-weight: 600;
      color: #475569;
      background: #ffffff;
      transition: background 0.15s ease;
    }
    .btn-cancel:hover { background: #f1f5f9; color: var(--text-main); }
    .btn-submit {
      padding: 9px 22px;
      border-radius: 10px;
      font-size: 13.5px;
      font-weight: 600;
      color: #ffffff;
      background: var(--primary);
      box-shadow: 0 2px 6px rgba(14, 165, 233, 0.25);
      transition: background 0.15s ease;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .btn-submit:hover { background: var(--primary-hover); }

    /* =====================================================
       RIGHT COLUMN CARDS
       ===================================================== */
    .side-card {
      background: #ffffff;
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 22px;
      box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
      margin-bottom: 20px;
    }

    .role-preview-badge {
      font-size: 14px;
      font-weight: 700;
      color: #0284c7;
      margin-bottom: 6px;
    }
    .role-preview-desc {
      font-size: 12.5px;
      color: var(--text-muted);
      line-height: 1.5;
      padding-bottom: 14px;
      border-bottom: 1px solid var(--border-light);
      margin-bottom: 16px;
    }

    .permissions-list {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 11px;
    }
    .permission-item {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 13px;
    }
    .permission-item.allowed { color: #334155; }
    .permission-item.denied { color: #94a3b8; }
    .perm-icon-check { color: #0ea5e9; width: 16px; height: 16px; flex-shrink: 0; }
    .perm-icon-dash { color: #cbd5e1; width: 14px; height: 14px; flex-shrink: 0; }

    /* Info Box */
    .info-card-blue {
      background: #f0f9ff;
      border: 1px solid #bae6fd;
      border-radius: 14px;
      padding: 20px;
      margin-bottom: 20px;
    }
    .info-card-blue h4 {
      font-size: 13.5px;
      font-weight: 700;
      color: #0369a1;
      margin-bottom: 6px;
    }
    .info-card-blue p {
      font-size: 12.5px;
      color: #0284c7;
      line-height: 1.5;
      margin-bottom: 10px;
    }
    .info-card-blue small {
      display: block;
      font-size: 11.5px;
      color: #0369a1;
    }

    .notification-option-card {
      background: #ffffff;
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 18px 20px;
      display: flex;
      align-items: flex-start;
      gap: 12px;
    }
    .notif-title { font-size: 13px; font-weight: 600; color: #1e293b; }
    .notif-desc { font-size: 12px; color: var(--text-muted); margin-top: 2px; }

    @media (max-width: 960px) {
      .create-grid { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

  @include('super_admin.partials.sidebar')

  <div class="app-main">
    @include('super_admin.partials.top_header', ['currentPageTitle' => 'Buat akun'])

    <main class="dashboard-content">
      <div class="content-header">
        <h1>Buat akun</h1>
        <p>Tambahkan akun baru dan tentukan hak aksesnya.</p>
      </div>

      @if (session('success'))
        <div style="background:#dcfce7;border:1px solid #86efac;color:#15803d;padding:12px 18px;border-radius:10px;font-size:13px;margin-bottom:20px;">
          {{ session('success') }}
        </div>
      @endif

      @if ($errors->any())
        <div style="background:#fee2e2;border:1px solid #fca5a5;color:#b91c1c;padding:12px 18px;border-radius:10px;font-size:13px;margin-bottom:20px;">
          <ul style="margin-left: 18px;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @php
        $formActionUrl = Route::has('superadmin.users.store') ? route('superadmin.users.store') : url('/superadmin/users');
        $cancelUrl = Route::has('superadmin.dashboard') ? route('superadmin.dashboard') : url('/superadmin/dashboard');
      @endphp

      <form action="{{ $formActionUrl }}" method="POST">
        @csrf

        <div class="create-grid">
          <!-- Left Column -->
          <div class="left-col">
            <!-- Card 1: Informasi akun -->
            <div class="form-card">
              <h2 class="form-card-title">Informasi akun</h2>
              <p class="form-card-subtitle">Kolom bertanda * wajib diisi.</p>

              <div class="form-group">
                <label class="form-label" for="nama_lengkap">Nama lengkap *</label>
                <input
                  type="text"
                  name="nama_lengkap"
                  id="nama_lengkap"
                  class="form-input"
                  placeholder="Masukkan nama lengkap"
                  value="{{ old('nama_lengkap') }}"
                  required
                >
              </div>

              <div class="grid-2col form-group">
                <div>
                  <label class="form-label" for="email">Email *</label>
                  <div class="form-input-wrap">
                    <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <rect x="3" y="5" width="18" height="14" rx="2"/>
                      <polyline points="3 7 12 13 21 7"/>
                    </svg>
                    <input
                      type="email"
                      name="email"
                      id="email"
                      class="form-input has-icon"
                      placeholder="nama@authensix.com"
                      value="{{ old('email') }}"
                      required
                    >
                  </div>
                  <div class="form-hint">Gunakan email aktif untuk menerima notifikasi akun.</div>
                </div>

                <div>
                  <label class="form-label" for="username">Username *</label>
                  <input
                    type="text"
                    name="username"
                    id="username"
                    class="form-input"
                    placeholder="Masukkan username"
                    value="{{ old('username') }}"
                    required
                  >
                </div>
              </div>

              <!-- Input Nomor Telepon pendukung untuk validasi endpoint backend saat ini -->
              <input type="hidden" name="nomor_telepon" id="nomor_telepon" value="{{ old('nomor_telepon', '081234567890') }}">
            </div>

            <!-- Card 2: Keamanan & akses -->
            <div class="form-card">
              <h2 class="form-card-title">Keamanan &amp; akses</h2>
              <p class="form-card-subtitle">Atur level izin dan kata sandi masuk pengguna.</p>

              <div class="grid-2col form-group">
                <div>
                  <label class="form-label" for="roleSelect">Role *</label>
                  <div class="select-wrap">
                    <select name="role" id="roleSelect" class="form-select" required>
                      <option value="admin" {{ old('role', 'admin') === 'admin' ? 'selected' : '' }}>Admin</option>
                      <option value="customer" {{ old('role') === 'customer' ? 'selected' : '' }}>Customer</option>
                      <option value="superadmin" {{ old('role') === 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                    <svg class="select-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                  </div>
                </div>

                <div>
                  <label class="form-label" for="statusSelect">Status akun *</label>
                  <div class="select-wrap">
                    <select name="status" id="statusSelect" class="form-select">
                      <option value="active" selected>Aktif</option>
                      <option value="inactive">Nonaktif</option>
                    </select>
                    <svg class="select-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                  </div>
                </div>
              </div>

              <div class="grid-2col form-group">
                <div>
                  <label class="form-label" for="password">Kata sandi *</label>
                  <div class="form-input-wrap">
                    <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                      <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <input
                      type="password"
                      name="password"
                      id="password"
                      class="form-input has-icon"
                      placeholder="Masukkan kata sandi"
                      minlength="8"
                      required
                    >
                  </div>
                </div>

                <div>
                  <label class="form-label" for="password_confirmation">Konfirmasi kata sandi *</label>
                  <div class="form-input-wrap">
                    <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                      <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <input
                      type="password"
                      name="password_confirmation"
                      id="password_confirmation"
                      class="form-input has-icon"
                      placeholder="Ulangi kata sandi"
                      minlength="8"
                      required
                    >
                  </div>
                </div>
              </div>

              <div class="form-hint" style="margin-bottom: 16px;">
                Minimal 8 karakter, kombinasi huruf besar, huruf kecil, dan angka.
              </div>

              <label class="checkbox-label">
                <input type="checkbox" name="must_change_password" value="1" class="checkbox-custom" checked>
                <span>Wajib ganti kata sandi saat pertama kali masuk</span>
              </label>
            </div>

            <!-- Action Bottom -->
            <div class="form-action-footer">
              <span class="footer-review-text">Periksa kembali informasi sebelum menyimpan.</span>
              <div class="footer-btns">
                <a href="{{ $cancelUrl }}" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                  <span>Buat akun</span>
                </button>
              </div>
            </div>
          </div>

          <!-- Right Column -->
          <div class="right-col">
            <!-- Card 1: Hak akses role -->
            <div class="side-card">
              <h3 class="form-card-title" style="margin-bottom: 10px;">Hak akses role</h3>
              <div class="role-preview-badge" id="roleBadgeTitle">Admin</div>
              <p class="role-preview-desc" id="roleDescText">
                Mengelola operasional toko tanpa akses pengelolaan akun administrator.
              </p>

              <ul class="permissions-list" id="permissionsList">
                <li class="permission-item allowed">
                  <svg class="perm-icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                  <span>Kelola produk</span>
                </li>
                <li class="permission-item allowed">
                  <svg class="perm-icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                  <span>Kelola pesanan</span>
                </li>
                <li class="permission-item allowed">
                  <svg class="perm-icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                  <span>Lihat laporan penjualan</span>
                </li>
                <li class="permission-item denied">
                  <svg class="perm-icon-dash" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/></svg>
                  <span>Buat dan kelola akun</span>
                </li>
                <li class="permission-item denied">
                  <svg class="perm-icon-dash" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/></svg>
                  <span>Lihat action log</span>
                </li>
              </ul>
            </div>

            <!-- Card 2: Keamanan akun -->
            <div class="info-card-blue">
              <h4>Keamanan akun</h4>
              <p>Pastikan role sesuai tanggung jawab pengguna. Akses pengelolaan akun dan action log hanya untuk super admin.</p>
              <small>Pembuatan akun akan tercatat otomatis di action log.</small>
            </div>

            <!-- Card 3: Kirim notifikasi akun baru -->
            <div class="notification-option-card">
              <input type="checkbox" name="send_notification" value="1" class="checkbox-custom" checked id="notifCheck">
              <label for="notifCheck" style="cursor: pointer;">
                <div class="notif-title">Kirim notifikasi akun baru</div>
                <div class="notif-desc">Ke alamat email pengguna</div>
              </label>
            </div>
          </div>
        </div>
      </form>
    </main>
  </div>

  <script>
    // Interaktivitas Dinamis Hak Akses Role berdasarkan pilihan Role
    const roleSelect = document.getElementById('roleSelect');
    const roleBadgeTitle = document.getElementById('roleBadgeTitle');
    const roleDescText = document.getElementById('roleDescText');
    const permissionsList = document.getElementById('permissionsList');

    const roleData = {
      admin: {
        title: 'Admin',
        desc: 'Mengelola operasional toko tanpa akses pengelolaan akun administrator.',
        perms: [
          { name: 'Kelola produk', allowed: true },
          { name: 'Kelola pesanan', allowed: true },
          { name: 'Lihat laporan penjualan', allowed: true },
          { name: 'Buat dan kelola akun', allowed: false },
          { name: 'Lihat action log', allowed: false },
        ]
      },
      customer: {
        title: 'Customer',
        desc: 'Akun pelanggan pembeli produk toko melalui antarmuka katalog pembeli.',
        perms: [
          { name: 'Belanja & transaksi produk', allowed: true },
          { name: 'Riwayat pesanan pribadi', allowed: true },
          { name: 'Kelola produk toko', allowed: false },
          { name: 'Kelola pesanan pelanggan', allowed: false },
          { name: 'Akses modul administrasi', allowed: false },
        ]
      },
      superadmin: {
        title: 'Super Admin',
        desc: 'Memiliki hak akses penuh sistem termasuk manajemen akun dan pengawasan keamanan.',
        perms: [
          { name: 'Kelola produk & pesanan', allowed: true },
          { name: 'Buat dan kelola akun administrator', allowed: true },
          { name: 'Lihat dan ekspor action log', allowed: true },
          { name: 'Kontrol keamanan & role', allowed: true },
          { name: 'Akses seluruh laporan sistem', allowed: true },
        ]
      }
    };

    function updateRolePreview() {
      const selected = roleSelect.value;
      const data = roleData[selected] || roleData.admin;

      roleBadgeTitle.textContent = data.title;
      roleDescText.textContent = data.desc;

      permissionsList.innerHTML = data.perms.map(p => `
        <li class="permission-item ${p.allowed ? 'allowed' : 'denied'}">
          ${p.allowed
            ? '<svg class="perm-icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>'
            : '<svg class="perm-icon-dash" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/></svg>'
          }
          <span>${p.name}</span>
        </li>
      `).join('');
    }

    roleSelect.addEventListener('change', updateRolePreview);
  </script>
</body>
</html>
