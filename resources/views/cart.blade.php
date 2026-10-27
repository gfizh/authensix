<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Keranjang - AuthenSIX Computer</title>
<style>
  :root {
    --text: #111111;
    --muted: #666666;
    --line: #e5e5e5;
    --placeholder: #e9e9ec;
    --accent: #1f6fd6;
    --danger: #d93025;
  }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
    color: var(--text);
    background: #fff;
  }
  button, input { font: inherit; color: inherit; }
  button { background: none; border: 0; cursor: pointer; }
  a { color: inherit; text-decoration: none; }
  :focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }
  .icon { width: 22px; height: 22px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

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
    flex: 1; max-width: 330px;
    display: flex; align-items: center; gap: 8px;
    border: 1.5px solid var(--text); border-radius: 8px;
    padding: 0 10px; height: 38px;
  }
  .search input { flex: 1; border: 0; outline: 0; min-width: 0; background: transparent; }
  .search:focus-within { outline: 2px solid var(--accent); outline-offset: 2px; }
  .cart-link { position: relative; }
  .badge {
    position: absolute; top: -6px; right: -8px;
    min-width: 16px; height: 16px; padding: 0 4px;
    border-radius: 999px; background: var(--accent); color: #fff;
    font-size: 10px; font-weight: 600; display: grid; place-items: center;
  }

  /* ---------- Konten ---------- */
  main { max-width: 1100px; margin: 0 auto; padding: 24px; }
  .back { display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; }
  h1 { font-size: 18px; margin: 18px 0 16px; }

  .layout { display: grid; grid-template-columns: 1fr 320px; gap: 24px; align-items: start; }

  /* Daftar item */
  .list { border: 1px solid var(--line); border-radius: 8px; }
  .list-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 16px; border-bottom: 1px solid var(--line);
    font-size: 12px; font-weight: 600;
  }
  .check { display: flex; align-items: center; gap: 10px; cursor: pointer; }
  input[type="checkbox"] { width: 16px; height: 16px; accent-color: var(--accent); cursor: pointer; }
  .link-danger { color: var(--danger); font-size: 12px; font-weight: 600; }
  .link-danger:hover { text-decoration: underline; }

  .item {
    display: grid; grid-template-columns: auto 88px 1fr auto;
    gap: 14px; align-items: center;
    padding: 16px;
    border-bottom: 1px solid var(--line);
  }
  .item:last-child { border-bottom: 0; }
  .thumb { width: 88px; height: 70px; border-radius: 8px; background: var(--placeholder); }
  .info .name { font-size: 13px; font-weight: 600; line-height: 1.4;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
  .info .varian { font-size: 11px; color: var(--muted); margin-top: 3px; }
  .info .harga { font-size: 14px; font-weight: 700; margin-top: 6px; }
  .info .stok { font-size: 10.5px; color: var(--muted); margin-top: 2px; }
  .info .stok.low { color: var(--danger); }

  .actions { display: flex; flex-direction: column; align-items: flex-end; gap: 12px; }
  .qty {
    display: inline-flex; align-items: center;
    border: 1px solid var(--line); border-radius: 6px; overflow: hidden;
  }
  .qty button { width: 28px; height: 28px; font-size: 15px; }
  .qty button:hover:not(:disabled) { background: #f3f3f3; }
  .qty button:disabled { color: #bbb; cursor: not-allowed; }
  .qty input {
    width: 40px; height: 28px; text-align: center;
    border: 0; border-left: 1px solid var(--line); border-right: 1px solid var(--line);
    font-size: 12px; outline: 0; -moz-appearance: textfield;
  }
  .qty input::-webkit-outer-spin-button, .qty input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
  .hapus { font-size: 11px; color: var(--muted); display: inline-flex; align-items: center; gap: 4px; }
  .hapus:hover { color: var(--danger); }

  /* Ringkasan */
  .summary {
    border: 1px solid var(--line); border-radius: 8px;
    padding: 16px;
    position: sticky; top: 16px;
  }
  .summary h2 { font-size: 13px; margin-bottom: 14px; }
  .row { display: flex; justify-content: space-between; font-size: 12px; color: var(--muted); margin-bottom: 10px; }
  .row.total { color: var(--text); font-weight: 700; font-size: 14px; padding-top: 12px; border-top: 1px solid var(--line); margin: 14px 0 16px; }
  .btn {
    display: block; width: 100%; text-align: center;
    padding: 9px 12px; border-radius: 6px;
    font-size: 12px; font-weight: 600;
    border: 1.5px solid var(--accent);
  }
  .btn-primary { background: var(--accent); color: #fff; }
  .btn-primary:hover:not(:disabled) { filter: brightness(.92); }
  .btn-outline { color: var(--accent); background: #fff; margin-top: 8px; }
  .btn-outline:hover { background: #f1f6fd; }
  .btn:disabled { opacity: .45; cursor: not-allowed; }

  /* Kosong */
  .empty { text-align: center; padding: 60px 20px; border: 1px solid var(--line); border-radius: 8px; }
  .empty p { font-size: 13px; color: var(--muted); margin: 6px 0 18px; }
  .empty .btn { display: inline-block; width: auto; padding: 9px 22px; }

  @media (max-width: 800px) {
    .layout { grid-template-columns: 1fr; }
    .summary { position: static; }
  }
  @media (max-width: 560px) {
    .navbar { flex-wrap: wrap; padding: 12px 16px; }
    .search { order: 5; max-width: none; flex-basis: 100%; }
    main { padding: 16px; }
    .item { grid-template-columns: auto 64px 1fr; }
    .thumb { width: 64px; height: 56px; }
    .actions { grid-column: 2 / -1; flex-direction: row; justify-content: space-between; align-items: center; }
  }
</style>
</head>
<body>

{{-- ===== Navbar ===== --}}
@include('body.navbar')

<main>
  <a href="{{ url('/dashboard') }}" class="back">← Kembali ke katalog</a>
  <h1>Keranjang Belanja</h1>

  @if(session('success'))
    <p style="font-size:12px;color:#1e7e34;margin-bottom:12px">{{ session('success') }}</p>
  @endif

  @forelse($items as $item)
    @if($loop->first)
    <div class="layout">
      {{-- ===== Daftar item ===== --}}
      <section class="list" aria-label="Daftar produk di keranjang">
        <div class="list-head">
          <label class="check">
            <input type="checkbox" id="check-all" checked>
            Pilih semua ({{ $items->count() }})
          </label>
          <form action="{{ route('cart.clear') }}" method="POST"
                onsubmit="return confirm('Kosongkan seluruh keranjang?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="link-danger">Hapus semua</button>
          </form>
        </div>
    @endif

        <article class="item" data-harga="{{ $item->harga }}">
          <input type="checkbox" class="check-item" value="{{ $item->id }}" checked aria-label="Pilih {{ $item->nama }}">

          <div class="thumb"></div>

          <div class="info">
            <div class="name">{{ $item->nama }}</div>
            @if($item->varian)
              <div class="varian">Varian: {{ $item->varian }}</div>
            @endif
            <div class="harga">Rp{{ number_format($item->harga, 0, ',', '.') }}</div>
            <div class="stok {{ $item->stok <= 5 ? 'low' : '' }}">Stok: {{ $item->stok <= 5 ? 'Sisa '.$item->stok : $item->stok }}</div>
          </div>

          <div class="actions">
            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="qty">
              @csrf
              @method('PATCH')
              <button type="submit" name="qty" value="{{ $item->qty - 1 }}"
                      {{ $item->qty <= 1 ? 'disabled' : '' }} aria-label="Kurangi">−</button>
              <input type="number" name="qty" value="{{ $item->qty }}" min="1" max="{{ $item->stok }}"
                     class="qty-input" aria-label="Jumlah" onchange="this.form.submit()">
              <button type="submit" name="qty" value="{{ $item->qty + 1 }}"
                      {{ $item->qty >= $item->stok ? 'disabled' : '' }} aria-label="Tambah">+</button>
            </form>

            <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
              @csrf
              @method('DELETE')
              <button type="submit" class="hapus">
                <svg class="icon" style="width:13px;height:13px" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/></svg>
                Hapus
              </button>
            </form>
          </div>

          <input type="hidden" class="qty-val" value="{{ $item->qty }}">
        </article>

    @if($loop->last)
      </section>

      {{-- ===== Ringkasan ===== --}}
      <aside class="summary" aria-label="Ringkasan belanja">
        <h2>Ringkasan belanja</h2>
        <div class="row"><span>Total barang</span><span id="sum-qty">0</span></div>
        <div class="row"><span>Subtotal</span><span id="sum-subtotal">Rp0</span></div>
        <div class="row total"><span>Total</span><span id="sum-total">Rp0</span></div>

        <form action="{{ route('checkout') }}" method="POST" id="form-checkout">
          @csrf
          <div id="selected-inputs"></div>
          <a href="{{ url('/checkout') }}" type="submit" class="btn btn-primary" id="btn-checkout">Checkout</a>
        </form>
        <a href="{{ url('/dashboard') }}" class="btn btn-outline">Lanjut Belanja</a>
      </aside>
    </div>
    @endif
  @empty
    <div class="empty">
      <svg class="icon" style="width:40px;height:40px;color:#bbb" viewBox="0 0 24 24"><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.6 12.4a1 1 0 0 0 1 .8h8.8a1 1 0 0 0 1-.8L20 7H6"/></svg>
      <h2 style="font-size:15px;margin-top:10px">Keranjang kamu masih kosong</h2>
      <p>Yuk, cari komponen PC dan perangkat favoritmu.</p>
      <a href="{{ url('/katalog') }}" class="btn btn-primary">Mulai Belanja</a>
    </div>
  @endforelse
</main>

<script>
  (function () {
    const checkAll = document.getElementById('check-all');
    if (!checkAll) return;

    const rupiah = n => 'Rp' + n.toLocaleString('id-ID');
    const items = [...document.querySelectorAll('.item')];
    const boxes = items.map(i => i.querySelector('.check-item'));

    function hitung() {
      let qty = 0, total = 0;
      const wrap = document.getElementById('selected-inputs');
      wrap.innerHTML = '';

      items.forEach((el, i) => {
        if (!boxes[i].checked) return;
        const q = parseInt(el.querySelector('.qty-val').value, 10);
        qty += q;
        total += q * parseInt(el.dataset.harga, 10);
        wrap.insertAdjacentHTML('beforeend',
          `<input type="hidden" name="items[]" value="${boxes[i].value}">`);
      });

      document.getElementById('sum-qty').textContent = qty;
      document.getElementById('sum-subtotal').textContent = rupiah(total);
      document.getElementById('sum-total').textContent = rupiah(total);
      document.getElementById('btn-checkout').disabled = qty === 0;
      checkAll.checked = boxes.every(b => b.checked);
    }

    checkAll.addEventListener('change', () => {
      boxes.forEach(b => b.checked = checkAll.checked);
      hitung();
    });
    boxes.forEach(b => b.addEventListener('change', hitung));
    hitung();
  })();
</script>
</body>
</html>