<?php

namespace App\Http\Controllers\Api;

//import model Produk
use App\Models\Produk;

use App\Http\Controllers\Controller;
//import resource ProdukResource
use App\Http\Resources\ProdukResource;

//import resources ProdukResource
use Illuminate\Http\Request;

//import facade Validator
use Illuminate\Support\Facades\Validator;

//import facade Storage
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{    
    public function index()
    {
        //get all posts
        $produks = Produk::latest()->paginate(5);

        //return collection of posts as a resource
        return new ProdukResource(true, 'List Data Produk', $produks);
    }

    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'harga' => 'required|integer',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create post
        $produks = Produk::create([
            'nama'     => $request->nama,
            'kategori'     => $request->kategori,
            'harga'   => $request->harga,
        ]);

        //return response
        return new ProdukResource(true, 'Data Produk Berhasil Ditambahkan!', $produks);
    }

    public function show($id)
    {
        //find post by ID
        $produks = Produk::find($id);

        //return single post as a resource
        return new ProdukResource(true, 'Detail Data Produk!', $produks);
    }

    public function update(Request $request, $id)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'harga' => 'required|integer',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find post by ID
        $produks = Produk::find($id);

            //update post with new image
            $produks->update([
                'nama'     => $request->nama,
                'kategori'     => $request->kategori,
                'harga'   => $request->harga,
            ]);

        //return response
        return new ProdukResource(true, 'Data Produk Berhasil Diubah!', $produks);
    }

    public function destroy($id)
    {

        //find post by ID
        $produks = Produk::find($id);

        //delete image
        Storage::delete('public/posts/'.basename($produks->nama));

        //delete post
        $produks->delete();

        //return response
        return new ProdukResource(true, 'Data Produk Berhasil Dihapus!', null);
    }
}



