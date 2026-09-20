<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SearchRequest $request)
    {
        $user = Auth::user();
        $keyword = $request->input('search');

        $sales = Penjualan::query()
            ->with(['user', 'itemPenjualan.produk.jenis'])
            ->when($user->role->name === 'kasir', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            // Search nama user
            ->when($keyword, function ($query) use ($keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                })
                // cari nama produk lewat itemPenjualan
                ->orWhereHas('itemPenjualan.produk', function ($qProduk) use ($keyword){
                    $qProduk->where('nama', 'like', '%' . $keyword . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('penjualan.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(SearchRequest $request)
    {
        $sale = Penjualan::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'status'  => 'OPEN'
            ],
            [
                'total_pembayaran'  => 0,
                'metode_pembayaran' => 'CASH'
            ]
        );

        $keyword = $request->input('search');

        if ($keyword) {
            $products = Produk::when($keyword, function ($query) use ($keyword) {
                $query->where('nama', 'like', '%' . $keyword . '%');
            })
            ->orderBy('nama')
            ->get();
        } else {
            $products = Produk::orderBy('nama')->get();
        }

        $mode = 'create';

        return view('penjualan.pos', compact('sale', 'products', 'mode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Penjualan $penjualan)
    {
        $user = Auth::user();

        if ($user->role?->name === 'kasir' && $penjualan->user_id !== $user->id) {
            abort(403, 'Anda tidak berhak melihat transaksi ini.');
        }

        return view('penjualan.show', compact('penjualan'));
    }

    /**
     * Display the printable receipt (struk) for the specified resource.
     */
    public function struk(Penjualan $penjualan)
    {
        $user = Auth::user();

        if ($user->role?->name === 'kasir' && $penjualan->user_id !== $user->id) {
            abort(403, 'Anda tidak berhak melihat struk transaksi ini.');
        }

        $penjualan->load(['user', 'itemPenjualan.produk']);

        return view('penjualan.struk', compact('penjualan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penjualan $penjualan)
    {
        $this->authorize('update', $penjualan);

        $sale = $penjualan;
        $sale->load('itemPenjualan');
        $products = Produk::orderBy('nama')->get();
        $mode = 'edit';

        return view('penjualan.pos', compact('sale', 'products', 'mode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Penjualan $penjualan)
    {
        $request->validate([
            'payment_method' => 'required|in:CASH,QRIS,BAYAR_NANTI',
            'uang_dibayar'   => 'nullable',
            'kembalian'      => 'nullable',
        ]);

        if ($penjualan->status == 'COMPLETED') {
            return back()->with('error', 'Transaksi sudah diproses');
        }

        $this->authorize('update', $penjualan);

        if ($penjualan->itemPenjualan()->count() == 0) {
            return back()->with('error', 'Keranjang masih kosong');
        }

        $total = $penjualan->itemPenjualan()->sum('subtotal');
        
        $uangDibayar = null;
        $kembalian = null;

        // Validasi khusus jika metode pembayaran CASH
        if ($request->payment_method === 'CASH') {
            // Bersihkan format ribuan bertitik dari JavaScript (misal "2.000.000" -> "2000000")
            $rawUang = $request->input('uang_dibayar', 0);

            $uangDibayar = floatval(preg_replace('/[^0-9]/', '', $rawUang));

            if ($uangDibayar < $total) {
                return back()->withErrors(['uang_dibayar' => 'Uang tunai dari pelanggan kurang dari total pembayaran!'])->withInput();
            }

            // Kembalian dihitung sendiri oleh server, jangan percaya nilai kiriman client
            $kembalian = $uangDibayar - $total;
        }

        $newStatus = ($request->payment_method === 'BAYAR_NANTI') ? 'OPEN' : 'COMPLETED';

        DB::transaction(function () use ($penjualan, $request, $total, $newStatus, $uangDibayar, $kembalian) {
            $penjualan->update([
                'metode_pembayaran' => $request->payment_method,
                'total_pembayaran'  => $total,
                'uang_dibayar'      => $uangDibayar,
                'kembalian'         => $kembalian,
                'status'            => $newStatus
            ]);
        });

        // Transaksi selesai (CASH/QRIS) -> arahkan ke struk supaya bisa langsung dicetak
        if ($newStatus === 'COMPLETED') {
            return redirect()
                ->route('penjualan.struk', $penjualan->id)
                ->with('success', 'Transaksi berhasil diselesaikan');
        }

        // Bayar nanti -> tetap kembali ke daftar seperti sebelumnya
        return redirect()
            ->route('penjualan.index')
            ->with('success', 'Transaksi berhasil disimpan (Bayar Nanti)');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penjualan $penjualan)
    {
        $this->authorize('delete', $penjualan);

        if ($penjualan->status !== 'OPEN') {
            return redirect()->route('penjualan.index')->with('errors', 'Transaksi Sudah selesai tidak bisa dibatalkan');
        }

        DB::transaction(function() use ($penjualan) {
            foreach ($penjualan->itemPenjualan as $item) {
                // Kembalikan stoknya
                $item->produk->increment('stok', $item->kuantitas);
            }

            // Hapus itemnya
            $penjualan->itemPenjualan()->delete();

            // Hapus penjualan
            $penjualan->delete();
        });
        
        return redirect()
            ->route('penjualan.index')
            ->with('success', 'Transaksi berhasil dibatalkan');
    }
}