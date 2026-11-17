<header class="top-header">
  <nav class="breadcrumb-nav" aria-label="Breadcrumb">
    <span class="breadcrumb-root">Super admin</span>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">{{ $currentPageTitle ?? 'Dashboard super admin' }}</span>
  </nav>

  <div class="header-right">
    <button type="button" class="btn-notification" aria-label="Notifikasi" title="Notifikasi aktivitas">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
      </svg>
      <span class="notification-badge-dot" aria-hidden="true"></span>
    </button>

    <div class="header-user-badge">
      <div class="header-avatar" title="{{ auth()->user()->nama_lengkap ?? auth()->user()->username ?? 'Super Admin' }}">
        {{ strtoupper(substr(auth()->user()->nama_lengkap ?? auth()->user()->username ?? 'GU', 0, 2)) }}
      </div>
      <div class="header-user-meta">
        <div class="header-name">{{ auth()->user()->nama_lengkap ?? auth()->user()->username ?? 'GURUHHHH' }}</div>
        <div class="header-role">Super admin</div>
      </div>
    </div>
  </div>
</header>
