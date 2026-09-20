<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Struk Penjualan #{{ str_pad($penjualan->id, 6, '0', STR_PAD_LEFT) }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: #f1f3f5;
        }

        .struk-page {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 20px 12px 40px;
        }

        .struk-actions {
            width: 100%;
            max-width: 360px;
            display: flex;
            gap: 8px;
            margin-bottom: 16px;
        }

        .struk-actions a,
        .struk-actions button {
            flex: 1;
        }

        .struk-box {
            width: 100%;
            max-width: 340px; /* mirip lebar kertas thermal 80mm */
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            padding: 20px 16px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 12.5px;
            color: #111;
        }

        .struk-header {
            text-align: center;
        }

        .struk-toko-nama {
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .struk-toko-info {
            font-size: 11px;
            margin-top: 4px;
            line-height: 1.5;
        }

        .struk-divider {
            border-top: 1px solid #000;
            margin: 12px 0;
        }

        .struk-divider-dashed {
            border-top: 1px dashed #999;
            margin: 12px 0;
        }

        .struk-info-row {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 3px;
        }

        .struk-total {
            font-weight: 700;
            font-size: 14px;
            margin: 8px 0;
        }

        .struk-items {
            width: 100%;
            border-collapse: collapse;
        }

        .struk-items th {
            font-size: 11px;
            border-bottom: 1px dashed #999;
            padding-bottom: 4px;
            font-weight: 600;
            text-align: left;
        }

        .struk-items td {
            padding: 2px 0;
            vertical-align: top;
        }

        .struk-sub {
            font-size: 11px;
            color: #444;
            padding-left: 8px !important;
        }

        .struk-empty {
            padding: 12px 0;
            color: #888;
            text-align: center;
        }

        .ta-right { text-align: right; }

        .struk-footer {
            text-align: center;
            font-size: 11px;
            margin-top: 8px;
            line-height: 1.6;
        }

        .struk-barcode {
            text-align: center;
            font-size: 10px;
            letter-spacing: 2px;
            margin-top: 6px;
            color: #555;
        }

        /* ===== Mode cetak ===== */
        @media print {
            body {
                background: #fff;
            }
            .no-print {
                display: none !important;
            }
            .struk-page {
                padding: 0;
            }
            .struk-box {
                box-shadow: none;
                border: none;
                max-width: 80mm;
                padding: 4mm;
            }
            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
    </style>
  </head>

  <body>
    <div class="struk-page">

        <div class="struk-actions no-print">
            <a href="{{ route('penjualan.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Kembali</a>
            <button type="button" class="btn btn-success btn-sm" onclick="window.print()">🖨️ Cetak Struk</button>
        </div>

        <div class="struk-box" id="strukBox">

            {{-- ===== HEADER TOKO ===== --}}
            <div class="struk-header">
                <div class="struk-toko-nama">TOKO HANDPHONE DANZZ</div>
                <div class="struk-toko-info">
                    Jl. Bebedahan 1 Kel. Sukanegara<br>
                    Kec. Purbaratu (Depan Qinimarket)<br>
                    WA: 0812-3456-7890
                </div>
            </div>

            <div class="struk-divider"></div>

            {{-- ===== INFO TRANSAKSI ===== --}}
            <div>
                <div class="struk-info-row">
                    <span>No. Struk</span>
                    <span>#{{ str_pad($penjualan->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="struk-info-row">
                    <span>Tanggal</span>
                    <span>{{ $penjualan->created_at->translatedFormat('d-m-Y H:i') }}</span>
                </div>
                <div class="struk-info-row">
                    <span>Kasir</span>
                    <span>{{ $penjualan->user->name }}</span>
                </div>
            </div>

            <div class="struk-divider-dashed"></div>

            {{-- ===== RINCIAN BARANG ===== --}}
            <table class="struk-items">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th></th>
                        <th class="ta-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualan->itemPenjualan as $item)
                        <tr>
                            <td colspan="3">{{ $item->produk->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="struk-sub">
                                {{ $item->kuantitas }} x Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                            </td>
                            <td></td>
                            <td class="ta-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="struk-empty">Tidak ada item</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="struk-divider-dashed"></div>

            {{-- ===== RINGKASAN PEMBAYARAN ===== --}}
            <div>
                <div class="struk-info-row struk-total">
                    <span>TOTAL</span>
                    <span>Rp {{ number_format($penjualan->total_pembayaran, 0, ',', '.') }}</span>
                </div>
                <div class="struk-info-row">
                    <span>Metode Bayar</span>
                    <span>{{ $penjualan->metode_pembayaran ?? '-' }}</span>
                </div>

                @if($penjualan->metode_pembayaran === 'CASH')
                    <div class="struk-info-row">
                        <span>Uang Dibayar</span>
                        <span>Rp {{ number_format($penjualan->uang_dibayar, 0, ',', '.') }}</span>
                    </div>
                    <div class="struk-info-row">
                        <span>Kembalian</span>
                        <span>Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</span>
                    </div>
                @endif

                <div class="struk-info-row">
                    <span>Status</span>
                    <span>{{ $penjualan->status }}</span>
                </div>
            </div>

            <div class="struk-divider"></div>

            <div class="struk-footer">
                Terima kasih telah berbelanja di<br>
                <strong>Toko Handphone Danzz</strong> 🙏<br>
                Garansi toko 3 bulan (kerusakan pabrik).<br>
                Barang yang sudah dibeli<br>
                tidak dapat dikembalikan.<br>
                <strong>Simpan struk ini sebagai bukti garansi.</strong>
            </div>
        </div>
    </div>
  </body>
</html>