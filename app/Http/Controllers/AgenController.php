<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agen;

class AgenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agens = Agen::all();
        return view('agen.index', compact('agens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('agen.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        Agen::create($request->all());

        return redirect()->route('agen.index')
            ->with('success', 'Agen berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agen $agen)
    {
        return view('agen.edit', compact('agen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Agen $agen)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $agen->update($request->all());

        return redirect()->route('agen.index')
            ->with('success', 'Agen berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agen $agen)
    {
        $agen->delete();
        return redirect()->route('agen.index')
            ->with('success', 'Agen berhasil dihapus!');
    }
}