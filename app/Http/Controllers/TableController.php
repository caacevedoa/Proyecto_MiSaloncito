<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Table;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tables = Table::all();
        return view('tables_crud.ver_crear_mesas', compact('tables'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
     $table = new Table;
        $table->table_number = $request->table_number;
        $table->table_status = $request->table_status;
        $table->save();
        return redirect()->route('tables.index')->with('success', 'Mesa creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $table = Table::findOrFail($id);
        return view('tables_crud.editar_mesa', compact('table'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $table = Table::findOrFail($id);
        $table->table_number = $request->table_number;
        $table->table_status = $request->table_status;
        $table->save();
        return redirect()->route('tables.index')->with('success', 'Mesa actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $table = Table::findOrFail($id);
        $table->delete();
        return redirect()->route('tables.index')->with('success', 'Mesa eliminada exitosamente.');
    }
}
