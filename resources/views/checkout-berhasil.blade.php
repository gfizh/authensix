<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Transaksi Berhasil - AuthenSIX Computer</title>
  <style>
    :root {
      --primary-blue: #0080ff;
      --primary-hover: #006ee0;
      --card-bg: #f2f3f5;
      --text-main: #111827;
      --text-muted: #4b5563;
      --line-color: #d8dade;
      --success-green: #16a34a;
      --border-blue: #93c5fd;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      color: var(--text-main);
      background: #eef1f5;
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
    }

    a { color: inherit; text-decoration: none; }

    /* ---------- Navbar ---------- */
    .navbar {
      display: flex;
      align-items: center;
      gap: 16px;
      padding: 14px 24px;
      border-bottom: 1px solid #e3e7eb;
      background: #ffffff;
    }
    .logo {
      font-size: 22px;
      font-weight: 750;
      letter-spacing: -1px;
      white-space: nowrap;
    }
    .logo b { color: #1673e6; }
    .logo small {
      display: block;
      margin-top: -4px;
      font-size: 7px;
      letter-spacing: 4px;
      text-align: center;
      color: #737373;
    }
    .kategori {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 14px;
      font-weight: 600;
    }
    .search {
      flex: 1;
      max-width: 315px;
      display: flex;
      align-items: center;
      gap: 8px;
      height: 38px;
      padding: 0 10px;
      border: 1px solid #555;
      border-radius: 7px;
    }
    .search button {
      display: grid;
      place-items: center;
      padding: 0;
      border: 0;
      background: transparent;
      cursor: pointer;
    }
    .search input {
      flex: 1;
      width: 100%;
      min-width: 0;
      height: 100%;
      padding: 0;
      border: 0;
      outline: 0;
      background: transparent;
      font: inherit;
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

    /* ---------- Main Layout ---------- */
    .page-wrapper {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 32px 16px 48px;
    }

    .success-card {
      width: 100%;
      max-width: 390px;
      border-radius: 26px;
      background: var(--card-bg);
      box-shadow: 0 12px 32px rgba(0, 0, 0, 0.08), 0 2px 6px rgba(0, 0, 0, 0.04);
      overflow: hidden;
      border: 1px solid rgba(0, 0, 0, 0.04);
    }

    /* Top Blue Banner */
    .card-banner {
      position: relative;
      height: 135px;
      background: var(--primary-blue);
      display: flex;
      justify-content: center;
      align-items: flex-end;
    }

    /* Floating Circle Badge */
    .status-badge {
      position: absolute;
      bottom: -52px;
      left: 50%;
      transform: translateX(-50%);
      width: 110px;
      height: 110px;
      border-radius: 50%;
      background: #ffffff;
      box-shadow: 0 6px 18px rgba(0, 128, 255, 0.16), 0 2px 6px rgba(0, 0, 0, 0.06);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      z-index: 2;
    }

    .badge-icon {
      width: 34px;
      height: 34px;
      color: var(--primary-blue);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 2px;
    }

    .badge-icon svg {
      width: 100%;
      height: 100%;
    }

    .badge-text {
      font-size: 11.5px;
      font-weight: 700;
      color: var(--primary-blue);
      text-align: center;
      line-height: 1.25;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    /* Card Content */
    .card-content {
      padding: 70px 22px 24px;
      display: flex;
      flex-direction: column;
    }

    /* Product Item */
    .product-row {
      display: flex;
      gap: 14px;
      align-items: center;
      margin-bottom: 14px;
    }

    .product-thumb {
      width: 76px;
      height: 76px;
      min-width: 76px;
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      padding: 6px;
    }

    .product-thumb img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }

    .product-thumb .thumb-fallback {
      width: 100%;
      height: 100%;
      display: grid;
      place-items: center;
      color: #9ca3af;
    }

    .product-info {
      display: flex;
      flex-direction: column;
      gap: 3px;
      min-width: 0;
    }

    .product-title {
      font-size: 11px;
      font-weight: 800;
      color: var(--text-main);
      text-transform: uppercase;
      line-height: 1.35;
      margin: 0;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .product-more-items {
      display: inline-block;
      font-size: 10px;
      font-weight: 600;
      color: #6b7280;
      margin-top: 1px;
    }

    .product-price {
      font-size: 14px;
      font-weight: 750;
      color: var(--primary-blue);
      margin: 2px 0 0;
    }

    /* Divider */
    .card-divider {
      height: 1px;
      background: var(--line-color);
      margin: 12px 0 15px;
      border: 0;
    }

    /* Details Rows */
    .details-table {
      display: flex;
      flex-direction: column;
      gap: 9px;
      margin-bottom: 18px;
    }

    .detail-entry {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 12px;
    }

    .detail-label {
      color: var(--text-muted);
      font-weight: 500;
    }

    .detail-value {
      color: var(--text-main);
      font-weight: 500;
      text-align: right;
    }

    .detail-value.status-success {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      color: var(--success-green);
      font-weight: 650;
    }

    .detail-value.status-success svg {
      width: 14px;
      height: 14px;
      stroke-width: 2.5;
    }

    /* Delivery Estimate Box */
    .delivery-box {
      background: #ffffff;
      border: 1.5px solid var(--border-blue);
      border-radius: 14px;
      padding: 12px 14px;
      text-align: center;
      margin-bottom: 12px;
    }

    .delivery-header {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      font-size: 12px;
      font-weight: 600;
      color: var(--text-main);
    }

    .delivery-icon {
      font-size: 16px;
      line-height: 1;
    }

    .delivery-sub {
      font-size: 11px;
      color: var(--text-muted);
      margin-top: 3px;
    }

    /* Thank You Pill */
    .thankyou-pill {
      background: #ffffff;
      border-radius: 9999px;
      padding: 9px 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      font-size: 11.5px;
      font-weight: 600;
      color: var(--text-main);
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
      margin-bottom: 20px;
    }

    .thankyou-icon {
      font-size: 14px;
      line-height: 1;
    }

    .thankyou-text b {
      color: var(--primary-blue);
      font-weight: 750;
    }

    /* Action Button */
    .btn-return {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      width: 100%;
      height: 46px;
      background: var(--primary-blue);
      color: #ffffff;
      border-radius: 12px;
      font-size: 13.5px;
      font-weight: 650;
      text-decoration: none;
      box-shadow: 0 4px 14px rgba(0, 128, 255, 0.28);
      transition: background 150ms ease, transform 100ms ease;
    }

    .btn-return:hover {
      background: var(--primary-hover);
    }

    .btn-return:active {
      transform: scale(0.99);
    }

    @media (max-width: 600px) {
      .navbar { flex-wrap: wrap; gap: 12px; padding: 12px 16px; }
      .search { order: 5; max-width: none; flex-basis: 100%; }
      .page-wrapper { padding: 16px 12px 36px; }
      .success-card { max-width: 100%; }
    }
  </style>
</head>
<body>
  @include('body.navbar')

  @php
    $firstItem = $order?->items?->first();
    $product = $firstItem?->produk;

    // Nama produk (default fallback jika belum ada data pesanan di database)
    $productName = $product?->nama_produk
      ?? 'GIGABYTE VGA NVIDIA GEFORCE RTX 5060 TI EAGLE ICE 16GB GDDR7 OC WHITE - 16GB GDDR7 OC';

    // Gambar produk
    $imageUrl = $product?->gambar_url;
    if ($imageUrl && !str_contains($imageUrl, 'example.com')) {
      $productImage = str_starts_with($imageUrl, 'http') ? $imageUrl : asset($imageUrl);
    } else {
      $productImage = asset('storage/products/rtx4060.jpg');
    }

    // Jumlah item lainnya bila pembelian lebih dari 1 macam
    $totalItemsCount = $order?->items?->count() ?? 1;
    $extraItemsCount = max(0, $totalItemsCount - 1);

    // Total bayar
    $totalPrice = $order?->total_pembayaran ? (float)$order->total_pembayaran : 34430000;
    $formattedPrice = 'Rp' . number_format($totalPrice, 0, ',', '.');

    // No. Invoice
    $invoiceNumber = $order?->invoice?->nomor_invoice
      ?? ($order?->nomor_pesanan ? str_replace('ORD-', 'INV-', $order->nomor_pesanan) : 'INV-2425100');

    // Format Tanggal Bahasa Indonesia
    $indonesianMonths = [
      1 => 'Agustus', // default fallback
      1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
      5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
      9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    $orderDate = $order?->tanggal_pesanan ?? now();
    $monthName = $indonesianMonths[(int)$orderDate->format('m')] ?? $orderDate->format('F');
    $formattedDate = $orderDate->format('d') . ' ' . $monthName . ' ' . $orderDate->format('Y') . ' • ' . $orderDate->format('H:i');

    // Metode Pembayaran
    $rawPayment = $order?->pembayaran?->metode_pembayaran;
    $paymentMethod = match($rawPayment) {
      'bank_transfer' => 'Transfer Bank BRI',
      'e_wallet' => 'Transfer E-Wallet / QRIS',
      null => 'Transfer Bank BRI',
      default => ucwords(str_replace('_', ' ', $rawPayment)),
    };

    // Estimasi Pengiriman
    $courierService = $order?->pengiriman?->layanan;
    $shippingEstimate = match($courierService) {
      'YES' => '1-2 hari',
      'OKE' => '4-6 hari',
      default => '4-5 hari',
    };
  @endphp

  <main class="page-wrapper">
    <div class="success-card">
      <!-- Blue Top Banner -->
      <div class="card-banner">
        <!-- Circular Badge Transaksi Berhasil -->
        <div class="status-badge">
          <div class="badge-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
          </div>
          <div class="badge-text">
            <span>Transaksi</span>
            <span>Berhasil !</span>
          </div>
        </div>
      </div>

      <!-- Card Body -->
      <div class="card-content">
        <!-- Product Item Row -->
        <section class="product-row" aria-label="Produk yang dibeli">
          <div class="product-thumb">
            <img id="product-image" src="{{ $productImage }}" alt="{{ $productName }}" onerror="this.src='{{ asset('storage/products/rtx4060.jpg') }}'">
          </div>
          <div class="product-info">
            <h1 class="product-title" id="product-name">{{ $productName }}</h1>
            @if($extraItemsCount > 0)
              <span class="product-more-items" id="product-extra-count">+{{ $extraItemsCount }} produk lainnya</span>
            @endif
            <p class="product-price" id="product-price">{{ $formattedPrice }}</p>
          </div>
        </section>

        <hr class="card-divider" aria-hidden="true">

        <!-- Detail Information Rows -->
        <section class="details-table" aria-label="Rincian Transaksi">
          <div class="detail-entry">
            <span class="detail-label">No. invoice</span>
            <span class="detail-value" id="order-invoice">{{ $invoiceNumber }}</span>
          </div>
          <div class="detail-entry">
            <span class="detail-label">Tanggal</span>
            <span class="detail-value" id="order-date">{{ $formattedDate }}</span>
          </div>
          <div class="detail-entry">
            <span class="detail-label">Metode Bayar</span>
            <span class="detail-value" id="order-payment">{{ $paymentMethod }}</span>
          </div>
          <div class="detail-entry">
            <span class="detail-label">Status</span>
            <span class="detail-value status-success" id="order-status">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
              <span>Lunas</span>
            </span>
          </div>
        </section>

        <!-- Delivery Box -->
        <section class="delivery-box" aria-label="Estimasi Pengiriman">
          <div class="delivery-header">
            <span class="delivery-icon" aria-hidden="true">📦</span>
            <span class="delivery-title" id="shipping-estimate-text">Estimasi pengiriman: {{ $shippingEstimate }}</span>
          </div>
          <p class="delivery-sub" id="shipping-note">No. resi akan dikirim via email</p>
        </section>

        <!-- Thank You Pill -->
        <div class="thankyou-pill">
          <span class="thankyou-icon" aria-hidden="true">🎉</span>
          <span class="thankyou-text">Terimakasih telah berbelanja di Authen<b>Six</b></span>
        </div>

        <!-- Back to Catalog Button -->
        <a href="{{ route('dashboard') }}" class="btn-return">
          <span aria-hidden="true">&larr;</span> Kembali ke katalog
        </a>
      </div>
    </div>
  </main>

  <script>
    // Client-side hydration dari sessionStorage jika dialihkan via checkout AJAX
    try {
      const stored = sessionStorage.getItem("checkoutOrder");
      if (stored) {
        const order = JSON.parse(stored);
        if (order) {
          // No. Invoice
          if (order.invoice && order.invoice.nomor_invoice) {
            document.getElementById("order-invoice").textContent = order.invoice.nomor_invoice;
          } else if (order.nomor_pesanan) {
            document.getElementById("order-invoice").textContent = order.nomor_pesanan.replace(/^ORD-/, 'INV-');
          } else if (order.nomor) {
            document.getElementById("order-invoice").textContent = order.nomor.replace(/^ORD-/, 'INV-');
          }

          // Total Bayar
          const totalVal = Number(order.total_pembayaran ?? order.total);
          if (!isNaN(totalVal) && totalVal > 0) {
            document.getElementById("product-price").textContent = 'Rp' + new Intl.NumberFormat("id-ID").format(totalVal);
          }

          // Produk
          if (Array.isArray(order.items) && order.items.length > 0) {
            const firstItem = order.items[0];
            const pName = firstItem.produk?.nama_produk || firstItem.nama_produk;
            if (pName) {
              document.getElementById("product-name").textContent = pName;
            }
            const pImg = firstItem.produk?.gambar_url;
            if (pImg && !pImg.includes("example.com")) {
              document.getElementById("product-image").src = pImg;
            }
          }
        }
        sessionStorage.removeItem("checkoutOrder");
      }
    } catch (e) {
      sessionStorage.removeItem("checkoutOrder");
    }
  </script>
</body>
</html>
