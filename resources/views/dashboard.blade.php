<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>AuthenSIX Computer</title>
<style>
  :root {
    --bg: #ffffff;
    --text: #0f172a;
    --muted: #64748b;
    --line: #e2e8f0;
    --placeholder: #f8fafc;
    --accent: #1673e6;
  }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
    color: var(--text);
    background: var(--bg);
    -webkit-font-smoothing: antialiased;
  }
  button { font: inherit; color: inherit; background: none; border: 0; cursor: pointer; }
  a { color: inherit; text-decoration: none; }
  :focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }

  /* ---------- Navbar ---------- */
  .navbar {
    display: flex; align-items: center; gap: 16px;
    padding: 14px 24px;
    border-bottom: 1px solid var(--line);
    background: #ffffff;
  }
  .logo { font-size: 22px; font-weight: 750; letter-spacing: -0.5px; white-space: nowrap; }
  .logo b { color: var(--accent); }
  .logo small { display: block; font-size: 8px; font-weight: 500; letter-spacing: 3px; text-align: center; color: var(--muted); margin-top: -2px; }
  .kategori { font-weight: 600; font-size: 14.5px; display: flex; align-items: center; gap: 6px; color: var(--text); transition: color 0.15s ease; }
  .kategori:hover { color: var(--accent); }
  .search {
    flex: 1; max-width: 320px;
    display: flex; align-items: center; gap: 8px;
    border: 1.5px solid #cbd5e1; border-radius: 8px;
    padding: 0 10px; height: 38px;
    background: #ffffff;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
  }
  .search input { flex: 1; border: 0; outline: 0; font: inherit; min-width: 0; background: transparent; color: var(--text); font-size: 13px; }
  .search button { display: grid; place-items: center; color: var(--muted); }
  .search:focus-within { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(22, 115, 230, 0.12); }
  .nav-spacer { flex: 1; }
  .icon { width: 22px; height: 22px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

  main { padding: 16px 24px 48px; max-width: 1100px; margin: 0 auto; }

  /* ---------- Carousel ---------- */
  .carousel {
    position: relative;
    height: 225px;
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 14px;
    overflow: hidden;
    background: var(--placeholder);
    box-shadow: 0 4px 20px -4px rgba(0, 0, 0, 0.06);
  }
  .carousel > img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
  }
  .slide { position: absolute; inset: 0; display: none; align-items: center; justify-content: center; color: var(--muted); }
  .slide.active { display: flex; }
  .arrow {
    position: absolute; top: 50%; transform: translateY(-50%);
    width: 38px; height: 38px; border-radius: 50%;
    background: rgba(255, 255, 255, 0.88);
    backdrop-filter: blur(6px);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.6);
    color: #1e293b;
    display: grid; place-items: center;
    transition: all 0.2s ease;
  }
  .arrow:hover {
    background: #ffffff;
    transform: translateY(-50%) scale(1.06);
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.18);
  }
  .arrow.prev { left: 12px; }
  .arrow.next { right: 12px; }

  /* ---------- Kategori tab ---------- */
  .tabs {
    display: flex; gap: 20px; align-items: center;
    padding: 18px 4px 14px;
    overflow-x: auto;
    scrollbar-width: none;
  }
  .tabs::-webkit-scrollbar { display: none; }
  .tab {
    color: var(--muted);
    font-size: 14.5px;
    font-weight: 500;
    white-space: nowrap;
    padding: 4px 2px 8px;
    border-bottom: 2.5px solid transparent;
    transition: color 0.15s ease, border-color 0.15s ease;
  }
  .tab:hover {
    color: var(--accent);
    border-color: #cbd5e1;
  }
  .tab.active {
    color: var(--accent);
    font-weight: 650;
    border-color: var(--accent);
  }

  /* ---------- Search Title ---------- */
  .search-title {
    font-size: 18px;
    font-weight: 650;
    color: #1e293b;
    margin: 8px 0 16px;
  }

  /* ---------- Produk ---------- */
  .products {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 16px;
  }
  .card {
    display: flex;
    flex-direction: column;
    padding: 10px;
    border-radius: 12px;
    border: 1px solid #f1f5f9;
    background: #ffffff;
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
  }
  .card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 8px 24px -4px rgba(0, 0, 0, 0.07);
    transform: translateY(-2px);
  }
  .card[hidden] { display: none; }
  .thumb {
    aspect-ratio: 1 / 0.85;
    width: 100%;
    background: #f8fafc;
    border-radius: 8px;
    margin-bottom: 10px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 6px;
  }
  .thumb img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform 0.25s ease;
  }
  .card:hover .thumb img {
    transform: scale(1.04);
  }
  .image-placeholder {
    display: grid;
    width: 100%;
    height: 100%;
    place-items: center;
    color: var(--muted);
    font-size: 11.5px;
  }
  .name {
    font-size: 13px;
    font-weight: 600;
    color: #1e293b;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 36px;
  }
  .category { font-size: 11px; color: var(--muted); margin-top: 4px; font-weight: 500; }
  .price { font-size: 14.5px; font-weight: 750; color: #0f172a; margin: 4px 0 2px; }
  .meta {
    display: flex;
    align-items: center;
    font-size: 11px;
    color: #475569;
    font-weight: 500;
    margin-top: 4px;
  }
  .meta .star { color: #f59e0b; margin-right: 3px; font-size: 12px; }
  .meta .sep { width: 1px; height: 10px; background: #cbd5e1; margin: 0 6px; }
  .review { font-size: 10.5px; color: #94a3b8; margin-top: 2px; }

  .empty-products {
    grid-column: 1 / -1;
    padding: 36px 20px;
    border: 1.5px dashed #cbd5e1;
    border-radius: 12px;
    background: #f8fafc;
    color: var(--muted);
    font-size: 13px;
    text-align: center;
  }

  .pagination {
    margin-top: 24px;
    display: flex;
    justify-content: center;
  }

  @media (max-width: 600px) {
    .navbar { flex-wrap: wrap; padding: 12px 16px; }
    .search { order: 5; max-width: none; flex-basis: 100%; }
    main { padding: 12px 16px 36px; }
    .carousel { height: 160px; }
    .products { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .card { padding: 8px; }
  }
</style>
</head>
<body>

@include('body.navbar')

<main>
  <!-- Carousel -->
  <section class="carousel" aria-label="Banner promo">
    <img src="{{ asset('images/katalog1.png') }}" alt="Banner promo">
    <div class="slide" role="img" aria-label="Banner 3"></div>
    <button class="arrow prev" aria-label="Sebelumnya">
      <svg class="icon" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    </button>
    <button class="arrow next" aria-label="Berikutnya">
      <svg class="icon" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </button>
  </section>

  <!-- Tab kategori -->
  <nav class="tabs" id="categories" aria-label="Kategori produk">
    <a href="{{ route('dashboard', array_filter(['search' => request('search')])) }}"
       class="tab {{ request()->filled('category') ? '' : 'active' }}">Semua</a>
    @foreach ($categories as $category)
      <a href="{{ route('dashboard', array_filter(['search' => request('search'), 'category' => $category->id_kategori])) }}"
         class="tab {{ (string) request('category') === (string) $category->id_kategori ? 'active' : '' }}">
        {{ $category->nama_kategori }}
      </a>
    @endforeach
  </nav>

  @if (request()->filled('search'))
    <h1 class="search-title">Hasil pencarian: <span>{{ request('search') }}</span></h1>
  @endif

  <section class="products" id="products" aria-label="Daftar produk">
    @forelse ($products as $product)
      <a href="{{ route('produk.detail', $product->id_produk) }}" class="card">
        <div class="thumb">
          @if ($product->gambar_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->gambar_path))
            <img src="{{ $product->gambar_url }}" alt="{{ $product->nama_produk }}">
          @else
            <div class="image-placeholder" role="img" aria-label="Foto belum tersedia">Foto belum tersedia</div>
          @endif
        </div>
        <div class="name">{{ $product->nama_produk }}</div>
        <div class="category">{{ $product->kategori->nama_kategori }}</div>
        <div class="price">Rp. {{ number_format((float) $product->harga, 0, ',', '.') }}</div>
        <div class="meta">
          <span class="star">★</span>{{ number_format((float) $product->rating_rata_rata, 1) }}
          <span class="sep"></span>{{ $product->jumlah_terjual }} Terjual
        </div>
        <div class="review">({{ $product->ulasan_count }} Ulasan)</div>
      </a>
    @empty
      <div class="empty-products">Belum ada produk yang cocok.</div>
    @endforelse
  </section>
  @if ($products->hasPages())
    <div class="pagination">{{ $products->links() }}</div>
  @endif
</main>

<script>
  // Carousel
  const slides = document.querySelectorAll(".slide");
  let idx = 0;
  const show = i => {
    idx = (i + slides.length) % slides.length;
    slides.forEach((s, n) => s.classList.toggle("active", n === idx));
  };
  document.querySelector(".prev").onclick = () => show(idx - 1);
  document.querySelector(".next").onclick = () => show(idx + 1);
</script>
</body>
</html>
