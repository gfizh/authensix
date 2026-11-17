@php
    $dashboardUrl = Route::has('superadmin.dashboard') ? route('superadmin.dashboard') : url('/superadmin/dashboard');
    $createAccountUrl = Route::has('superadmin.buat_akun') ? route('superadmin.buat_akun') : (Route::has('superadmin.users.create') ? route('superadmin.users.create') : url('/superadmin/buat-akun'));
    $actionLogUrl = Route::has('superadmin.action_log') ? route('superadmin.action_log') : url('/superadmin/action-log');

    $isDashboardActive = request()->routeIs('superadmin.dashboard') || request()->is('superadmin/dashboard*');
    $isCreateAccountActive = request()->routeIs('superadmin.buat_akun', 'superadmin.users.create') || request()->is('superadmin/buat-akun*');
    $isActionLogActive = request()->routeIs('superadmin.action_log') || request()->is('superadmin/action-log*');
@endphp

<aside class="sidebar" aria-label="Menu Super Admin">
  <div class="sidebar-brand">
    <a href="{{ $dashboardUrl }}" class="brand-link" aria-label="AuthenSIX Computer">
      <div class="brand-text">Authen<b>SIX</b></div>
      <small class="brand-sub">COMPUTER</small>
    </a>
    <div class="superadmin-badge-label">SUPER ADMIN</div>
  </div>

  <nav class="sidebar-nav">
    <a href="{{ $dashboardUrl }}"
       class="nav-item {{ $isDashboardActive ? 'active' : '' }}"
       @if ($isDashboardActive) aria-current="page" @endif>
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="3" y="3" width="7" height="7" rx="1.5"/>
        <rect x="14" y="3" width="7" height="7" rx="1.5"/>
        <rect x="3" y="14" width="7" height="7" rx="1.5"/>
        <rect x="14" y="14" width="7" height="7" rx="1.5"/>
      </svg>
      <span>Dashboard</span>
    </a>

    <a href="{{ $createAccountUrl }}"
       class="nav-item {{ $isCreateAccountActive ? 'active' : '' }}"
       @if ($isCreateAccountActive) aria-current="page" @endif>
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
        <circle cx="8.5" cy="7" r="4"/>
        <line x1="20" y1="8" x2="20" y2="14"/>
        <line x1="23" y1="11" x2="17" y2="11"/>
      </svg>
      <span>Buat akun</span>
    </a>

    <a href="{{ $actionLogUrl }}"
       class="nav-item {{ $isActionLogActive ? 'active' : '' }}"
       @if ($isActionLogActive) aria-current="page" @endif>
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"/>
        <polyline points="12 6 12 12 16 14"/>
      </svg>
      <span>Action log</span>
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="user-card">
      <div class="user-avatar" title="{{ auth()->user()->nama_lengkap ?? auth()->user()->username ?? 'Super Admin' }}">
        {{ strtoupper(substr(auth()->user()->nama_lengkap ?? auth()->user()->username ?? 'GU', 0, 2)) }}
      </div>
      <div class="user-meta">
        <div class="user-name">{{ auth()->user()->nama_lengkap ?? auth()->user()->username ?? 'GURUHHHH' }}</div>
        <div class="user-role">Super admin</div>
      </div>
      <form method="POST" action="{{ route('logout') }}" class="logout-form">
        @csrf
        <button type="submit" class="logout-btn" title="Keluar dari akun">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            <polyline points="16 17 21 12 16 7"/>
            <line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
        </button>
      </form>
    </div>
  </div>
</aside>
