<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Super Admin | AuthenSIX Computer</title>
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
      padding: 24px 24px 16px;
    }

    .brand-link { display: inline-block; }
    .brand-text {
      font-size: 21px;
      font-weight: 750;
      letter-spacing: -0.5px;
      color: #0f172a;
    }
    .brand-text b { color: #1673e6; }
    .brand-sub {
      display: block;
      font-size: 8px;
      font-weight: 600;
      letter-spacing: 3px;
      text-align: center;
      color: var(--text-muted);
      margin-top: -3px;
    }

    .superadmin-badge-label {
      display: inline-block;
      font-size: 9.5px;
      font-weight: 750;
      letter-spacing: 1.5px;
      color: var(--primary);
      text-transform: uppercase;
      margin-top: 10px;
      padding: 3px 8px;
      background: #f0f9ff;
      border-radius: 6px;
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
      width: 19px;
      height: 19px;
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
      background: #e0f2fe;
      color: #0369a1;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 750;
      font-size: 13px;
      flex-shrink: 0;
    }

    .user-meta { flex: 1; min-width: 0; }
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

    .logout-form { display: flex; align-items: center; }
    .logout-btn {
      color: var(--text-muted);
      padding: 6px;
      border-radius: 6px;
      transition: all 0.15s ease;
    }
    .logout-btn:hover {
      color: #ef4444;
      background: #fee2e2;
    }
    .logout-btn svg { width: 18px; height: 18px; }

    /* =====================================================
       LAYOUT & TOP HEADER
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
    }

    .breadcrumb-nav {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 13px;
    }
    .breadcrumb-root { color: var(--text-muted); }
    .breadcrumb-sep { color: #cbd5e1; font-size: 12px; }
    .breadcrumb-current { color: var(--text-main); font-weight: 600; }

    .header-right {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .btn-notification {
      position: relative;
      width: 36px;
      height: 36px;
      border-radius: 10px;
      display: grid;
      place-items: center;
      color: #475569;
      transition: background 0.15s ease;
    }
    .btn-notification:hover { background: #f1f5f9; }
    .btn-notification svg { width: 19px; height: 19px; }
    .notification-badge-dot {
      position: absolute;
      top: 8px;
      right: 8px;
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: #ef4444;
      border: 1.5px solid #ffffff;
    }

    .header-user-badge {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 4px 8px;
      border-radius: 10px;
    }
    .header-avatar {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      background: #e0f2fe;
      color: #0284c7;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 12.5px;
    }
    .header-user-meta { text-align: left; }
    .header-name { font-size: 13px; font-weight: 700; color: var(--text-main); }
    .header-role { font-size: 11px; color: var(--text-muted); }

    /* =====================================================
       CONTENT WRAPPER
       ===================================================== */
    .dashboard-content {
      padding: 28px 32px 48px;
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

    .header-actions {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .select-period {
      padding: 8px 14px;
      background: #ffffff;
      border: 1px solid var(--border);
      border-radius: 10px;
      font-size: 13px;
      font-weight: 600;
      color: #334155;
      outline: none;
      cursor: pointer;
    }

    .btn-create-account {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 9px 18px;
      background: var(--primary);
      color: #ffffff;
      border-radius: 10px;
      font-size: 13.5px;
      font-weight: 600;
      box-shadow: 0 2px 6px rgba(14, 165, 233, 0.25);
      transition: background 0.15s ease;
    }
    .btn-create-account:hover {
      background: var(--primary-hover);
    }

    /* =====================================================
       STAT CARDS ROW
       ===================================================== */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 18px;
      margin-bottom: 24px;
    }

    .stat-card {
      background: #ffffff;
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 20px;
      box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    }

    .stat-label {
      font-size: 13px;
      font-weight: 500;
      color: var(--text-muted);
    }

    .stat-value {
      font-size: 32px;
      font-weight: 750;
      color: #0f172a;
      letter-spacing: -0.5px;
      margin: 8px 0 6px;
    }

    .stat-subtext {
      font-size: 12px;
      color: var(--text-sub);
    }

    /* =====================================================
       MIDDLE SECTION (Chart & Roles)
       ===================================================== */
    .middle-grid {
      display: grid;
      grid-template-columns: 1.6fr 1.1fr;
      gap: 20px;
      margin-bottom: 24px;
    }

    .dashboard-card {
      background: #ffffff;
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 22px;
      box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    }

    .card-head {
      margin-bottom: 18px;
    }
    .card-title {
      font-size: 16px;
      font-weight: 700;
      color: #0f172a;
    }
    .card-subtitle {
      font-size: 12.5px;
      color: var(--text-muted);
      margin-top: 3px;
    }

    /* Activity Bar Chart Mockup */
    .activity-chart {
      display: flex;
      align-items: flex-end;
      justify-content: space-between;
      height: 180px;
      padding: 10px 10px 0;
      border-bottom: 1px solid var(--border-light);
      gap: 14px;
    }

    .chart-bar-group {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
    }

    .chart-bar-val {
      font-size: 11px;
      font-weight: 600;
      color: var(--text-muted);
    }

    .chart-bar {
      width: 100%;
      max-width: 32px;
      background: #38bdf8;
      border-radius: 6px 6px 0 0;
      transition: height 0.3s ease, background 0.15s ease;
      min-height: 8px;
    }
    .chart-bar:hover { background: #0284c7; }
    .chart-bar.current { background: #0284c7; }

    .chart-day {
      font-size: 12px;
      font-weight: 500;
      color: var(--text-muted);
      margin-top: 8px;
    }

    .chart-notice {
      font-size: 11.5px;
      color: var(--text-sub);
      margin-top: 12px;
      text-align: center;
      font-style: italic;
    }

    /* Role Breakdown */
    .roles-list {
      display: flex;
      flex-direction: column;
      gap: 18px;
      margin-top: 10px;
    }

    .role-row-head {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 6px;
    }
    .role-row-title { color: #1e293b; }
    .role-row-count { color: var(--text-muted); font-size: 12.5px; }

    .role-progress-track {
      width: 100%;
      height: 7px;
      background: #f1f5f9;
      border-radius: 999px;
      overflow: hidden;
    }
    .role-progress-fill {
      height: 100%;
      border-radius: 999px;
    }
    .fill-superadmin { background: #0284c7; width: 15%; }
    .fill-admin { background: #38bdf8; width: 35%; }
    .fill-customer { background: #93c5fd; width: 75%; }

    /* =====================================================
       BOTTOM SECTION: RECENT ACTIVITY TABLE
       ===================================================== */
    .table-card-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 16px;
    }

    .link-action-log {
      font-size: 13px;
      font-weight: 600;
      color: var(--primary);
      display: inline-flex;
      align-items: center;
      gap: 4px;
      transition: color 0.15s ease;
    }
    .link-action-log:hover { color: var(--primary-hover); }

    .data-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
    }

    .data-table th {
      text-align: left;
      padding: 12px 16px;
      background: #f8fafc;
      color: var(--text-muted);
      font-weight: 600;
      font-size: 12px;
      border-bottom: 1px solid var(--border);
    }

    .data-table td {
      padding: 14px 16px;
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
      padding: 40px 20px !important;
      text-align: center;
    }
    .empty-state-box {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }
    .empty-state-title {
      font-size: 14px;
      font-weight: 600;
      color: #475569;
      margin-top: 10px;
    }
    .empty-state-desc {
      font-size: 12px;
      color: var(--text-sub);
      margin-top: 4px;
      max-width: 440px;
      line-height: 1.5;
    }

    .dashboard-footer-note {
      margin-top: 24px;
      font-size: 12px;
      color: var(--text-sub);
    }

    @media (max-width: 1024px) {
      .stats-grid { grid-template-columns: repeat(2, 1fr); }
      .middle-grid { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

  @include('super_admin.partials.sidebar')

  <div class="app-main">
    @include('super_admin.partials.top_header', ['currentPageTitle' => 'Dashboard super admin'])

    <main class="dashboard-content">
      <div class="content-header">
        <div class="header-title-group">
          <h1>Dashboard super admin</h1>
          <p>Pantau akun, hak akses, dan seluruh aktivitas administrator.</p>
        </div>

        <div class="header-actions">
          <select class="select-period" aria-label="Rentang waktu">
            <option value="7">7 hari terakhir</option>
            <option value="30">30 hari terakhir</option>
          </select>

          @php
            $buatAkunUrl = Route::has('superadmin.buat_akun') ? route('superadmin.buat_akun') : (Route::has('superadmin.users.create') ? route('superadmin.users.create') : url('/superadmin/buat-akun'));
          @endphp
          <a href="{{ $buatAkunUrl }}" class="btn-create-account">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <line x1="12" y1="5" x2="12" y2="19"/>
              <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            <span>Buat akun</span>
          </a>
        </div>
      </div>

      <!-- 4 Stat Cards Row -->
      <section class="stats-grid" aria-label="Statistik utama">
        <div class="stat-card">
          <div class="stat-label">Total akun</div>
          <div class="stat-value" id="statTotalUsers">{{ $users['total'] ?? '-' }}</div>
          <div class="stat-subtext">{{ ($users['new_this_week'] ?? 0) }} akun baru minggu ini</div>
        </div>

        <div class="stat-card">
          <div class="stat-label">Akun aktif</div>
          <div class="stat-value" id="statActiveUsers">{{ $users['active'] ?? '-' }}</div>
          <div class="stat-subtext">
            @if (!empty($users['total']) && $users['total'] > 0)
              {{ round((($users['active'] ?? 0) / $users['total']) * 100, 1) }}% dari total akun
            @else
              Akses pengguna aktif
            @endif
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-label">Akun nonaktif</div>
          <div class="stat-value" id="statInactiveUsers">{{ $users['inactive'] ?? '0' }}</div>
          <div class="stat-subtext">Akses masuk dibatasi</div>
        </div>

        <div class="stat-card">
          <div class="stat-label">Aktivitas hari ini</div>
          <div class="stat-value" id="statTodayActivities">{{ $activities['today'] ?? '0' }}</div>
          <div class="stat-subtext">{{ $activities['today_success'] ?? 0 }} berhasil &middot; {{ $activities['today_failed'] ?? 0 }} gagal</div>
        </div>
      </section>

      <!-- Middle Section: Activity Bar Chart & Role Breakdown -->
      <section class="middle-grid">
        <div class="dashboard-card">
          <div class="card-head">
            <h2 class="card-title">Aktivitas administrator</h2>
            <p class="card-subtitle">Total aktivitas dalam 7 hari terakhir</p>
          </div>

          <div class="activity-chart" aria-label="Grafik aktivitas mingguan">
            @if (!empty($weeklyActivities) && count($weeklyActivities) > 0)
              @foreach ($weeklyActivities as $day)
                <div class="chart-bar-group">
                  <span class="chart-bar-val">{{ $day['count'] > 0 ? $day['count'] : '-' }}</span>
                  <div class="chart-bar {{ !empty($day['is_today']) ? 'current' : '' }}" style="height: {{ $day['height'] }}%;"></div>
                  <span class="chart-day">{{ $day['day'] }}</span>
                </div>
              @endforeach
            @else
              <div class="chart-bar-group">
                <span class="chart-bar-val">-</span>
                <div class="chart-bar" style="height: 20%;"></div>
                <span class="chart-day">Hari</span>
              </div>
            @endif
          </div>
        </div>

        <div class="dashboard-card">
          <div class="card-head">
            <h2 class="card-title">Akun berdasarkan role</h2>
            <p class="card-subtitle">Pembagian akses pengguna di sistem</p>
          </div>

          <div class="roles-list">
            @php
              $totalUsers = max(1, $users['total'] ?? 1);
              $pctSuper = round((($users['superadmin'] ?? 0) / $totalUsers) * 100);
              $pctAdmin = round((($users['admin'] ?? 0) / $totalUsers) * 100);
              $pctCust = round((($users['customer'] ?? 0) / $totalUsers) * 100);
            @endphp
            <div class="role-row">
              <div class="role-row-head">
                <span class="role-row-title">Super admin</span>
                <span class="role-row-count" id="roleSuperadminCount">{{ isset($users['superadmin']) ? $users['superadmin'].' akun' : '-' }}</span>
              </div>
              <div class="role-progress-track">
                <div class="role-progress-fill fill-superadmin" style="width: {{ $pctSuper }}%;"></div>
              </div>
            </div>

            <div class="role-row">
              <div class="role-row-head">
                <span class="role-row-title">Admin</span>
                <span class="role-row-count" id="roleAdminCount">{{ isset($users['admin']) ? $users['admin'].' akun' : '-' }}</span>
              </div>
              <div class="role-progress-track">
                <div class="role-progress-fill fill-admin" style="width: {{ $pctAdmin }}%;"></div>
              </div>
            </div>

            <div class="role-row">
              <div class="role-row-head">
                <span class="role-row-title">Customer</span>
                <span class="role-row-count" id="roleCustomerCount">{{ isset($users['customer']) ? $users['customer'].' akun' : '-' }}</span>
              </div>
              <div class="role-progress-track">
                <div class="role-progress-fill fill-customer" style="width: {{ $pctCust }}%;"></div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Bottom Section: Aktivitas Terbaru Table -->
      <section class="dashboard-card" style="padding: 24px;">
        <div class="table-card-head">
          <div>
            <h2 class="card-title">Aktivitas terbaru</h2>
            <p class="card-subtitle">Perubahan terakhir di panel administrasi</p>
          </div>
          @php
            $actionLogUrl = Route::has('superadmin.action_log') ? route('superadmin.action_log') : url('/superadmin/action-log');
          @endphp
          <a href="{{ $actionLogUrl }}" class="link-action-log">
            <span>Lihat action log</span>
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
          </a>
        </div>

        <div style="overflow-x: auto;">
          <table class="data-table">
            <thead>
              <tr>
                <th scope="col">Waktu</th>
                <th scope="col">Pengguna</th>
                <th scope="col">Aktivitas</th>
                <th scope="col">Modul</th>
                <th scope="col">Status</th>
              </tr>
            </thead>
            <tbody>
              @if (!empty($recentActivities) && count($recentActivities) > 0)
                @foreach ($recentActivities as $act)
                  <tr>
                    <td>{{ $act->created_at ? $act->created_at->format('d M, H.i') : '-' }}</td>
                    <td>
                      <strong>{{ $act->user_name ?? '-' }}</strong>
                      @if (!empty($act->user_role))
                        <div style="font-size: 11px; color: var(--text-sub);">{{ $act->user_role }}</div>
                      @endif
                    </td>
                    <td>
                      <span style="font-weight: 600;">{{ $act->aktivitas ?? '-' }}</span>
                      @if (!empty($act->detail))
                        <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 2px;">{{ $act->detail }}</div>
                      @endif
                    </td>
                    <td>{{ $act->modul ?? '-' }}</td>
                    <td>
                      <span class="badge-status-pill {{ ($act->status ?? '') === 'Berhasil' ? 'badge-success' : 'badge-danger' }}">
                        {{ $act->status ?? 'Berhasil' }}
                      </span>
                    </td>
                  </tr>
                @endforeach
              @else
                <tr>
                  <td colspan="5" class="empty-state-cell">
                    <div class="empty-state-box">
                      <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                      </svg>
                      <div class="empty-state-title">Belum ada aktivitas tercatat</div>
                      <div class="empty-state-desc">Pencatatan riwayat aktivitas administrator akan tampil otomatis di sini setelah modul Action Log diimplementasikan oleh tim backend.</div>
                    </div>
                  </td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
      </section>

      <div class="dashboard-footer-note">
        Terakhir diperbarui: {{ now()->translatedFormat('d F Y, H.i') }} WIB
      </div>
    </main>
  </div>

  <script>
    // Integrasi dengan endpoint backend /superadmin/dashboard jika di-load melalui browser
    document.addEventListener('DOMContentLoaded', () => {
      fetch('/superadmin/dashboard', {
        headers: { 'Accept': 'application/json' }
      })
      .then(res => res.ok ? res.json() : null)
      .then(data => {
        if (data && data.users) {
          const u = data.users;
          if (u.total !== undefined && document.getElementById('statTotalUsers')) {
            document.getElementById('statTotalUsers').textContent = u.total;
          }
          if (u.superadmin !== undefined && document.getElementById('roleSuperadminCount')) {
            document.getElementById('roleSuperadminCount').textContent = u.superadmin + ' akun';
          }
          if (u.admin !== undefined && document.getElementById('roleAdminCount')) {
            document.getElementById('roleAdminCount').textContent = u.admin + ' akun';
          }
          if (u.customer !== undefined && document.getElementById('roleCustomerCount')) {
            document.getElementById('roleCustomerCount').textContent = u.customer + ' akun';
          }
        }
      })
      .catch(() => {});
    });
  </script>
</body>
</html>
