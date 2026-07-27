<?php

namespace App\Http\Controllers;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\Produk\StoreRequest;
use App\Http\Requests\Produk\UpdateRequest;
use App\Models\Produk;
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
        $keyword = $request->input('seacrh');
        if($keyword) {
            $products = Produk::when($keyword, function ($query) use ($keyword){
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
        return view('produk.create');
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
        return view('produk.show', compact('produk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $produk)
    {
        $this->authorize('viewAny', Produk::class);
        return view('produk.edit', compact('produk'));
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
