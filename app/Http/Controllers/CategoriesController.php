<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Calculation\Category;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
    public function index()
    {
        $getCat = Categories::all();
        return view('admin_pages.category.index', compact('getCat'));
    }

    public function add()
    {
        return view('admin_pages.category.add');
    }
    public function create(Request $request)
    {

        $nameImage = $this->uploadImage($request);
        $newCategory = new Categories();
        $newCategory->tenloai = $request->namecategory;
        $newCategory->mota = $request->descriptioncategory;
        $newCategory->hinhanh = $nameImage;
        $newCategory->slug = Str::slug($request->namecategory);

        if ($this->checkName(Str::slug($request->namecategory))) {
            return redirect('admin/category-add');
        }
        $newCategory->save();
        $getCat = Categories::all();
        return view('admin_pages.category.index', compact('getCat'));
    }


    public function checkName($slug)
    {
        $mal = Categories::where('slug', $slug)->get('slug');
        $checkName = "";
        foreach ($mal as $m) {
            $checkName = $m->slug;
        }
        if ($slug == $checkName) {
            return true;
        }
        return false;
    }
    public function edit($slug)
    {
        $editCat = Categories::where('slug', $slug)->first();
        return view('admin_pages.category.edit', compact('editCat'));
    }
    function update(Request $req)
    {
        $id = $req->id_cat;
        $updateCat = Categories::where('id', $id)->first();
        $nameImage = "";
        $updateCat->tenloai = $req->categoryname_edit;
        $updateCat->mota = $req->des_edit;
        if ($req->categoryImage == null) {
            $nameImage = $req->oldname;
        }else{
            $nameImage = $req->categoryImage;
        }
        $updateCat->save();
        $getCat = Categories::all();
        return view('admin_pages.category.index', compact('getCat'));
    }
    function deletecat($id)
    {
        $delCat = Categories::find($id);
        $product=DB::table('products')->where('id_loaisanpham',$id);
        $product->delete();
        $delCat->delete();
        return redirect('admin/category');
    }
    public function uploadImage($req)
    {
        $imageName = "";
        $images = $req->file('categoriesIMG');
        if ($req->hasFile('categoriesIMG')) {
            $images = $req->file('categoriesIMG');
            $imageName = time() . '.' . $images->extension();
            $images->move(public_path('uploads/categories/'), $imageName);
        }
        return $imageName;
    }
}
