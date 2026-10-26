<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Checkout Berhasil - AuthenSIX Computer</title>
  <style>
    * { box-sizing: border-box; }
    body {
      min-height: 100vh;
      display: grid;
      place-items: center;
      margin: 0;
      padding: 20px;
      color: #171717;
      background: #f8fafc;
      font-family: Inter, system-ui, -apple-system, "Segoe UI", sans-serif;
    }
    .success-card {
      width: min(100%, 480px);
      padding: 36px 28px;
      border: 1px solid #e3e7eb;
      border-radius: 16px;
      background: #fff;
      text-align: center;
      box-shadow: 0 12px 36px rgb(20 40 60 / 7%);
    }
    .checkmark {
      width: 64px;
      height: 64px;
      display: grid;
      place-items: center;
      margin: 0 auto 18px;
      border-radius: 50%;
      color: #168c5a;
      background: #e5f7ef;
      font-size: 34px;
      font-weight: 700;
    }
    h1 { margin: 0 0 9px; font-size: 24px; }
    p { color: #5f6670; font-size: 14px; line-height: 1.55; }
    .order-number { margin: 18px 0 4px; color: #171717; font-weight: 700; }
    .order-total { color: #1673e6; font-size: 20px; font-weight: 750; }
    .status { margin: 12px 0 22px; }
    .button {
      display: inline-flex;
      min-height: 42px;
      align-items: center;
      justify-content: center;
      padding: 0 20px;
      border-radius: 8px;
      color: #fff;
      background: #1599ed;
      font-size: 14px;
      font-weight: 650;
      text-decoration: none;
    }
    [hidden] { display: none !important; }
  </style>
</head>
<body>
  <main class="success-card">
    <div class="checkmark" aria-hidden="true">✓</div>
    <h1>Checkout Berhasil</h1>
    <p>Pesanan Anda sudah tercatat. Status pesanan saat ini menunggu proses selanjutnya.</p>
    <p class="order-number" id="order-number" hidden></p>
    <p class="order-total" id="order-total" hidden></p>
    <p class="status">Informasi pesanan tersimpan di akun Anda.</p>
    <a class="button" href="{{ route('dashboard') }}">Kembali ke katalog</a>
  </main>
  <script>
    try {
      const order = JSON.parse(sessionStorage.getItem("checkoutOrder") || "null");
      if (order) {
        const number = document.getElementById("order-number");
        const total = document.getElementById("order-total");
        number.textContent = `Nomor pesanan: ${order.nomor || "-"}`;
        total.textContent = new Intl.NumberFormat("id-ID", {
          style: "currency",
          currency: "IDR",
          maximumFractionDigits: 0
        }).format(Number(order.total) || 0);
        number.hidden = false;
        total.hidden = false;
        sessionStorage.removeItem("checkoutOrder");
      }
    } catch {
      sessionStorage.removeItem("checkoutOrder");
    }
  </script>
</body>
</html>