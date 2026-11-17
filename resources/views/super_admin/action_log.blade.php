<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Action Log | Super Admin AuthenSIX</title>
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
       PAGE CONTENT & ACTION LOG
       ===================================================== */
    .dashboard-content {
      padding: 28px 32px 56px;
      max-width: 1300px;
      width: 100%;
    }

    .content-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      margin-bottom: 24px;
      flex-wrap: wrap;
    }
    .header-title-group h1 {
      font-size: 24px;
      font-weight: 750;
      color: #0f172a;
      letter-spacing: -0.5px;
    }
    .header-title-group p {
      font-size: 13.5px;
      color: var(--text-muted);
      margin-top: 4px;
    }

    .btn-export-csv {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 9px 18px;
      background: #ffffff;
      border: 1px solid var(--border);
      border-radius: 10px;
      font-size: 13px;
      font-weight: 600;
      color: #334155;
      box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
      transition: background 0.15s ease, border-color 0.15s ease;
    }
    .btn-export-csv:hover { background: #f8fafc; border-color: #cbd5e1; }

    /* Filter Box */
    .filter-card {
      background: #ffffff;
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 20px;
      box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
      margin-bottom: 22px;
    }

    .filter-grid-top {
      display: grid;
      grid-template-columns: 1.8fr 1.2fr;
      gap: 16px;
      margin-bottom: 16px;
    }

    .filter-grid-bottom {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr auto;
      gap: 14px;
      align-items: flex-end;
    }

    .filter-label {
      display: block;
      font-size: 12.5px;
      font-weight: 600;
      color: #475569;
      margin-bottom: 6px;
    }

    .search-input-wrap {
      position: relative;
      display: flex;
      align-items: center;
    }
    .search-icon {
      position: absolute;
      right: 14px;
      width: 17px;
      height: 17px;
      color: #94a3b8;
      pointer-events: none;
    }
    .search-input {
      width: 100%;
      height: 40px;
      padding: 0 40px 0 14px;
      border: 1px solid var(--border);
      border-radius: 10px;
      font-size: 13px;
      color: var(--text-main);
      outline: none;
      transition: border-color 0.15s ease;
    }
    .search-input:focus { border-color: var(--primary); }

    .select-wrap {
      position: relative;
      display: flex;
      align-items: center;
    }
    .filter-select {
      width: 100%;
      height: 40px;
      padding: 0 34px 0 14px;
      border: 1px solid var(--border);
      border-radius: 10px;
      font-size: 13px;
      color: var(--text-main);
      background: #ffffff;
      outline: none;
      cursor: pointer;
      appearance: none;
    }
    .select-arrow {
      position: absolute;
      right: 12px;
      width: 14px;
      height: 14px;
      color: #94a3b8;
      pointer-events: none;
    }

    .btn-reset-filter {
      height: 40px;
      padding: 0 20px;
      border: 1px solid var(--border);
      border-radius: 10px;
      font-size: 13px;
      font-weight: 600;
      color: #475569;
      background: #ffffff;
      transition: background 0.15s ease;
      white-space: nowrap;
    }
    .btn-reset-filter:hover { background: #f1f5f9; color: var(--text-main); }

    /* Main Table Card */
    .main-table-card {
      background: #ffffff;
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 22px 24px;
      box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    }

    .table-head-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 18px;
    }
    .table-title { font-size: 16px; font-weight: 750; color: #0f172a; }
    .table-subtitle { font-size: 12.5px; color: var(--text-muted); margin-top: 3px; }
    .timezone-label { font-size: 12px; color: var(--text-sub); }

    .data-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
    }
    .data-table th {
      text-align: left;
      padding: 12px 14px;
      background: #f8fafc;
      color: var(--text-muted);
      font-weight: 600;
      font-size: 12px;
      border-bottom: 1px solid var(--border);
    }
    .data-table td {
      padding: 13px 14px;
      border-bottom: 1px solid var(--border-light);
      color: #334155;
    }

    .badge-status-pill {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 999px;
      font-size: 11px;
      font-weight: 600;
    }
    .badge-success { background: #dcfce7; color: #15803d; }
    .badge-danger { background: #fee2e2; color: #b91c1c; }

    .empty-state-cell {
      padding: 56px 20px !important;
      text-align: center;
    }
    .empty-state-box {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }
    .empty-state-title {
      font-size: 15px;
      font-weight: 700;
      color: #334155;
      margin-top: 12px;
    }
    .empty-state-desc {
      font-size: 12.5px;
      color: var(--text-sub);
      margin-top: 5px;
      max-width: 480px;
      line-height: 1.5;
    }

    .log-footer-note {
      margin-top: 20px;
      font-size: 12px;
      color: var(--text-sub);
    }

    .pagination-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 20px;
      padding-top: 16px;
      border-top: 1px solid var(--border-light);
      font-size: 12.5px;
      color: var(--text-muted);
    }
    .pagination-btns {
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .page-btn {
      min-width: 30px;
      height: 30px;
      padding: 0 8px;
      border-radius: 6px;
      border: 1px solid var(--border);
      background: #ffffff;
      font-size: 12.5px;
      font-weight: 600;
      color: #475569;
      display: grid;
      place-items: center;
    }
    .page-btn.active {
      background: var(--primary);
      border-color: var(--primary);
      color: #ffffff;
    }

    @media (max-width: 900px) {
      .filter-grid-top { grid-template-columns: 1fr; }
      .filter-grid-bottom { grid-template-columns: 1fr 1fr; }
    }
  </style>
</head>
<body>

  @include('super_admin.partials.sidebar')

  <div class="app-main">
    @include('super_admin.partials.top_header', ['currentPageTitle' => 'Action log'])

    <main class="dashboard-content">
      <div class="content-header">
        <div class="header-title-group">
          <h1>Action log</h1>
          <p>Telusuri aktivitas administrator untuk menjaga keamanan sistem.</p>
        </div>

        <a href="{{ route('superadmin.action_log.export', request()->query()) }}" class="btn-export-csv" title="Ekspor data log ke CSV">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
            <polyline points="7 10 12 15 17 10"/>
            <line x1="12" y1="15" x2="12" y2="3"/>
          </svg>
          <span>Ekspor CSV</span>
        </a>
      </div>

      <!-- Filter Box -->
      <form method="GET" action="{{ route('superadmin.action_log') }}" id="filterForm">
        <section class="filter-card" aria-label="Filter action log">
          <div class="filter-grid-top">
            <div>
              <label class="filter-label" for="filterSearch">Cari aktivitas</label>
              <div class="search-input-wrap">
                <input
                  type="text"
                  id="filterSearch"
                  name="search"
                  value="{{ $filters['search'] ?? '' }}"
                  class="search-input"
                  placeholder="Cari nama pengguna atau aktivitas..."
                >
                <button type="submit" style="display:none;" aria-hidden="true"></button>
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="11" cy="11" r="8"/>
                  <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
              </div>
            </div>

            <div>
              <label class="filter-label" for="filterDate">Rentang tanggal</label>
              <div class="select-wrap">
                <select id="filterDate" name="date" class="filter-select" onchange="this.form.submit()">
                  <option value="all" {{ ($filters['date'] ?? '') === 'all' ? 'selected' : '' }}>Semua waktu</option>
                  <option value="7" {{ ($filters['date'] ?? '') === '7' ? 'selected' : '' }}>7 hari terakhir</option>
                  <option value="30" {{ ($filters['date'] ?? '') === '30' ? 'selected' : '' }}>30 hari terakhir</option>
                </select>
                <svg class="select-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
              </div>
            </div>
          </div>

          <div class="filter-grid-bottom">
            <div>
              <label class="filter-label" for="filterUser">Pengguna</label>
              <div class="select-wrap">
                <select id="filterUser" name="user" class="filter-select" onchange="this.form.submit()">
                  <option value="">Semua pengguna</option>
                  @if (!empty($usersList))
                    @foreach ($usersList as $u)
                      <option value="{{ $u->nama_lengkap }}" {{ ($filters['user'] ?? '') == $u->nama_lengkap ? 'selected' : '' }}>
                        {{ $u->nama_lengkap }} ({{ ucfirst($u->role) }})
                      </option>
                    @endforeach
                  @endif
                </select>
                <svg class="select-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
              </div>
            </div>

            <div>
              <label class="filter-label" for="filterModule">Modul</label>
              <div class="select-wrap">
                <select id="filterModule" name="modul" class="filter-select" onchange="this.form.submit()">
                  <option value="">Semua modul</option>
                  <option value="Akun" {{ ($filters['modul'] ?? '') === 'Akun' ? 'selected' : '' }}>Akun</option>
                  <option value="Produk" {{ ($filters['modul'] ?? '') === 'Produk' ? 'selected' : '' }}>Produk</option>
                  <option value="Autentikasi" {{ ($filters['modul'] ?? '') === 'Autentikasi' ? 'selected' : '' }}>Autentikasi</option>
                  <option value="Pesanan" {{ ($filters['modul'] ?? '') === 'Pesanan' ? 'selected' : '' }}>Pesanan</option>
                </select>
                <svg class="select-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
              </div>
            </div>

            <div>
              <label class="filter-label" for="filterStatus">Status</label>
              <div class="select-wrap">
                <select id="filterStatus" name="status" class="filter-select" onchange="this.form.submit()">
                  <option value="">Semua status</option>
                  <option value="success" {{ ($filters['status'] ?? '') === 'success' ? 'selected' : '' }}>Berhasil</option>
                  <option value="failed" {{ ($filters['status'] ?? '') === 'failed' ? 'selected' : '' }}>Gagal</option>
                </select>
                <svg class="select-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
              </div>
            </div>

            <div>
              <a href="{{ route('superadmin.action_log') }}" class="btn-reset-filter" style="display:inline-flex; align-items:center; justify-content:center; text-decoration:none;">Reset filter</a>
            </div>
          </div>
        </section>
      </form>

      <!-- Main Action Log Table Card -->
      <section class="main-table-card" aria-label="Riwayat aktivitas">
        <div class="table-head-row">
          <div>
            <h2 class="table-title">Riwayat aktivitas</h2>
            <p class="table-subtitle">{{ isset($logs) ? $logs->total() : 0 }} aktivitas</p>
          </div>
          <span class="timezone-label">Waktu ditampilkan dalam WIB</span>
        </div>

        <div style="overflow-x: auto;">
          <table class="data-table">
            <thead>
              <tr>
                <th scope="col">Waktu</th>
                <th scope="col">Pengguna</th>
                <th scope="col">Aktivitas</th>
                <th scope="col">Modul</th>
                <th scope="col">Alamat IP</th>
                <th scope="col">Status</th>
                <th scope="col" style="width: 40px;"></th>
              </tr>
            </thead>
            <tbody>
              @if (!empty($logs) && $logs->count() > 0)
                @foreach ($logs as $log)
                  <tr>
                    <td>
                      {{ $log->created_at ? $log->created_at->format('d M Y') : '-' }}
                      <br>
                      <small style="color: var(--text-sub); font-size: 11px;">{{ $log->created_at ? $log->created_at->format('H.i.s') : '' }}</small>
                    </td>
                    <td>
                      <strong>{{ $log->user_name ?? '-' }}</strong>
                      <div style="font-size: 11px; color: var(--text-sub);">{{ $log->user_role ?? '-' }}</div>
                    </td>
                    <td>
                      <span style="font-weight: 600;">{{ $log->aktivitas ?? '-' }}</span>
                      @if (!empty($log->detail))
                        <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 2px;">{{ $log->detail }}</div>
                      @endif
                    </td>
                    <td>{{ $log->modul ?? '-' }}</td>
                    <td>{{ $log->ip_address ?? '-' }}</td>
                    <td>
                      <span class="badge-status-pill {{ ($log->status ?? '') === 'Berhasil' ? 'badge-success' : 'badge-danger' }}">
                        {{ $log->status ?? 'Berhasil' }}
                      </span>
                    </td>
                    <td>
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                    </td>
                  </tr>
                @endforeach
              @else
                <tr>
                  <td colspan="7" class="empty-state-cell">
                    <div class="empty-state-box">
                      <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                      </svg>
                      <div class="empty-state-title">Belum ada aktivitas tercatat</div>
                      <div class="empty-state-desc">
                        Aktivitas administrator yang tercatat oleh sistem akan tampil secara otomatis pada riwayat ini.
                      </div>
                    </div>
                  </td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>

        <div class="pagination-bar">
          @if (!empty($logs) && $logs->total() > 0)
            <span>Menampilkan {{ $logs->firstItem() }}–{{ $logs->lastItem() }} dari {{ $logs->total() }} aktivitas</span>
            <div class="pagination-btns">
              @if ($logs->onFirstPage())
                <button class="page-btn" disabled>&lt;</button>
              @else
                <a href="{{ $logs->previousPageUrl() }}" class="page-btn">&lt;</a>
              @endif

              <span class="page-btn active">{{ $logs->currentPage() }}</span>

              @if ($logs->hasMorePages())
                <a href="{{ $logs->nextPageUrl() }}" class="page-btn">&gt;</a>
              @else
                <button class="page-btn" disabled>&gt;</button>
              @endif
            </div>
          @else
            <span>Menampilkan 0 dari 0 aktivitas</span>
            <div class="pagination-btns">
              <button class="page-btn" disabled>&lt;</button>
              <button class="page-btn active">1</button>
              <button class="page-btn" disabled>&gt;</button>
            </div>
          @endif
        </div>
      </section>

      <div class="log-footer-note">
        Log bersifat hanya-baca. Seluruh perubahan akun dan aktivitas administrator dicatat otomatis.
      </div>
    </main>
  </div>

  <script>
    function resetActionLogFilters() {
      document.getElementById('filterSearch').value = '';
      document.getElementById('filterUser').value = '';
      document.getElementById('filterModule').value = '';
      document.getElementById('filterStatus').value = '';
    }
  </script>
</body>
</html>
