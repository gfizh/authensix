<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AuthenSIX Computer - Kategori</title>
  <style>
    :root {
      --text: #111;
      --muted: #666;
      --line: #e5e5e5;
      --pill: #e6e6e6;
      --accent: #1f6fd6;
      --placeholder: #f1f1f1;
    }
    * { box-sizing: border-box; }
    html, body { min-height: 100%; }
    body {
      min-height: 100vh;
      margin: 0;
      display: flex;
      flex-direction: column;
      color: var(--text);
      background: #fff;
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
    }
    button, input { font: inherit; }
    button { color: inherit; background: none; border: 0; cursor: pointer; }
    a { color: inherit; text-decoration: none; }
    :focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }
    .menu { flex: 1; min-height: 0; display: flex; flex-direction: column; background: #fff; }
    .navbar { display: flex; align-items: center; gap: 16px; padding: 14px 24px; }
    .logo { font-size: 22px; font-weight: 700; letter-spacing: -.5px; white-space: nowrap; }
    .logo b { color: var(--accent); }
    .logo small { display: block; color: var(--muted); font-size: 8px; font-weight: 500; letter-spacing: 3px; text-align: center; }
    .kategori { display: flex; align-items: center; gap: 6px; font-size: 15px; font-weight: 600; }
    .search {
      flex: 1;
      max-width: 330px;
      display: flex;
      align-items: center;
      gap: 8px;
      height: 38px;
      padding: 0 10px;
      border: 1.5px solid var(--text);
      border-radius: 8px;
    }
    .search input { flex: 1; min-width: 0; border: 0; outline: 0; background: transparent; font: inherit; }
    .search:focus-within { outline: 2px solid var(--accent); outline-offset: 2px; }
    .nav-spacer { flex: 1; }
    .icon { width: 22px; height: 22px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .heads {
      height: 30px;
      display: grid;
      flex: 0 0 30px;
      grid-template-columns: 170px minmax(0, 1fr);
      align-items: center;
      border-top: 1px solid var(--line);
      border-bottom: 1px solid var(--line);
      font-size: 11px;
    }
    .heads span:first-child { text-align: center; }
    .heads span:last-child { padding-left: 32px; }
    .dropdown { min-height: 0; display: grid; flex: 1; grid-template-columns: 170px minmax(0, 1fr); }
    .sidebar {
      min-height: 0;
      margin: 0;
      padding: 12px 0;
      overflow-y: auto;
      border-right: 1px solid #f0f0f0;
      list-style: none;
      scrollbar-color: #888 #fff;
      scrollbar-width: thin;
    }
    .sidebar li { margin: 0 0 15px; }
    .sidebar button {
      display: block;
      width: calc(100% - 20px);
      margin: 0 auto;
      padding: 8px 6px;
      border-radius: 999px;
      font-size: 12px;
      text-align: center;
    }
    .sidebar button:hover { background: #f3f3f3; }
    .sidebar button.active { background: var(--pill); font-weight: 650; }
    .panel { min-width: 0; min-height: 0; padding: 24px 32px; overflow-y: auto; }
    .category-panel[hidden] { display: none; }
    .category-title { margin: 0 0 18px; font-size: 20px; font-weight: 650; }
    .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)); gap: 22px 18px; }
    .product-card { min-width: 0; padding: 10px; border: 1px solid transparent; border-radius: 10px; transition: border-color .15s, box-shadow .15s; }
    .product-card:hover { border-color: var(--line); box-shadow: 0 4px 16px rgb(0 0 0 / 6%); }
    .product-image { display: grid; aspect-ratio: 1 / .82; place-items: center; overflow: hidden; border-radius: 7px; background: var(--placeholder); }
    .product-image img { width: 100%; height: 100%; object-fit: contain; }
    .product-name { display: -webkit-box; min-height: 38px; margin: 10px 0 4px; overflow: hidden; font-size: 13px; font-weight: 550; line-height: 1.4; -webkit-box-orient: vertical; -webkit-line-clamp: 2; }
    .product-brand { color: var(--muted); font-size: 11px; }
    .product-price { margin: 8px 0 4px; font-size: 14px; font-weight: 700; }
    .product-stock { color: var(--muted); font-size: 11px; }
    .empty-category { padding: 28px 20px; border: 1px dashed #d7d7d7; border-radius: 10px; color: var(--muted); font-size: 13px; text-align: center; }
    @media (max-width: 650px) {
      .navbar { flex-wrap: wrap; gap: 12px; padding: 12px 16px; }
      .search { order: 5; max-width: none; flex-basis: 100%; }
      .heads { grid-template-columns: 120px minmax(0, 1fr); }
      .heads span:last-child { padding-left: 18px; }
      .dropdown { grid-template-columns: 120px minmax(0, 1fr); }
      .sidebar button { width: calc(100% - 12px); padding-right: 3px; padding-left: 3px; font-size: 10px; }
      .panel { padding: 18px 14px; }
      .category-title { font-size: 17px; }
      .product-grid { grid-template-columns: repeat(auto-fill, minmax(135px, 1fr)); gap: 12px; }
      .product-card { padding: 6px; }
    }
  </style>
</head>
<body>
  <div class="menu">
    <header class="navbar">
      <a href="{{ route('dashboard') }}" class="logo" aria-label="AuthenSIX Computer">
        Authen<b>SIX</b>
        <small>COMPUTER</small>
      </a>
      <a href="{{ route('kategori') }}" class="kategori" aria-current="page">
        Kategori
        <svg class="icon" style="width:14px;height:14px" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6" fill="currentColor"/></svg>
      </a>
      <form class="search" action="{{ route('dashboard') }}" role="search">
        <svg class="icon" style="width:18px;height:18px" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
        <input type="search" name="search" aria-label="Cari produk">
      </form>
      <span class="nav-spacer"></span>
      <a href="{{ route('checkout') }}" aria-label="Keranjang">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.6 12.4a1 1 0 0 0 1 .8h8.8a1 1 0 0 0 1-.8L20 7H6"/></svg>
      </a>
    </header>

    <div class="heads" aria-hidden="true">
      <span>Kategori</span>
      <span>Produk</span>
    </div>

    <div class="dropdown">
      <ul class="sidebar" id="sidebar" aria-label="Kategori produk">
        @foreach ($categories as $category)
          <li>
            <button
              type="button"
              class="category-button{{ $loop->first ? ' active' : '' }}"
              data-category="{{ $category->id_kategori }}"
              aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
            >{{ $category->nama_kategori }}</button>
          </li>
        @endforeach
        @foreach ($emptyCategories as $emptyCategory)
          <li>
            <button
              type="button"
              class="category-button"
              data-category="empty-{{ $loop->index }}"
              aria-pressed="false"
            >{{ $emptyCategory }}</button>
          </li>
        @endforeach
      </ul>

      <main class="panel" id="panel" aria-live="polite">
        @foreach ($categories as $category)
          <section
            class="category-panel"
            data-category-panel="{{ $category->id_kategori }}"
            @if (! $loop->first) hidden @endif
          >
            <h1 class="category-title">{{ $category->nama_kategori }}</h1>
            @if ($category->produk->isNotEmpty())
              <div class="product-grid">
                @foreach ($category->produk as $product)
                  <a class="product-card" href="{{ route('produk.detail', $product->id_produk) }}">
                    <div class="product-image">
                      <img src="{{ $product->gambar_url }}" alt="{{ $product->nama_produk }}" loading="lazy">
                    </div>
                    <h2 class="product-name">{{ $product->nama_produk }}</h2>
                    <div class="product-brand">{{ $product->merek?->nama_merek }}</div>
                    <p class="product-price">Rp. {{ number_format((float) $product->harga, 0, ',', '.') }}</p>
                    <div class="product-stock">Stok: {{ $product->stok }}</div>
                  </a>
                @endforeach
              </div>
            @else
              <p class="empty-category">Belum ada produk dalam kategori ini.</p>
            @endif
          </section>
        @endforeach

        @foreach ($emptyCategories as $emptyCategory)
          <section class="category-panel" data-category-panel="empty-{{ $loop->index }}" hidden>
            <h1 class="category-title">{{ $emptyCategory }}</h1>
            <p class="empty-category">Belum ada produk dalam kategori ini.</p>
          </section>
        @endforeach

        @if ($categories->isEmpty() && empty($emptyCategories))
          <p class="empty-category">Belum ada kategori produk yang tersedia.</p>
        @endif
      </main>
    </div>
  </div>

  <script>
    const sidebar = document.getElementById("sidebar");
    const panels = [...document.querySelectorAll("[data-category-panel]")];

    sidebar.addEventListener("click", event => {
      const button = event.target.closest("button[data-category]");
      if (!button || !sidebar.contains(button)) return;

      sidebar.querySelectorAll(".category-button").forEach(categoryButton => {
        const isActive = categoryButton === button;
        categoryButton.classList.toggle("active", isActive);
        categoryButton.setAttribute("aria-pressed", String(isActive));
      });

      panels.forEach(panel => {
        panel.hidden = panel.dataset.categoryPanel !== button.dataset.category;
      });
    });
  </script>
</body>
</html>
