<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>AuthenSIX Computer - Kategori</title>
<style>
  :root {
    --text: #111111;
    --muted: #666666;
    --line: #e5e5e5;
    --pill: #e6e6e6;
    --overlay: #d9d9d9;
    --accent: #1f6fd6;
  }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  html, body { height: 100%; }
  .dropdown > * { min-height: 0; }
  body {
    font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
    color: var(--text);
    background: #fff;
    display: flex; flex-direction: column;
    height: 100vh; height: 100dvh;
  }
  button { font: inherit; color: inherit; background: none; border: 0; cursor: pointer; }
  a { color: inherit; text-decoration: none; }
  :focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }

  /* ---------- Menu putih (navbar + dropdown) ---------- */
  .menu { background: #fff; flex: 1; display: flex; flex-direction: column; min-height: 0; }

  .navbar {
    display: flex; align-items: center; gap: 16px;
    padding: 14px 24px;
  }
  .logo { font-size: 22px; font-weight: 700; letter-spacing: -0.5px; white-space: nowrap; }
  .logo b { color: var(--accent); }
  .logo small { display: block; font-size: 8px; font-weight: 500; letter-spacing: 3px; text-align: center; color: var(--muted); }
  .kategori { font-weight: 600; font-size: 15px; display: flex; align-items: center; gap: 6px; }
  .search {
    flex: 1; max-width: 330px;
    display: flex; align-items: center; gap: 8px;
    border: 1.5px solid var(--text); border-radius: 8px;
    padding: 0 10px; height: 38px;
  }
  .search input { flex: 1; border: 0; outline: 0; font: inherit; min-width: 0; background: transparent; }
  .search:focus-within { outline: 2px solid var(--accent); outline-offset: 2px; }
  .nav-spacer { flex: 1; }
  .icon { width: 22px; height: 22px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

  /* ---------- Judul kolom ---------- */
  .heads {
    display: grid; grid-template-columns: 150px 1fr;
    border-top: 1px solid var(--line);
    border-bottom: 1px solid var(--line);
    font-size: 11px; height: 28px; align-items: center;
  }
  .heads span:first-child { text-align: center; padding-right: 0; }
  .heads span:last-child { padding-left: 50px; }

  /* ---------- Isi dropdown ---------- */
  .dropdown {
    display: grid; grid-template-columns: 150px 1fr;
    flex: 1; min-height: 0;
  }
  .sidebar {
    list-style: none;
    overflow-y: scroll;
    border-right: 1px solid #f0f0f0;
    padding: 10px 0;
    scrollbar-width: thin;
    scrollbar-color: #777 #fff;
  }
  .sidebar button {
    display: block; width: calc(100% - 20px);
    margin: 0 auto;
    font-size: 11px; text-align: center;
    padding: 6px 4px;
    border-radius: 999px;
  }
  .sidebar li { margin-bottom: 24px; }
  .sidebar li:last-child { margin-bottom: 0; }
  .sidebar button:hover { background: #f3f3f3; }
  .sidebar button.active { background: var(--pill); }

  .panel { padding: 20px 24px; }

  @media (max-width: 600px) {
    .navbar { flex-wrap: wrap; padding: 12px 16px; }
    .search { order: 5; max-width: none; flex-basis: 100%; }
    .dropdown, .heads { grid-template-columns: 120px 1fr; }
    .heads span:last-child { padding-left: 20px; }
  }
</style>
</head>
<body>

<div class="menu">
  <header class="navbar">
    <a href="#" class="logo" aria-label="AuthenSIX Computer">
      Authen<b>SIX</b>
      <small>COMPUTER</small>
    </a>

    <button class="kategori" aria-expanded="true">
      Kategori
      <svg class="icon" style="width:14px;height:14px" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6" fill="currentColor"/></svg>
    </button>

    <label class="search">
      <svg class="icon" style="width:18px;height:18px" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
      <input type="search" aria-label="Cari produk">
    </label>

    <button aria-label="Keranjang">
      <svg class="icon" viewBox="0 0 24 24"><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.6 12.4a1 1 0 0 0 1 .8h8.8a1 1 0 0 0 1-.8L20 7H6"/></svg>
    </button>
  </header>

  <div class="heads">
    <span>Belanja</span>
    <span>Belanja</span>
  </div>

  <div class="dropdown">
    <ul class="sidebar" id="sidebar" aria-label="Kategori"></ul>
    <div class="panel" id="panel" aria-live="polite">
      <!-- Isi subkategori muncul di sini -->
    </div>
  </div>
</div>

<script>
  const kategori = [
    "PC & Desktop", "Laptop", "Komponen PC", "Monitor",
    "Peripheral", "Networking", "Aksesoris Komputer", "Printer & Scanner"
  ];

  const sidebar = document.getElementById("sidebar");
  sidebar.innerHTML = kategori.map((k, i) =>
    `<li><button class="${i === 0 ? "active" : ""}">${k}</button></li>`
  ).join("");

  sidebar.addEventListener("click", e => {
    const btn = e.target.closest("button");
    if (!btn) return;
    sidebar.querySelectorAll("button").forEach(b => b.classList.remove("active"));
    btn.classList.add("active");
    // TODO: isi #panel dengan subkategori dari kategori yang dipilih
  });
</script>
</body>
</html>