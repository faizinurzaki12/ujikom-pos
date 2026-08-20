<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use App\Http\Requests\Jenis\StoreRequest;
use App\Http\Requests\Jenis\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JenisController extends Controller
{
    /**
     * Menampilkan daftar semua jenis, dengan search + pagination
     * (sama seperti pola di halaman Users).
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $jenis = Jenis::with('user')
            ->when($search, function ($query, $search) {
                return $query->where('nama_jenis', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10); // FIX: sebelumnya ->get(), diganti paginate()
                            // supaya $jenis->firstItem() di view bisa jalan

        return view('jenis.index', compact('jenis'));
    }

    public function create()
    {
        $this->authorize('create', Jenis::class);

        return view('jenis.create');
    }

    /**
     * Menyimpan jenis baru ke database. Validasi ditangani StoreRequest.
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('create', Jenis::class);

        Jenis::create([
            'user_id'    => Auth::id(),
            'nama_jenis' => $request->validated()['nama_jenis'],
        ]);

        return redirect()->route('jenis.index')->with('success', 'Jenis berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail 1 jenis beserta daftar produk di dalamnya.
     */
    public function show(Jenis $jenis)
    {
        $this->authorize('view', $jenis);

        $jenis->load(['produk', 'user']);

        return view('jenis.show', compact('jenis'));
    }

    public function edit(Jenis $jenis)
    {
        $this->authorize('update', $jenis);

        return view('jenis.edit', compact('jenis'));
    }

    /**
     * Mengubah data jenis. Validasi ditangani UpdateRequest.
     */
    public function update(UpdateRequest $request, Jenis $jenis)
    {
        $this->authorize('update', $jenis);

        $jenis->update([
            'nama_jenis' => $request->validated()['nama_jenis'],
            // user_id sengaja TIDAK diubah saat update -- user_id
            // seharusnya tetap mencatat siapa yang PERTAMA KALI membuat
            // jenis ini, bukan berubah jadi siapa yang terakhir mengedit.
            // Kalau kamu memang mau field "terakhir diedit oleh",
            // sebaiknya tambah kolom terpisah, misal `updated_by`.
        ]);

        return redirect()->route('jenis.index')->with('success', 'Jenis berhasil diperbarui.');
    }

    /**
     * Menghapus jenis dari database. Ditolak kalau masih dipakai produk.
     */
    public function destroy(Jenis $jenis)
    {
        $this->authorize('delete', $jenis);

        if ($jenis->produk()->count() > 0) {
            return redirect()->route('jenis.index')
                ->with('error', 'Jenis tidak bisa dihapus karena masih digunakan oleh produk!');
        }

        $jenis->delete();

        return redirect()->route('jenis.index')->with('success', 'Jenis berhasil dihapus.');
    }
}