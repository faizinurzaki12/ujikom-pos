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

        // 🔍 Search nama user
        ->when($keyword, function ($query) use ($keyword) {
            $query->whereHas('user', function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%');
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

        if($keyword) {
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
     * Show the form for editing the specified resource.
     */
    public function edit(Penjualan $penjualan)
    {
        $sale = $penjualan;
        abort_if($sale->status === 'COMPLETED', 403);

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
            'payment_method' => 'required|in:CASH,QRIS'
        ]);

        if ($penjualan->status !== 'OPEN') {
            return back()->with('errors', 'Transaksi sudah diproses');
        }

        if ($penjualan->itemPenjualan()->count() === 0) {
            return back()->with('errors', 'Keranjang masih kosong');
        }

        DB::transaction(function() use ($penjualan, $request) {
            // hitung ulang totall (anti manipulasi)
            $total = $penjualan->itemPenjualan()->sum('subtotal');
            $penjualan->update([
                'metode_pembayaran' => $request->payment_method,
                'total_pembayaran' => $total,
                'status' => 'COMPLETED'
            ]);
        });

        return redirect()
            ->route('penjualan.index')
            ->with('success', 'Transaksi Berhasil Diselesaikan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penjualan $penjualan)
    {
        $this->authorize('delete', $penjualan);

        // ! pastikan hanya transaksi OPEN
        if($penjualan->status !== 'OPEN') {
            return redirect()->route('penjualan.index')->with('errors', 'Transaksi Sudah selesai tidak bisa dibatalkan');
        }

        DB::transaction(function() use ($penjualan) {
            foreach ($penjualan->itemPenjualan as $item) {
                // kembalikan stok nya 
                $item->produk->increment('stok', $item->kuantitas);
            }

            // hapus item nya
            $penjualan->itemPenjualan()->delete();

            // hapus penjualan
            $penjualan->delete();
        });
        
        return redirect()
            ->route('penjualan.index')
            ->with('success', 'Transaksi berhasil dibatalkan');
    }
}
