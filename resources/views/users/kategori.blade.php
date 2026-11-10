<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AuthenSIX Computer - Kategori</title>
  <style>
    :root {
      --text: #0f172a;
      --muted: #64748b;
      --line: #e2e8f0;
      --pill: #eff6ff;
      --pill-text: #1d4ed8;
      --accent: #1673e6;
      --placeholder: #f8fafc;
    }
    * { box-sizing: border-box; }
    html, body { min-height: 100%; }
    body {
      min-height: 100vh;
      margin: 0;
      display: flex;
      flex-direction: column;
      color: var(--text);
      background: #ffffff;
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
      -webkit-font-smoothing: antialiased;
    }
    button, input { font: inherit; }
    button { color: inherit; background: none; border: 0; cursor: pointer; }
    a { color: inherit; text-decoration: none; }
    :focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }

    .menu { flex: 1; min-height: 0; display: flex; flex-direction: column; background: #ffffff; }

    /* ---------- Navbar ---------- */
    .navbar {
      display: flex;
      align-items: center;
      gap: 16px;
      padding: 14px 24px;
      border-bottom: 1px solid var(--line);
      background: #ffffff;
    }
    .logo {
      font-size: 22px;
      font-weight: 750;
      letter-spacing: -.5px;
      white-space: nowrap;
    }
    .logo b { color: var(--accent); }
    .logo small {
      display: block;
      color: var(--muted);
      font-size: 8px;
      font-weight: 500;
      letter-spacing: 3px;
      text-align: center;
      margin-top: -2px;
    }
    .kategori {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 14.5px;
      font-weight: 600;
      color: var(--accent);
    }
    .search {
      flex: 1;
      max-width: 320px;
      display: flex;
      align-items: center;
      gap: 8px;
      height: 38px;
      padding: 0 10px;
      border: 1.5px solid #cbd5e1;
      border-radius: 8px;
      background: #ffffff;
      transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .search input {
      flex: 1;
      min-width: 0;
      border: 0;
      outline: 0;
      background: transparent;
      font: inherit;
      color: var(--text);
      font-size: 13px;
    }
    .search button { display: grid; place-items: center; color: var(--muted); }
    .search:focus-within {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(22, 115, 230, 0.12);
    }
    .nav-spacer { flex: 1; }
    .icon {
      width: 22px;
      height: 22px;
      fill: none;
      stroke: currentColor;
      stroke-width: 2;
      stroke-linecap: round;
      stroke-linejoin: round;
    }

    /* ---------- Sub-Header ---------- */
    .heads {
      height: 34px;
      display: grid;
      flex: 0 0 34px;
      grid-template-columns: 180px minmax(0, 1fr);
      align-items: center;
      background: #f8fafc;
      border-top: 1px solid var(--line);
      border-bottom: 1px solid var(--line);
      font-size: 11px;
      font-weight: 600;
      color: var(--muted);
      letter-spacing: 0.5px;
      text-transform: uppercase;
    }
    .heads span:first-child { text-align: center; }
    .heads span:last-child { padding-left: 28px; }

    /* ---------- Dropdown / Two Columns ---------- */
    .dropdown {
      min-height: 0;
      display: grid;
      flex: 1;
      grid-template-columns: 180px minmax(0, 1fr);
    }

    /* ---------- Sidebar ---------- */
    .sidebar {
      min-height: 0;
      margin: 0;
      padding: 12px 10px;
      overflow-y: auto;
      background: #fafbfc;
      border-right: 1px solid var(--line);
      list-style: none;
      scrollbar-color: #cbd5e1 #f8fafc;
      scrollbar-width: thin;
    }
    .sidebar li { margin: 0 0 4px; }
    .sidebar button {
      display: flex;
      align-items: center;
      width: 100%;
      padding: 8px 12px;
      border-radius: 9px;
      font-size: 12.5px;
      font-weight: 500;
      color: #475569;
      text-align: left;
      transition: all 0.15s ease;
    }
    .sidebar button:hover {
      background: #f1f5f9;
      color: var(--text);
    }
    .sidebar button.active {
      background: var(--pill);
      color: var(--pill-text);
      font-weight: 650;
      box-shadow: inset 0 0 0 1px #bfdbfe;
    }

    /* ---------- Panel ---------- */
    .panel {
      min-width: 0;
      min-height: 0;
      padding: 24px 28px 48px;
      overflow-y: auto;
      background: #ffffff;
    }
    .category-panel[hidden] { display: none; }
    .category-title {
      margin: 0 0 18px;
      font-size: 18px;
      font-weight: 700;
      color: var(--text);
    }
    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
      gap: 16px;
    }
    .product-card {
      min-width: 0;
      display: flex;
      flex-direction: column;
      padding: 12px;
      border: 1px solid #f1f5f9;
      border-radius: 12px;
      background: #ffffff;
      transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .product-card:hover {
      border-color: #cbd5e1;
      box-shadow: 0 8px 24px -4px rgba(0, 0, 0, 0.07);
      transform: translateY(-2px);
    }
    .product-image {
      display: flex;
      align-items: center;
      justify-content: center;
      aspect-ratio: 1 / 0.85;
      overflow: hidden;
      border-radius: 8px;
      background: var(--placeholder);
      padding: 6px;
    }
    .product-image img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      transition: transform 0.25s ease;
    }
    .product-card:hover .product-image img {
      transform: scale(1.04);
    }
    .product-name {
      display: -webkit-box;
      min-height: 36px;
      margin: 10px 0 4px;
      overflow: hidden;
      font-size: 13px;
      font-weight: 600;
      line-height: 1.4;
      color: var(--text);
      -webkit-box-orient: vertical;
      -webkit-line-clamp: 2;
    }
    .product-brand {
      color: var(--muted);
      font-size: 11px;
      font-weight: 500;
    }
    .product-price {
      margin: 6px 0 4px;
      font-size: 14.5px;
      font-weight: 750;
      color: var(--text);
    }
    .product-stock {
      color: #475569;
      font-size: 11px;
      display: flex;
      align-items: center;
      gap: 5px;
      margin-top: auto;
    }
    .product-stock-dot {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: #10b981;
      display: inline-block;
    }
    .empty-category {
      padding: 38px 20px;
      border: 1.5px dashed #cbd5e1;
      border-radius: 12px;
      background: var(--placeholder);
      color: var(--muted);
      font-size: 13px;
      text-align: center;
    }

    @media (max-width: 650px) {
      .navbar { flex-wrap: wrap; gap: 12px; padding: 12px 16px; }
      .search { order: 5; max-width: none; flex-basis: 100%; }
      .heads { grid-template-columns: 125px minmax(0, 1fr); }
      .heads span:last-child { padding-left: 14px; }
      .dropdown { grid-template-columns: 125px minmax(0, 1fr); }
      .sidebar button { padding: 8px 8px; font-size: 11px; }
      .panel { padding: 16px 14px 36px; }
      .category-title { font-size: 16px; }
      .product-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
      .product-card { padding: 8px; }
    }
  </style>
</head>
<body>
  <div class="menu">
    @include('body.navbar')

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
                    <div class="product-stock">
                      <span class="product-stock-dot" aria-hidden="true"></span>
                      Stok: {{ $product->stok }}
                    </div>
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
