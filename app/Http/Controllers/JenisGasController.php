<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisGas;

class JenisGasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jenisGases = JenisGas::with('stok')->get();
        return view('jenis_gas.index', compact('jenisGases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jenis_gas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:jenis_gas',
            'stok_minimum' => 'required|integer|min:0',
        ]);

        JenisGas::create($request->all());

        return redirect()->route('jenis-gas.index')
            ->with('success', 'Jenis gas berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jenisGas = JenisGas::findOrFail($id);
        return view('jenis_gas.edit', compact('jenisGas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $jenisGas = JenisGas::findOrFail($id);
        
        $request->validate([
            'nama' => 'required|string|max:255|unique:jenis_gas,nama,' . $id,
            'stok_minimum' => 'required|integer|min:0',
        ]);

        $jenisGas->update($request->all());

        return redirect()->route('jenis-gas.index')
            ->with('success', 'Jenis gas berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jenisGas = JenisGas::findOrFail($id);
        $jenisGas->delete();
        
        return redirect()->route('jenis-gas.index')
            ->with('success', 'Jenis gas berhasil dihapus!');
    }
}