<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
     $products = Product::all();
        return view('products_crud.ver_crear_productos', compact('products'));
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
     $product = new Product;
        $product->product_name = $request->product_name;
        $product->product_type = $request->product_type;
        $product->unit_price = $request->unit_price;
        $product->product_status = $request->product_status;
        $product->save();
        return redirect()->route('products.index')->with('success', 'Producto creado exitosamente.');
    }
        //

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
        $product = Product::findOrFail($id);
        return view('products_crud.editar_producto', compact('product'));
    }
    /**
     * Update the specified resource in storage.
     */
  public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        $product->product_name = $request->product_name;
        $product->product_type = $request->product_type;
        $product->unit_price = $request->unit_price;
        $product->product_status = $request->product_status;
        $product->save();
        return redirect()->route('products.index')->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Producto eliminado exitosamente.');
    }
}
