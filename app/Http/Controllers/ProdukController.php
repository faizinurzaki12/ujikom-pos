<?php

namespace App\Http\Controllers;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\Produk\StoreRequest;
use App\Http\Requests\Produk\UpdateRequest;
use App\Models\Produk;
use App\Models\Jenis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SearchRequest $request)
    {
        $this->authorize('viewAny', Produk::class);
        $keyword = $request->input('search');
        if($keyword) {
            $products = Produk::with('jenis')
                ->when($keyword, function ($query) use ($keyword){
                $query->where('nama', 'like', '%' . $keyword . '%');
            }) 
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();
        } else {
            $products = Produk::latest()->paginate(10)->withQueryString();
        }
        
        return view('produk.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('viewAny', Produk::class);
        $jenisList = \App\Models\Jenis::all();
        return view('produk.create', compact('jenisList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('viewAny', Produk::class);
        $dataReq = $request->validated();

        $data['user_id'] = Auth::id();
        $data['nama'] = $dataReq['name'];
        $data['jenis_id'] = $dataReq['jenis_id'];
        $data['harga_beli'] = $dataReq['purchase_price'];
        $data['harga_jual'] = $dataReq['selling_price'];
        $data['stok'] = $dataReq['stock'] ?? true;

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('products', 'public');
        }

        Produk::create($data);

        return redirect()->route('produk.index')->with('success', 'Product created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        $this->authorize('view', $produk);
        $jenisList = \App\Models\Jenis::all();
        return view('produk.show', compact('produk', 'jenisList'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $produk)
    {
        $this->authorize('viewAny', Produk::class);
        $jenisList = \App\Models\Jenis::all();
        return view('produk.edit', compact('produk', 'jenisList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Produk $produk)
    {
        $this->authorize('viewAny', Produk::class);
        $dataReq = $request->validated();

        $data = [
        'user_id'    => Auth::id(),
        'nama'       => $dataReq['name'],
        'jenis_id'   => $dataReq['jenis_id'],
        'harga_beli' => $dataReq['purchase_price'],
        'harga_jual' => $dataReq['selling_price'],
        'stok'       => $dataReq['stock'],
        ];
        // jika upload foto baru
        if($request->hasFile('foto')) {
            if(
                $produk->foto && 
                Storage::disk('public')->exists($produk->foto)
            ) {
                Storage::disk('public')->delete($produk->foto);
            }
            // simpan foto baru buat di kenang
            $data['foto'] = $request->file('foto')->store('products', 'public');
        }

        $produk->update($data);

        return redirect()->route('produk.edit', $produk->id)->with('success', 'Products updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        $this->authorize('viewAny', Produk::class);
        if($produk->foto) {
            Storage::disk('public')->delete($produk->foto);
        }
        $produk->delete();
        return redirect()->route('produk.index')->with('success', 'Product deleted successfully.');
    }
}
