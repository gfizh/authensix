<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>AuthenSIX Computer</title>
<style>
  :root {
    --bg: #ffffff;
    --text: #111111;
    --muted: #666666;
    --line: #e5e5e5;
    --placeholder: #e9e9ec;
    --accent: #1f6fd6;
  }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
    color: var(--text);
    background: var(--bg);
  }
  button { font: inherit; color: inherit; background: none; border: 0; cursor: pointer; }
  a { color: inherit; text-decoration: none; }
  :focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }

  /* ---------- Navbar ---------- */
  .navbar {
    display: flex; align-items: center; gap: 16px;
    padding: 14px 24px;
    border-bottom: 1px solid var(--line);
  }
  .logo { font-size: 22px; font-weight: 700; letter-spacing: -0.5px; white-space: nowrap; }
  .logo b { color: var(--accent); }
  .logo small { display: block; font-size: 8px; font-weight: 500; letter-spacing: 3px; text-align: center; color: var(--muted); }
  .kategori { font-weight: 600; font-size: 15px; display: flex; align-items: center; gap: 6px; }
  .search {
    flex: 1; max-width: 290px;
    display: flex; align-items: center; gap: 8px;
    border: 1.5px solid var(--text); border-radius: 8px;
    padding: 0 10px; height: 38px;
  }
  .search input { flex: 1; border: 0; outline: 0; font: inherit; min-width: 0; background: transparent; }
  .search:focus-within { outline: 2px solid var(--accent); outline-offset: 2px; }
  .nav-spacer { flex: 1; }
  .icon { width: 22px; height: 22px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

  main { padding: 12px 24px 40px; max-width: 1100px; margin: 0 auto; }

  /* ---------- Carousel ---------- */
  .carousel {
    position: relative;
    height: 215px;
    border: 1px solid #333; border-radius: 12px;
    overflow: hidden;
    background: var(--placeholder);
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
    width: 36px; height: 36px; border-radius: 50%;
    display: grid; place-items: center;
  }
  .arrow:hover { background: rgba(255,255,255,.6); }
  .arrow.prev { left: 10px; }
  .arrow.next { right: 10px; }

  /* ---------- Kategori tab ---------- */
  .tabs {
    display: flex; gap: 24px; align-items: center;
    padding: 18px 4px 14px;
    overflow-x: auto;
    scrollbar-width: none;
  }
  .tabs::-webkit-scrollbar { display: none; }
  .tab { color: inherit; font-size: 15px; font-weight: 500; white-space: nowrap; padding: 0 0 4px; border-bottom: 2px solid transparent; }
  .tab:hover { border-color: var(--line); }
  .tab.active {
    color: var(--accent);
    border-color: var(--accent);
  }

  /* ---------- Produk ---------- */
  .products {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 20px;
  }
  .card { display: block; }
  .thumb {
    aspect-ratio: 1 / 0.8;
    width: 100%;
    background: var(--placeholder);
    border-radius: 8px;
    margin-bottom: 10px;
    overflow: hidden;
  }
  .thumb img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: contain;
  }
  .name {
    font-size: 13.5px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
  }
  .category { font-size: 11px; color: var(--muted); margin-top: 4px; }
  .price { font-size: 14px; font-weight: 700; margin: 4px 0; }
  .meta { display: flex; align-items: center; font-size: 11px; color: var(--muted); }
  .meta .star { color: #f5b301; margin-right: 3px; font-size: 13px; }
  .meta .sep { width: 1px; height: 12px; background: #bbb; margin: 0 6px; }
  .review { font-size: 10.5px; color: #888; margin-top: 4px; }

  @media (max-width: 600px) {
    .navbar { flex-wrap: wrap; padding: 12px 16px; }
    .search { order: 5; max-width: none; flex-basis: 100%; }
    main { padding: 12px 16px 32px; }
    .carousel { height: 150px; }
  }
</style>
</head>
<body>

<header class="navbar">
  <a href="#" class="logo" aria-label="AuthenSIX Computer">
    Authen<b>SIX</b>
    <small>COMPUTER</small>
  </a>

  <a href="{{ route('kategori') }}" class="kategori" aria-haspopup="true">
    Kategori
    <svg class="icon" style="width:14px;height:14px" viewBox="0 0 24 24">
        <path d="M6 9l6 6 6-6" fill="currentColor"/>
    </svg>
</a>

  <label class="search">
    <svg class="icon" style="width:18px;height:18px" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
    <input type="search" aria-label="Cari produk">
  </label>

  <span class="nav-spacer"></span>

  <button aria-label="Keranjang">
    <svg class="icon" viewBox="0 0 24 24"><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.6 12.4a1 1 0 0 0 1 .8h8.8a1 1 0 0 0 1-.8L20 7H6"/></svg>
  </button>
</header>

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
  </nav>

  <!-- Daftar produk (diisi lewat JS) -->
  <section class="products" id="products" aria-label="Daftar produk"></section>
</main>

<script>
 const products = [
  {
    nama: "Gigabyte GeForce RTX 5060 Ti Eagle Ice",
    kategori: "Kartu Grafis",
    gambar: "{{ asset('images/2.png') }}",
    harga: 8500000,
    rating: 5.0,
    terjual: 3,
    ulasan: 1
  },
  {
    nama: "Gigabyte GeForce RTX 5060 Ti Eagle OC",
    kategori: "Kartu Grafis",
    gambar: "{{ asset('images/2a.png') }}",
    harga: 9000000,
    rating: 4.9,
    terjual: 5,
    ulasan: 2
  },
  {
    nama: "PC Gaming RGB",
    kategori: "PC Prebuild",
    gambar: "{{ asset('images/3.png') }}",
    harga: 12500000,
    rating: 4.0,
    terjual: 10,
    ulasan: 3
  },
  {
    nama: "Monitor Dell 24 inci FHD IPS 100Hz",
    kategori: "Monitor",
    gambar: "{{ asset('images/4.png') }}",
    harga: 1800000,
    rating: 4.8,
    terjual: 8,
    ulasan: 4
  },
  {
    nama: "Laptop Lenovo LOQ Gaming",
    kategori: "Laptop",
    gambar: "{{ asset('images/5.png') }}",
    harga: 13500000,
    rating: 4.9,
    terjual: 12,
    ulasan: 6
  },
];

  const rupiah = n => "Rp. " + n.toLocaleString("id-ID");
  const productsContainer = document.getElementById("products");
  const categoriesContainer = document.getElementById("categories");
  const categories = @json($categories->pluck('nama_kategori')->values());
  const categoryFilters = ["Semua", ...categories];

  categoriesContainer.innerHTML = categoryFilters.map(category => `
    <button type="button" class="tab${category === "Semua" ? " active" : ""}" data-category="${category}">
      ${category}
    </button>
  `).join("");

  const renderProducts = category => {
    const filteredProducts = category === "Semua"
      ? products
      : products.filter(product => product.kategori === category);

    productsContainer.innerHTML = filteredProducts.length
      ? filteredProducts.map(p => `
      <a href="#" class="card">
        <div class="thumb">
          <img src="${p.gambar}" alt="${p.nama}">
        </div>
        <div class="name">${p.nama}</div>
        <div class="category">${p.kategori}</div>
        <div class="price">${rupiah(p.harga)}</div>
        <div class="meta">
          <span class="star">★</span>${p.rating.toFixed(1)}
          <span class="sep"></span>${p.terjual} Terjual
        </div>
        <div class="review">(${p.ulasan} Ulasan)</div>
      </a>
    `).join("")
      : `<p class="empty-products">Belum ada produk dalam kategori ${category}.</p>`;
  };

  categoriesContainer.addEventListener("click", event => {
    const button = event.target.closest("button[data-category]");

    if (!button) {
      return;
    }

    categoriesContainer.querySelector(".active")?.classList.remove("active");
    button.classList.add("active");
    renderProducts(button.dataset.category);
  });

  renderProducts("Semua");

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