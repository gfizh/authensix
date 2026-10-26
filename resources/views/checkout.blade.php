<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Checkout - AuthenSIX Computer</title>
  <style>
    :root {
      --text: #171717;
      --muted: #737373;
      --line: #d9d2d2;
      --field: #f7f6f6;
      --accent: #1599ed;
      --danger: #b42318;
    }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      color: var(--text);
      background: #fff;
      font-family: Inter, system-ui, -apple-system, "Segoe UI", sans-serif;
    }
    a { color: inherit; text-decoration: none; }
    button, input, textarea { font: inherit; }
    :focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }
    .navbar {
      min-height: 58px;
      display: flex;
      align-items: center;
      gap: 28px;
      padding: 8px 20px;
      border-bottom: 1px solid #ededed;
    }
    .logo { font-size: 22px; font-weight: 750; letter-spacing: -1px; white-space: nowrap; }
    .logo b { color: #1673e6; }
    .logo small { display: block; margin-top: -4px; font-size: 7px; letter-spacing: 4px; text-align: center; }
    .nav-label { font-size: 14px; }
    .search { width: min(38vw, 315px); height: 30px; border: 1px solid #555; border-radius: 7px; }
    .search input { width: 100%; height: 100%; padding: 0 9px; border: 0; background: transparent; }
    .nav-spacer { flex: 1; }
    .cart-icon { width: 25px; height: 25px; }
    main { max-width: 1180px; margin: 0 auto; padding: 40px 28px 60px; }
    h1 { margin: 0 0 14px; color: #5a5a5a; font-size: 20px; font-weight: 500; }
    .checkout-layout { display: grid; grid-template-columns: minmax(0, 1fr) 340px; align-items: start; gap: 22px; }
    .form-column { display: grid; gap: 14px; }
    .form-card, .summary-card { border: 1px solid var(--line); border-radius: 12px; }
    .form-card { padding: 14px; }
    .form-card h2 { margin: 0 0 8px; font-size: 15px; font-weight: 600; }
    .fields { display: grid; gap: 10px; }
    .field { display: grid; gap: 4px; min-width: 0; }
    .field label { color: #626262; font-size: 11px; }
    .field input, .field textarea {
      width: 100%;
      min-height: 39px;
      padding: 9px 11px;
      border: 1px solid #d5cece;
      border-radius: 8px;
      background: var(--field);
      color: var(--text);
      font-size: 13px;
    }
    .field textarea { min-height: 72px; resize: vertical; }
    .field input::placeholder, .field textarea::placeholder { color: #929292; }
    .two-columns { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .field-note { margin: 1px 0 0; color: var(--muted); font-size: 10px; line-height: 1.4; }
    .shipping-options { display: grid; gap: 10px; margin-top: 18px; }
    .shipping-option {
      min-height: 49px;
      display: grid;
      grid-template-columns: 18px minmax(0, 1fr) auto;
      align-items: center;
      gap: 8px;
      padding: 7px 10px;
      border: 1px solid #d5cece;
      border-radius: 8px;
      background: var(--field);
      cursor: pointer;
    }
    .shipping-option:has(input:checked) { border-color: var(--accent); background: #f1f9ff; }
    .shipping-option input { margin: 0; accent-color: #222; }
    .shipping-name { font-size: 12px; font-weight: 550; }
    .shipping-estimate { display: block; color: var(--muted); font-size: 10px; }
    .shipping-price { font-size: 12px; font-weight: 600; white-space: nowrap; }
    .summary-card { padding: 14px; }
    .summary-list { display: grid; gap: 12px; max-height: 310px; overflow: auto; }
    .summary-item { display: grid; grid-template-columns: 74px minmax(0, 1fr); gap: 10px; align-items: center; }
    .item-image { display: grid; width: 74px; height: 74px; place-items: center; overflow: hidden; }
    .item-image img { width: 100%; height: 100%; object-fit: contain; }
    .item-info { min-width: 0; }
    .item-name { margin: 0 0 5px; font-size: 12px; line-height: 1.35; }
    .item-quantity { color: var(--muted); font-size: 11px; }
    .item-price { margin-top: 3px; font-size: 12px; font-weight: 650; }
    .summary-message { margin: 8px 0; color: var(--muted); font-size: 12px; }
    .summary-message.error { color: var(--danger); }
    .summary-line { display: flex; justify-content: space-between; gap: 12px; margin: 9px 2px; color: #555; font-size: 12px; }
    .summary-line.total { margin-top: 14px; padding-top: 12px; border-top: 1px solid #bdbdbd; color: var(--text); font-weight: 700; }
    .summary-line.total strong { color: var(--accent); }
    .submit-button {
      width: 100%;
      min-height: 40px;
      margin-top: 12px;
      border: 0;
      border-radius: 8px;
      color: #fff;
      background: var(--accent);
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
    }
    .submit-button:disabled { opacity: .6; cursor: wait; }
    .error-box { margin: 0 0 14px; padding: 10px 12px; border: 1px solid #f2b8b5; border-radius: 8px; color: var(--danger); background: #fff5f5; font-size: 13px; }
    [hidden] { display: none !important; }
    @media (max-width: 760px) {
      main { padding: 26px 16px 40px; }
      .checkout-layout { grid-template-columns: 1fr; }
      .summary-card { grid-row: 1; width: 100%; }
      .summary-list { max-height: 260px; }
    }
    @media (max-width: 480px) {
      .navbar { gap: 14px; padding: 8px 12px; }
      .logo { font-size: 19px; }
      .search { flex: 1; width: auto; min-width: 40px; }
      .two-columns { grid-template-columns: 1fr 1fr; gap: 8px; }
      .form-card { padding: 12px; }
    }
  </style>
</head>
<body>
  <header class="navbar">
    <a href="{{ route('dashboard') }}" class="logo" aria-label="AuthenSIX Computer">
      Authen<b>SIX</b>
      <small>COMPUTER</small>
    </a>
    <span class="nav-label">Checkout ⌄</span>
    <form class="search" action="{{ route('dashboard') }}" role="search">
      <input type="search" name="search" aria-label="Cari produk">
    </form>
    <span class="nav-spacer"></span>
    <a href="{{ route('checkout') }}" aria-label="Checkout">
      <svg class="cart-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.6 12.4a1 1 0 0 0 1 .8h8.8a1 1 0 0 0 1-.8L20 7H6"/>
      </svg>
    </a>
  </header>

  <main>
    <h1>Form Checkout</h1>
    <div class="error-box" id="checkout-error" role="alert" hidden></div>
    <form id="checkout-form" novalidate>
      <div class="checkout-layout">
        <div class="form-column">
          <section class="form-card">
            <h2>Data Penerima</h2>
            <div class="fields">
              <div class="field">
                <label for="recipient-name">Nama Lengkap</label>
                <input id="recipient-name" name="nama_penerima" type="text" maxlength="150" required
                  value="{{ auth()->user()->nama_lengkap }}" placeholder="Contoh: Sulaiman">
              </div>
              <div class="field">
                <label for="recipient-email">Email</label>
                <input id="recipient-email" name="email" type="email" placeholder="nama@email.com" autocomplete="email">
                <p class="field-note">Email belum didukung penyimpanan pada data akun.</p>
              </div>
              <div class="field">
                <label for="recipient-phone">No. Handphone</label>
                <input id="recipient-phone" name="nomor_telepon" type="tel" maxlength="30" required
                  value="{{ auth()->user()->nomor_telepon }}" placeholder="08xxxxxxxxxx">
              </div>
            </div>
          </section>

          <section class="form-card">
            <h2>Alamat Pengiriman</h2>
            <div class="fields">
              <div class="field">
                <label for="address">Alamat Lengkap</label>
                <textarea id="address" name="alamat_lengkap" required placeholder="Nama jalan, nomor rumah, kecamatan, dan patokan"></textarea>
              </div>
              <div class="two-columns">
                <div class="field">
                  <label for="city">Kota</label>
                  <input id="city" name="kota" type="text" maxlength="100" required placeholder="Contoh: Bogor">
                </div>
                <div class="field">
                  <label for="postal-code">Kode Pos</label>
                  <input id="postal-code" name="kode_pos" type="text" maxlength="10" required placeholder="12345">
                </div>
              </div>
              <div class="field">
                <label for="province">Provinsi</label>
                <input id="province" name="provinsi" type="text" maxlength="100" required placeholder="Contoh: Jawa Barat">
              </div>
            </div>
          </section>

          <section class="form-card">
            <h2>Metode Pengiriman</h2>
            <div class="shipping-options">
              <label class="shipping-option">
                <input type="radio" name="shipping" value="25000" data-name="JNE Reguler">
                <span><span class="shipping-name">JNE Reguler</span><small class="shipping-estimate">Estimasi: 3-5 Hari</small></span>
                <span class="shipping-price">Rp. 25.000</span>
              </label>
              <label class="shipping-option">
                <input type="radio" name="shipping" value="55000" data-name="JNE Yes" checked>
                <span><span class="shipping-name">JNE Yes</span><small class="shipping-estimate">Estimasi: 1-2 Hari</small></span>
                <span class="shipping-price">Rp. 55.000</span>
              </label>
              <label class="shipping-option">
                <input type="radio" name="shipping" value="90000" data-name="JNE OKE Express">
                <span><span class="shipping-name">JNE OKE Express</span><small class="shipping-estimate">Estimasi: Same Day</small></span>
                <span class="shipping-price">Rp. 90.000</span>
              </label>
            </div>
          </section>
        </div>

        <aside class="summary-card" aria-label="Ringkasan pesanan">
          <div class="summary-list" id="summary-items" aria-live="polite">
            <p class="summary-message">Memuat keranjang...</p>
          </div>
          <div class="summary-line"><span>Subtotal</span><strong id="subtotal">-</strong></div>
          <div class="summary-line"><span>Ongkos Kirim</span><strong id="shipping-cost">Rp. 55.000</strong></div>
          <div class="summary-line total"><span>Total Bayar</span><strong id="total">-</strong></div>
          <button class="submit-button" id="submit-checkout" type="submit" disabled>Bayar sekarang</button>
        </aside>
      </div>
    </form>
  </main>

  <script>
    const api = {
      cart: @json(url('/api/cart')),
      addresses: @json(url('/api/addresses')),
      products: @json(url('/api/products')),
      cartItems: @json(url('/api/cart/items')),
      checkout: @json(url('/api/checkout')),
      csrf: @json(url('/sanctum/csrf-cookie')),
      success: @json(route('checkout.success'))
    };
    const requestedProductId = @json(request()->query('product_id'));
    const requestedQuantity = Math.max(1, Number(@json(request()->query('quantity', 1))) || 1);
    const form = document.getElementById("checkout-form");
    const errorBox = document.getElementById("checkout-error");
    const submitButton = document.getElementById("submit-checkout");
    const summaryItems = document.getElementById("summary-items");
    const money = new Intl.NumberFormat("id-ID", {
      style: "currency",
      currency: "IDR",
      maximumFractionDigits: 0
    });
    let cartData = null;
    let stagedProduct = null;
    let displayedSubtotal = 0;

    const setError = message => {
      errorBox.textContent = message;
      errorBox.hidden = !message;
    };

    const readJson = async response => {
      let body;
      try {
        body = await response.json();
      } catch {
        throw new Error("Respons server tidak dapat dibaca.");
      }
      if (!response.ok || body.success === false) {
        throw new Error(body.message || "Permintaan checkout gagal.");
      }
      return body.data;
    };

    const request = async (url, options = {}) => {
      const headers = { Accept: "application/json", ...(options.headers || {}) };
      if (options.method && options.method !== "GET") {
        const xsrfCookie = document.cookie
          .split("; ")
          .find(cookie => cookie.startsWith("XSRF-TOKEN="));
        if (!xsrfCookie) throw new Error("Sesi keamanan tidak tersedia. Muat ulang halaman lalu coba lagi.");
        headers["X-XSRF-TOKEN"] = decodeURIComponent(xsrfCookie.slice("XSRF-TOKEN=".length));
        headers["Content-Type"] = "application/json";
      }
      return readJson(await fetch(url, {
        credentials: "same-origin",
        ...options,
        headers
      }));
    };

    const prepareCsrf = async () => {
      const response = await fetch(api.csrf, { credentials: "same-origin" });
      if (!response.ok) throw new Error("Tidak dapat memulai sesi checkout. Muat ulang halaman lalu coba lagi.");
    };

    const displayedItems = () => {
      const items = (cartData?.items || []).map(item => ({
        cartItem: item,
        product: item.produk,
        quantity: Number(item.jumlah) || 0
      }));
      if (!stagedProduct) return items;

      const existing = items.find(item => String(item.product?.id_produk) === String(stagedProduct.id_produk));
      if (existing) {
        existing.quantity = stagedProduct.quantity;
      } else {
        items.push({ product: stagedProduct, quantity: stagedProduct.quantity });
      }
      return items;
    };

    const renderSummary = () => {
      const items = displayedItems();
      summaryItems.replaceChildren();

      if (!items.length) {
        const empty = document.createElement("p");
        empty.className = "summary-message";
        empty.textContent = "Keranjang masih kosong. Tambahkan produk sebelum checkout.";
        summaryItems.append(empty);
        displayedSubtotal = 0;
        submitButton.disabled = true;
      } else {
        displayedSubtotal = items.reduce((sum, item) => {
          const price = Number(item.product?.harga ?? item.cartItem?.harga_satuan) || 0;
          return sum + price * item.quantity;
        }, 0);
        items.forEach(item => {
          const product = item.product || {};
          const price = Number(product.harga ?? item.cartItem?.harga_satuan) || 0;
          const row = document.createElement("article");
          row.className = "summary-item";
          const imageWrap = document.createElement("div");
          imageWrap.className = "item-image";
          const image = document.createElement("img");
          image.src = product.gambar_url || "";
          image.alt = product.nama_produk || "Gambar produk";
          imageWrap.append(image);
          const info = document.createElement("div");
          info.className = "item-info";
          const name = document.createElement("p");
          name.className = "item-name";
          name.textContent = product.nama_produk || "Produk";
          const quantity = document.createElement("span");
          quantity.className = "item-quantity";
          quantity.textContent = `Jumlah: ${item.quantity}`;
          const itemPrice = document.createElement("p");
          itemPrice.className = "item-price";
          itemPrice.textContent = money.format(price * item.quantity);
          info.append(name, quantity, itemPrice);
          row.append(imageWrap, info);
          summaryItems.append(row);
        });
        submitButton.disabled = false;
      }

      document.getElementById("subtotal").textContent = money.format(displayedSubtotal);
      updateTotal();
    };

    const updateTotal = () => {
      const shipping = Number(form.elements.shipping.value) || 0;
      document.getElementById("shipping-cost").textContent = money.format(shipping);
      document.getElementById("total").textContent = money.format(displayedSubtotal + shipping);
    };

    const loadCheckoutData = async () => {
      const [cart, product] = await Promise.all([
        request(api.cart),
        requestedProductId
          ? request(`${api.products}/${encodeURIComponent(requestedProductId)}`)
          : Promise.resolve(null)
      ]);
      cartData = cart;
      if (product) {
        if (String(product.id_produk) !== String(requestedProductId) || !product.is_active) {
          throw new Error("Produk yang dipilih tidak tersedia.");
        }
        stagedProduct = { ...product, quantity: requestedQuantity };
      }
      renderSummary();
    };

    const getOrCreateAddress = async () => {
      const address = {
        label_alamat: "Checkout",
        nama_penerima: form.elements.nama_penerima.value.trim(),
        nomor_telepon: form.elements.nomor_telepon.value.trim(),
        alamat_lengkap: form.elements.alamat_lengkap.value.trim(),
        kota: form.elements.kota.value.trim(),
        provinsi: form.elements.provinsi.value.trim(),
        kode_pos: form.elements.kode_pos.value.trim(),
        is_primary: false
      };
      const addresses = await request(api.addresses);
      const existing = addresses.find(item =>
        item.nama_penerima === address.nama_penerima &&
        item.nomor_telepon === address.nomor_telepon &&
        item.alamat_lengkap === address.alamat_lengkap &&
        item.kota === address.kota &&
        item.provinsi === address.provinsi &&
        item.kode_pos === address.kode_pos
      );
      return existing || request(api.addresses, {
        method: "POST",
        body: JSON.stringify(address)
      });
    };

    const syncStagedProduct = async () => {
      if (!stagedProduct) return;
      const currentItem = (cartData?.items || []).find(item =>
        String(item.produk?.id_produk) === String(stagedProduct.id_produk)
      );
      if (currentItem) {
        await request(`${api.cartItems}/${currentItem.id_item_keranjang}`, {
          method: "PATCH",
          body: JSON.stringify({ jumlah: stagedProduct.quantity })
        });
      } else {
        await request(api.cartItems, {
          method: "POST",
          body: JSON.stringify({
            id_produk: stagedProduct.id_produk,
            jumlah: stagedProduct.quantity
          })
        });
      }
    };

    document.querySelectorAll('input[name="shipping"]').forEach(option =>
      option.addEventListener("change", updateTotal)
    );

    form.addEventListener("submit", async event => {
      event.preventDefault();
      setError("");
      if (!form.reportValidity()) return;
      if (!displayedItems().length) {
        setError("Keranjang masih kosong.");
        return;
      }

      submitButton.disabled = true;
      submitButton.textContent = "Memproses checkout...";
      try {
        await prepareCsrf();
        const address = await getOrCreateAddress();
        await syncStagedProduct();
        const order = await request(api.checkout, {
          method: "POST",
          body: JSON.stringify({
            id_alamat: address.id_alamat,
            biaya_pengiriman: Number(form.elements.shipping.value) || 0,
            pajak: 0
          })
        });
        sessionStorage.setItem("checkoutOrder", JSON.stringify({
          nomor: order.nomor_pesanan,
          total: order.total_pembayaran,
          status: order.status_pemesanan
        }));
        window.location.assign(api.success);
      } catch (error) {
        setError(error.message || "Checkout gagal. Silakan periksa kembali data Anda.");
        submitButton.disabled = displayedItems().length === 0;
        submitButton.textContent = "Bayar sekarang";
      }
    });

    loadCheckoutData().catch(error => {
      summaryItems.replaceChildren();
      const message = document.createElement("p");
      message.className = "summary-message error";
      message.textContent = error.message || "Keranjang gagal dimuat.";
      summaryItems.append(message);
      setError(error.message || "Data checkout gagal dimuat.");
      submitButton.disabled = true;
    });
  </script>
</body>
</html>