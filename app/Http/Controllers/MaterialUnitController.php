<?php
namespace App\Http\Controllers;
use App\Models\MaterialUnit;
use Illuminate\Http\Request;

class MaterialUnitController extends Controller
{
    public function show()
    {
        $don_vi = MaterialUnit::all();

        return view('admin_pages.materialUnit.index',compact('don_vi'));
    }
}
