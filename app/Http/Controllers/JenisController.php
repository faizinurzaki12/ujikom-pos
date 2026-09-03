<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use Illuminate\Http\Request;

class JenisController extends Controller
{
    public function index()
    {
        // $this->authorize('viewAny', Jenis::class);

        $jenis = Jenis::all();
        return view('jenis.index', compact('jenis'));
    }

    public function create()
    {
        $this->authorize('create', Jenis::class);

        return view('jenis.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Jenis::class);

        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        Jenis::create($request->only('nama'));

        return redirect()->route('jenis.index')->with('success', 'Jenis berhasil ditambahkan');
    }

    public function edit(Jenis $jeni)
    {
        $this->authorize('update', $jeni);

        return view('jenis.edit', ['jenis' => $jeni]);
    }

    public function update(Request $request, Jenis $jeni)
    {
        $this->authorize('update', $jeni);

        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $jeni->update($request->only('nama'));

        return redirect()->route('jenis.index')->with('success', 'Jenis berhasil diperbarui');
    }

    public function destroy(Jenis $jeni)
    {
        $this->authorize('delete', $jeni);

        $jeni->delete();

        return redirect()->route('jenis.index')->with('success', 'Jenis berhasil dihapus');
    }
}