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
use Illuminate\Support\Str;

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
            $file = $request->file('foto');

            // Buat nama unik file
            $filename = 'products/' . Str::random(20) . '.jpg';

            // Baca gambar asli
            $source = imagecreatefromstring(file_get_contents($file->getRealPath()));

            // Ambil ukuran asli
            $width  = imagesx($source);
            $height = imagesy($source);

            // Hitung ukuran baru (max lebar 800px, jaga aspect ratio)
            $maxWidth = 800;
            if ($width > $maxWidth) {
                $newWidth  = $maxWidth;
                $newHeight = intval($height * ($maxWidth / $width));
            } else {
                $newWidth  = $width;
                $newHeight = $height;
            }

            // Buat canvas baru
            $resized = imagecreatetruecolor($newWidth, $newHeight);

            // Resize dengan kualitas bagus
            imagecopyresampled(
                $resized, $source,
                0, 0, 0, 0,
                $newWidth, $newHeight,
                $width, $height
            );

            // Compress ke JPG (quality 60 = keseimbangan bagus & ukuran kecil)
            ob_start();
            imagejpeg($resized, null, 60);
            $compressedContent = ob_get_clean();

            // Bersihkan memory
            imagedestroy($source);
            imagedestroy($resized);

            // Simpan ke storage
            Storage::disk('public')->put($filename, $compressedContent);

            $data['foto'] = $filename;

            // Flash ukuran untuk ditampilkan di view
            session()->flash('foto_original_size', $file->getSize());
            session()->flash('foto_compressed_size', strlen($compressedContent));
        }
        $produk = Produk::create($data);

        return redirect()->route('produk.edit', $produk->id)->with('success', 'Product created successfully.');

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
        $this->authorize('update', $produk);

        $dataReq = $request->validated();

        $data = [
            'user_id'    => Auth::id(),
            'jenis_id'   => $dataReq['nama_jenis'],
            'nama'       => $dataReq['name'],
            'harga_beli' => $dataReq['purchase_price'],
            'harga_jual' => $dataReq['selling_price'],
            'stok'       => $dataReq['stock'] ?? 0,
        ];

        if ($request->hasFile('foto')) {

            // 1. Hapus foto lama jika ada
            if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
                Storage::disk('public')->delete($produk->foto);
            }

            // 2. Proses foto baru
            $file = $request->file('foto');
            $filename = 'products/' . Str::random(20) . '.jpg';

            $source = imagecreatefromstring(file_get_contents($file->getRealPath()));

            $width  = imagesx($source);
            $height = imagesy($source);

            $maxWidth = 800;
            if ($width > $maxWidth) {
                $newWidth  = $maxWidth;
                $newHeight = intval($height * ($maxWidth / $width));
            } else {
                $newWidth  = $width;
                $newHeight = $height;
            }

            $resized = imagecreatetruecolor($newWidth, $newHeight);

            imagecopyresampled(
                $resized, $source,
                0, 0, 0, 0,
                $newWidth, $newHeight,
                $width, $height
            );

            ob_start();
            imagejpeg($resized, null, 60);
            $compressedContent = ob_get_clean();

            imagedestroy($source);
            imagedestroy($resized);

            Storage::disk('public')->put($filename, $compressedContent);

            $data['foto'] = $filename;

            session()->flash('foto_original_size', $file->getSize());
            session()->flash('foto_compressed_size', strlen($compressedContent));
        }
        $produk->update($data);

        return redirect()->route('produk.edit', $produk->id)
            ->with('success', 'Produk berhasil diperbarui.');
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