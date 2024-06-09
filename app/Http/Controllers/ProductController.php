<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\File;
use App\Jobs\ProductJob;

class ProductController extends Controller
{
    public function index()
    {
        $product = Product::with(['category'])->orderBy('created_at', 'DESC');
        if (request()->q != '') {
            $product = $product->where('name', 'LIKE', '%' . request()->q . '%');
        }
        $product = $product->paginate(10);
        return view('produk.produk', compact('product'));
    }

    public function create()
    {
        $category = Category::orderBy('name', 'DESC')->get();

        return view('produk.create', compact('category'));
    }

    public function store(Request $request)
    {
        //VALIDASI REQUESTNYA
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id', //CATEGORY_ID KITA CEK HARUS ADA DI TABLE CATEGORIES DENGAN FIELD ID
            'price' => 'required|integer',
            'weight' => 'required|integer',
            'stock' => 'required|integer',
            'image' => 'required|image|mimes:png,jpeg,jpg' //GAMBAR DIVALIDASI HARUS BERTIPE PNG,JPG DAN JPEG
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('products'), $filename);

            $product = Product::create([
                'name' => $request->name,
                'slug' => $request->name,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'image' => $filename, //PASTIKAN MENGGUNAKAN VARIABLE FILENAM YANG HANYA BERISI NAMA FILE SAJA (STRING)
                'price' => $request->price,
                'weight' => $request->weight,
                'stock' => $request->stock,
                'status' => $request->status
            ]);
            //JIKA SUDAH MAKA REDIRECT KE LIST PRODUK
            return redirect(route('product.index'))->with(['success' => 'Produk Baru Ditambahkan']);
        }
    }

    public function destroy($id)
    {
        $product = Product::find($id); //QUERY UNTUK MENGAMBIL DATA PRODUK BERDASARKAN ID
        File::delete(public_path('products/' . $product->image));
        $product->delete();
        return redirect(route('product.index'))->with(['success' => 'Produk Sudah Dihapus']);
    }

    public function massUploadForm()
    {
        $category = Category::orderBy('name', 'DESC')->get();
        return view('produk.bulk', compact('category'));
    }

    public function massUpload(Request $request)
    {
        $this->validate($request, [
            'category_id' => 'required|exists:categories,id',
            'file' => 'required|mimes:xlsx'
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '-product.' . $file->getClientOriginalExtension();
            $file->storeAs('public/uploads', $filename);

            ProductJob::dispatch($request->category_id, $filename);
            return redirect()->back()->with(['success' => 'Upload Produk Dijadwalkan']);
        }
    }

    public function edit($id)
    {
        $product = Product::find($id); 
        $category = Category::orderBy('name', 'DESC')->get();
        return view('produk.edit', compact('product', 'category'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer',
            'price' => 'required|integer',
            'weight' => 'required|integer',
            'image' => 'nullable|image|mimes:png,jpeg,jpg' //IMAGE BISA NULLABLE
        ]);

        $product = Product::find($id); //AMBIL DATA PRODUK YANG AKAN DIEDIT BERDASARKAN ID
        $filename = $product->image; //SIMPAN SEMENTARA NAMA FILE IMAGE SAAT INI

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
        
            $file->move(public_path('products'), $filename);
        
            File::delete(public_path('products/' . $product->image));
        }        

        $product->update([
            'name' => $request->name,
            'slug' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'stock' => $request->stock,
            'price' => $request->price,
            'weight' => $request->weight,
            'image' => $filename,
            'status' => $request->status
        ]);
        return redirect(route('product.index'))->with(['success' => 'Data Produk Diperbaharui']);
    }
}
