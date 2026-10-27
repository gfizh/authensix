<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Produk - AuthenSIX Computer</title>
  <style>
    :root {
      --text: #171717;
      --muted: #686868;
      --line: #dedede;
      --soft: #f4f4f4;
      --accent: #1673e6;
      --blue: #2fa5ed;
    }

    * { box-sizing: border-box; }
    body {
      margin: 0;
      color: var(--text);
      background: #fff;
      font-family: Inter, system-ui, -apple-system, "Segoe UI", sans-serif;
    }
    button, input { font: inherit; }
    button, a { -webkit-tap-highlight-color: transparent; }
    a { color: inherit; text-decoration: none; }
    button { cursor: pointer; }
    :focus-visible { outline: 2px solid var(--accent); outline-offset: 3px; }

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
  .search button { display: grid; place-items: center; padding: 0; border: 0; background: transparent; }
  .search:focus-within { outline: 2px solid var(--accent); outline-offset: 2px; }
  .nav-spacer { flex: 1; }
  .icon { width: 22px; height: 22px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }


    main { max-width: 1240px; margin: 0 auto; padding: 40px 32px 64px; }
    .back-link { display: inline-block; margin-bottom: 48px; font-size: 13px; font-weight: 600; }
    .product-layout {
      display: grid;
      grid-template-columns: minmax(220px, 1fr) minmax(280px, 1.55fr) minmax(230px, .95fr);
      align-items: start;
      gap: 20px;
    }
    .gallery { min-width: 0; }
    .main-image-wrap {
      display: grid;
      place-items: center;
      width: 100%;
      aspect-ratio: 1 / .92;
      overflow: hidden;
      background: #fff;
    }
    .main-image { width: 100%; height: 100%; object-fit: contain; }
    .thumbnail-list { display: flex; gap: 12px; margin-top: 16px; }
    .thumbnail {
      width: 56px;
      height: 56px;
      padding: 3px;
      border: 1px solid var(--line);
      border-radius: 5px;
      background: #fff;
    }
    .thumbnail.active { border: 2px solid #222; }
    .thumbnail img { width: 100%; height: 100%; object-fit: contain; }

    .product-info { min-width: 0; padding: 2px 0; }
    .product-title { margin: 0; font-size: 19px; line-height: 1.3; text-transform: uppercase; }
    .product-meta { display: flex; flex-wrap: wrap; align-items: center; gap: 6px; margin-top: 8px; color: var(--muted); font-size: 12px; }
    .rating-star { color: #f2ad00; font-size: 16px; }
    .price { margin: 4px 0 24px; font-size: 25px; font-weight: 750; }
    .sku { margin: -17px 0 18px; color: var(--muted); font-size: 12px; }
    .stock-note { color: #bb8500; }
    .tabs { display: flex; gap: 32px; border-bottom: 1px solid var(--line); }
    .tab {
      position: relative;
      padding: 10px 0;
      border: 0;
      background: transparent;
      color: var(--muted);
      font-weight: 650;
    }
    .tab.active { color: var(--text); }
    .tab.active::after {
      position: absolute;
      right: 0;
      bottom: -1px;
      left: 0;
      height: 3px;
      background: #d6d6d6;
      content: "";
    }
    .tab-panel { min-height: 112px; padding: 12px 4px; color: #505050; font-size: 13px; }
    .tab-panel h2 { margin: 0 0 4px; color: #333; font-size: 13px; }
    .description { line-height: 1.55; white-space: pre-line; }
    .spec-list { display: grid; grid-template-columns: minmax(90px, .8fr) 1.2fr; gap: 7px 12px; margin: 0; }
    .spec-list dt { color: var(--muted); }
    .spec-list dd { margin: 0; color: #333; font-weight: 550; }

    .purchase-card {
      border: 1px solid #c9c9c9;
      border-radius: 12px;
      padding: 12px;
    }
    .purchase-title { margin: 0 0 9px; font-size: 14px; }
    .purchase-product { display: flex; align-items: center; gap: 9px; min-height: 43px; font-size: 11px; }
    .purchase-thumb {
      display: grid;
      width: 36px;
      height: 36px;
      flex: 0 0 36px;
      place-items: center;
      overflow: hidden;
      border: 1px solid #aaa;
      border-radius: 5px;
    }
    .purchase-thumb img { width: 100%; height: 100%; object-fit: contain; }
    .quantity-row { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin: 20px 0 17px; font-size: 11px; }
    .quantity-control { display: inline-flex; align-items: center; border: 1px solid #c9c9c9; border-radius: 6px; }
    .quantity-control button { width: 24px; height: 24px; border: 0; background: #fff; color: #555; }
    .quantity-control input { width: 32px; border: 0; text-align: center; outline: 0; appearance: textfield; }
    .quantity-control input::-webkit-inner-spin-button,
    .quantity-control input::-webkit-outer-spin-button { margin: 0; appearance: none; }
    .subtotal { display: flex; justify-content: space-between; gap: 8px; margin-bottom: 18px; font-size: 12px; }
    .subtotal span { color: var(--muted); }
    .purchase-actions { display: grid; gap: 7px; }
    .action-button {
      min-height: 34px;
      border: 1px solid var(--accent);
      border-radius: 6px;
      color: var(--accent);
      background: #fff;
      font-size: 13px;
    }
    .action-button.primary { border-color: #1687d2; color: #fff; background: var(--blue); font-weight: 700; }
    .notice { margin: 10px 0 0; color: var(--muted); font-size: 11px; line-height: 1.4; }
    .state-message { padding: 32px 0; color: var(--muted); text-align: center; }
    .state-message.error { color: #b42318; }
    [hidden] { display: none !important; }

    @media (max-width: 850px) {
      main { padding: 28px 20px 48px; }
      .back-link { margin-bottom: 28px; }
      .product-layout { grid-template-columns: minmax(190px, .9fr) 1.1fr; gap: 20px; }
      .purchase-card { grid-column: 1 / -1; width: min(100%, 420px); justify-self: end; }
    }
    @media (max-width: 560px) {
      .navbar { gap: 16px; padding: 8px 14px; }
      .logo { font-size: 19px; }
      .kategori { font-size: 12px; }
      .search { flex: 1; width: auto; min-width: 35px; }
      main { padding: 24px 16px 40px; }
      .back-link { margin-bottom: 20px; }
      .product-layout { grid-template-columns: 1fr; gap: 22px; }
      .gallery { max-width: 420px; width: 100%; margin: auto; }
      .main-image-wrap { aspect-ratio: 1 / .78; }
      .product-title { font-size: 17px; }
      .price { margin-bottom: 18px; font-size: 23px; }
      .purchase-card { grid-column: auto; width: 100%; }
    }
  </style>
</head>
<body>
  @include('body.navbar')

  <main>
    <a class="back-link" href="{{ route('dashboard') }}">← Kembali ke katalog</a>
    <p class="state-message" id="loading-message" role="status">Memuat detail produk...</p>
    <p class="state-message error" id="error-message" role="alert" hidden></p>

    <div class="product-layout" id="product-content" hidden>
      <section class="gallery" aria-label="Galeri produk">
        <div class="main-image-wrap">
          <img class="main-image" id="main-image" alt="">
        </div>
        <div class="thumbnail-list" id="thumbnail-list"></div>
      </section>

      <section class="product-info">
        <h1 class="product-title" id="product-name"></h1>
        <div class="product-meta">
          <span id="sold-count"></span>
          <span aria-hidden="true">·</span>
          <span class="rating-star" aria-hidden="true">★</span>
          <span id="rating"></span>
          <span id="review-count"></span>
        </div>
        <p class="price" id="product-price"></p>
        <p class="sku" id="product-sku"></p>

        <div class="tabs" role="tablist" aria-label="Informasi produk">
          <button class="tab active" type="button" role="tab" aria-selected="true" data-panel="description">Detail Produk</button>
          <button class="tab" type="button" role="tab" aria-selected="false" data-panel="specifications">Spesifikasi</button>
        </div>
        <div class="tab-panel" id="description-panel" role="tabpanel">
          <h2>Info Produk:</h2>
          <p class="description" id="product-description"></p>
        </div>
        <div class="tab-panel" id="specifications-panel" role="tabpanel" hidden>
          <dl class="spec-list" id="specification-list"></dl>
        </div>
      </section>

      <aside class="purchase-card" aria-label="Atur jumlah dan pembelian">
        <h2 class="purchase-title">Atur jumlah dan catatan</h2>
        <div class="purchase-product">
          <span class="purchase-thumb"><img id="purchase-image" alt=""></span>
          <span id="purchase-name"></span>
        </div>
        <div class="quantity-row">
          <div class="quantity-control" aria-label="Jumlah produk">
            <button type="button" id="decrease-quantity" aria-label="Kurangi jumlah">−</button>
            <input type="number" id="quantity" value="1" min="1" aria-label="Jumlah">
            <button type="button" id="increase-quantity" aria-label="Tambah jumlah">+</button>
          </div>
          <span>Stok: <strong class="stock-note" id="stock-count"></strong></span>
        </div>
        <div class="subtotal"><span>Subtotal</span><strong id="subtotal"></strong></div>
        <div class="purchase-actions">
          <button class="action-button primary" type="button" data-action="cart">+ Keranjang</button>
          <button class="action-button" type="button" data-action="buy">Beli Sekarang</button>
        </div>
        <p class="notice" id="action-notice" role="status" hidden></p>
      </aside>
    </div>
  </main>

  <script>
    const productId = @json($id);
    const endpoint = @json(url('/api/products/'.$id));
    const checkoutUrl = @json(route('checkout'));
    const cartEndpoint = @json(url('/api/cart/items'));
    const csrfEndpoint = @json(url('/sanctum/csrf-cookie'));
    const loadingMessage = document.getElementById("loading-message");
    const errorMessage = document.getElementById("error-message");
    const productContent = document.getElementById("product-content");
    let currentProduct = null;
    const currency = new Intl.NumberFormat("id-ID", {
      style: "currency",
      currency: "IDR",
      maximumFractionDigits: 0
    });

    const setText = (id, value) => {
      document.getElementById(id).textContent = value ?? "";
    };

    const makeElement = (tag, className, text) => {
      const element = document.createElement(tag);
      if (className) element.className = className;
      if (text !== undefined) element.textContent = text;
      return element;
    };

    const showError = message => {
      loadingMessage.hidden = true;
      errorMessage.textContent = message;
      errorMessage.hidden = false;
    };

    const renderSpecifications = specifications => {
      const list = document.getElementById("specification-list");
      list.replaceChildren();

      if (!specifications.length) {
        list.append(makeElement("dt", "", "Informasi"), makeElement("dd", "", "Spesifikasi belum tersedia."));
        return;
      }

      specifications.forEach(specification => {
        list.append(
          makeElement("dt", "", specification.nama_atribut),
          makeElement("dd", "", specification.nilai_atribut)
        );
      });
    };

    const renderProduct = product => {
      const imageUrl = product.gambar_url || "";
      const reviews = Array.isArray(product.ulasan) ? product.ulasan : [];
      const stock = Math.max(0, Number(product.stok) || 0);
      const price = Number(product.harga) || 0;
      const quantityInput = document.getElementById("quantity");
      const mainImage = document.getElementById("main-image");
      const purchaseImage = document.getElementById("purchase-image");
      const thumbnailList = document.getElementById("thumbnail-list");

      setText("product-name", product.nama_produk || "Produk");
      setText("sold-count", `Terjual ${Number(product.jumlah_terjual) || 0}`);
      setText("rating", Number(product.rating_rata_rata || 0).toFixed(1));
      setText("review-count", `(${reviews.length} Ulasan)`);
      setText("product-price", currency.format(price));
      setText("product-sku", `SKU: ${product.sku || "-"}`);
      setText("product-description", product.deskripsi || "Deskripsi produk belum tersedia.");
      setText("purchase-name", product.nama_produk || "Produk");
      setText("stock-count", stock);

      mainImage.src = imageUrl;
      mainImage.alt = product.nama_produk || "Gambar produk";
      purchaseImage.src = imageUrl;
      purchaseImage.alt = "";
      quantityInput.max = stock || 1;

      thumbnailList.replaceChildren();
      if (imageUrl) {
        const thumbnail = makeElement("button", "thumbnail active");
        thumbnail.type = "button";
        thumbnail.setAttribute("aria-label", "Tampilkan gambar produk");
        const thumbnailImage = document.createElement("img");
        thumbnailImage.src = imageUrl;
        thumbnailImage.alt = "";
        thumbnail.append(thumbnailImage);
        thumbnailList.append(thumbnail);
      }

      const detailRows = [
        ["Merek", product.merek?.nama_merek || "-"],
        ["Kategori", product.kategori?.nama_kategori || "-"],
        ["Kondisi", product.kondisi || "-"],
        ["Garansi", product.masa_garansi_bulan ? `${product.masa_garansi_bulan} bulan` : "-"]
      ];
      const description = document.getElementById("description-panel");
      description.querySelectorAll(".product-detail-row").forEach(row => row.remove());
      detailRows.forEach(([label, value]) => {
        const row = makeElement("p", "product-detail-row");
        const strong = makeElement("strong", "", `${label}: `);
        row.append(strong, document.createTextNode(value));
        description.append(row);
      });

      renderSpecifications(Array.isArray(product.spesifikasi) ? product.spesifikasi : []);
      updateSubtotal(price, stock);
      loadingMessage.hidden = true;
      productContent.hidden = false;
    };

    const updateSubtotal = (price, stock) => {
      const quantityInput = document.getElementById("quantity");
      const quantity = Math.max(1, Math.min(stock || 1, Number(quantityInput.value) || 1));
      quantityInput.value = quantity;
      setText("subtotal", currency.format(price * quantity));
    };

    document.getElementById("decrease-quantity").addEventListener("click", () => {
      const input = document.getElementById("quantity");
      input.value = Math.max(1, (Number(input.value) || 1) - 1);
      input.dispatchEvent(new Event("input"));
    });

    document.getElementById("increase-quantity").addEventListener("click", () => {
      const input = document.getElementById("quantity");
      input.value = Math.min(Number(input.max) || 1, (Number(input.value) || 1) + 1);
      input.dispatchEvent(new Event("input"));
    });

    document.getElementById("quantity").addEventListener("input", () => {
      const price = Number(currentProduct?.harga) || 0;
      const stock = Math.max(0, Number(currentProduct?.stok) || 0);
      updateSubtotal(price, stock);
    });

    document.querySelectorAll(".tab[data-panel]").forEach(button => {
      button.addEventListener("click", () => {
        const isDescription = button.dataset.panel === "description";
        document.querySelectorAll(".tab[data-panel]").forEach(tab => {
          const isActive = tab === button;
          tab.classList.toggle("active", isActive);
          tab.setAttribute("aria-selected", String(isActive));
        });
        document.getElementById("description-panel").hidden = !isDescription;
        document.getElementById("specifications-panel").hidden = isDescription;
      });
    });

    document.querySelectorAll(".purchase-actions button").forEach(button => {
      button.addEventListener("click", async () => {
        const notice = document.getElementById("action-notice");
        const quantity = Number(document.getElementById("quantity").value) || 1;

        if (button.dataset.action === "buy") {
          const query = new URLSearchParams({
            product_id: productId,
            quantity: String(quantity)
          });
          window.location.assign(`${checkoutUrl}?${query}`);
          return;
        }

        notice.textContent = "Menambahkan produk ke keranjang...";
        notice.hidden = false;
        button.disabled = true;

        try {
          const csrfResponse = await fetch(csrfEndpoint, { credentials: "same-origin" });
          if (!csrfResponse.ok) throw new Error("Sesi keamanan tidak tersedia.");
          const xsrfCookie = document.cookie
            .split("; ")
            .find(cookie => cookie.startsWith("XSRF-TOKEN="));
          if (!xsrfCookie) throw new Error("Sesi keamanan tidak tersedia.");

          const response = await fetch(cartEndpoint, {
            method: "POST",
            credentials: "same-origin",
            headers: {
              Accept: "application/json",
              "Content-Type": "application/json",
              "X-XSRF-TOKEN": decodeURIComponent(xsrfCookie.slice("XSRF-TOKEN=".length))
            },
            body: JSON.stringify({ id_produk: productId, jumlah: quantity })
          });
          const result = await response.json();
          if (!response.ok || !result.success) {
            throw new Error(result.message || "Produk gagal ditambahkan ke keranjang.");
          }
          notice.textContent = "Produk berhasil ditambahkan ke keranjang.";
        } catch (error) {
          notice.textContent = error.message || "Produk gagal ditambahkan ke keranjang.";
        } finally {
          button.disabled = false;
        }
      });
    });

    fetch(endpoint, { headers: { Accept: "application/json" } })
      .then(response => {
        if (!response.ok) {
          throw new Error(response.status === 404
            ? "Produk tidak ditemukan."
            : "Detail produk gagal dimuat. Silakan coba lagi.");
        }
        return response.json();
      })
      .then(response => {
        if (!response.success || !response.data || String(response.data.id_produk) !== String(productId)) {
          throw new Error(response.message || "Data produk tidak valid.");
        }
        currentProduct = response.data;
        renderProduct(response.data);
      })
      .catch(error => showError(error.message || "Detail produk gagal dimuat."));
  </script>
</body>
</html>
