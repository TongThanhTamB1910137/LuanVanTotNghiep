<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestPost;
use App\Models\Comments;
use App\Models\MenuPosts;
use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{

    // chinh sachs
    public function getPolicy()
    {
        $policy = Posts::where('loaibaiviet', 'chinh-sach')->get();
        return view('admin_pages.posts.getPolicy', ['policy' => $policy]);
    }

    // chinh sachs
    public function createPolicy()
    {
        return view('admin_pages.posts.createPolicy');
    }

    public function savePolicy(Request $request)
    {
        $request->validate([
            'tieude' => 'required',
            'noidung' => 'required',
        ], [
            'tieude.required' => "Tiêu đề không để trống.",
            'noidung.required' => "Nội dung không để trống.",
        ]);
        $data['tieude'] = $request->tieude;
        $data['slug'] = Str::slug($request->tieude, '-');
        $data['noidung'] = $request->noidung;
        $data['loaibaiviet'] = 'chinh-sach';
        $data['trangthai'] = 1;
        $policy = Posts::create($data);
        if ($policy) {
            return redirect()->route('get.policy')->with('message', 'Đã thêm chính sách thành công.');
        } else {
            return redirect()->route('get.policy')->with('message', 'Đã thêm chính sách thất bại.');
        }
    }

    public function activePolicy($id)
    {
        $policy = Posts::find($id);
        $policy->trangthai = +!$policy->trangthai;
        $policy->save();
        return redirect()->route('get.policy')->with('message', 'Đã cập nhật thành công.');
    }

    public function deletePolicy($id)
    {
        $slide = Posts::find($id);
        $slide->delete();
        return redirect()->route('get.policy')->with('message', 'Đã xoá thành công.');
    }

    public function editPolicy($id)
    {
        $policy = Posts::find($id);
        $viewData = [
            'policy' => $policy,
        ];
        if ($policy->id) {
            return view('admin_pages.posts.editPolicy', $viewData);
        }
        return redirect()->back();
    }

    public function saveEditPolicy($id, Request $request)
    {
        $request->validate([
            'tieude' => 'required',
            'noidung' => 'required',
        ], [
            'tieude.required' => "Tiêu đề không để trống.",
            'noidung.required' => "Nội dung không để trống.",
        ]);
        $policy = Posts::find($id);
        $policy->tieude = $request->tieude;
        $policy->noidung = $request->noidung;
        $policy->slug = Str::slug($request->tieude, '-');
        $policy->save();
        return redirect()->route('get.policy')->with('message', 'Đã cập nhật thành công.');
    }

    public function getTypePost()
    {
        $menupost = MenuPosts::all();
        return view('admin_pages.posts.getTypePost', ['menupost' => $menupost]);
    }

    public function activeMenuPost($id)
    {
        $menuPost = MenuPosts::find($id);
        $menuPost->trangthai = +!$menuPost->trangthai;
        $menuPost->save();
        return redirect()->back()->with('message', 'Đã cập nhật thành công.');
    }

    public function deleteMenuPost($id)
    {
        $menuPost = MenuPosts::find($id);
        if (count(Posts::where('id_danhmuc', $menuPost->id)->get()) > 0) {
            return redirect()->back()->with('message', 'Danh mục đang tồn tại bài viết.');
        }
        $menuPost->delete();
        return redirect()->back()->with('message', 'Đã xoá thành công.');
    }

    public function createMenuPost()
    {
        return view('admin_pages.posts.createMenuPost');
    }

    public function saveMenuPost(Request $request)
    {
        $request->validate([
            'tenloai' => 'required',
        ], [
            'tenloai.required' => "Tiêu đề không để trống.",
        ]);
        $data['tendanhmuc'] = $request->tenloai;
        $data['slug'] = Str::slug($request->tenloai, '-');
        $data['mota'] = $request->mota;
        $data['trangthai'] = 1;
        $menuPost = MenuPosts::create($data);
        if ($menuPost) {
            return redirect()->route('get.typepost')->with('message', 'Đã thêm loại bài viết thành công.');
        } else {
            return redirect()->route('get.typepost')->with('message', 'Thêm loại bài viết thất bại.');
        }
    }

    public function editMenuPost($id)
    {
        $menuPost = MenuPosts::find($id);
        $viewData = [
            'menuPost' => $menuPost,
        ];
        if ($menuPost->id) {
            return view('admin_pages.posts.editMenuPost', $viewData);
        }
        return redirect()->back();
    }

    public function saveEditMenuPost($id, Request $request)
    {
        $request->validate([
            'tenloai' => 'required',
        ], [
            'tenloai.required' => "Tiêu đề không để trống.",
        ]);
        $menuPost = MenuPosts::find($id);
        $menuPost->tendanhmuc = $request->tenloai;
        $menuPost->mota = $request->mota;
        $menuPost->slug = Str::slug($request->tenloai, '-');
        $menuPost->save();
        return redirect()->route('get.typepost')->with('message', 'Đã cập nhật thành công.');
    }

    // tin tuc
    public function getPost()
    {
        $post = Posts::where('loaibaiviet', 'tin-tuc')->orderBy('hot', 'desc')->paginate(10);
        return view('admin_pages.posts.index', ['post' => $post]);
    }

    // cập nhật tin tức ra trang chủ
    public function hotPost($id)
    {
        $Post = Posts::find($id);
        $Post->hot = +!$Post->hot;
        $Post->save();
        return redirect()->back()->with('message', 'Đã cập nhật thành công.');
    }
    public function activePost($id)
    {
        $Post = Posts::find($id);
        $Post->trangthai = +!$Post->trangthai;
        $Post->save();
        return redirect()->back()->with('message', 'Đã cập nhật thành công.');
    }
    public function deletePost($id)
    {
        $post = Posts::find($id);
        Comments::where('id_baiviet', $post->id)->delete();
        $post->delete();
        return redirect()->back()->with('message', 'Đã xoá.');
    }
    public function createPost()
    {
        $menuPost = MenuPosts::where('trangthai', 1)->get();
        return view('admin_pages.posts.createPost', ['menuPost' => $menuPost]);
    }

    public function savePost(RequestPost $request)
    {
        $request->validate([
            'hinhanh' => 'required',
        ], [
            'hinhanh.required' => "Hình ảnh không để trống.",
        ]);
        $data['tieude'] = $request->tieude;
        $data['slug'] = Str::slug($request->tieude, '-');
        $data['noidung'] = $request->noidung;
        $data['trangthai'] = 1;
        $data['hot'] = 0;
        $data['id_danhmuc'] = $request->danhmuc;
        $data['loaibaiviet'] = 'tin-tuc';
        $img = $request->file('hinhanh');
        if ($img) {
            $newImage = rand(1, 9999999) . '.' . $img->getClientOriginalExtension();
            $img->move('uploads/post', $newImage);
            $data['hinhanh'] = $newImage;
        }
        $post = Posts::create($data);
        if ($post) {
            return redirect()->back()->with('message', 'Đã thêm tin tức thành công.');
        } else {
            return redirect()->back()->with('message', 'Thêm thất bại.');
        }
    }

    public function editPost($id)
    {

        $Post = Posts::find($id);
        $menuPost = MenuPosts::where('trangthai', 1)->get();
        $viewData = [
            'menuPost' => $menuPost,
            'post' => $Post
        ];
        if ($Post->id) {
            return view('admin_pages.posts.editPost', $viewData);
        }
        return redirect()->back();
    }

    public function saveEditPost($id, RequestPost $request)
    {
        $post = Posts::find($id);
        $post['tieude'] = $request->tieude;
        $post['slug'] = Str::slug($request->tieude, '-');
        $post['noidung'] = $request->noidung;
        $post['id_danhmuc'] = $request->danhmuc;
        $img = $request->file('hinhanh');
        if ($img) {
            $newImage = rand(1, 9999999) . '.' . $img->getClientOriginalExtension();
            $img->move('uploads/post', $newImage);
            $urlImg =  'uploads/post/' . $post->hinhanh;
            if (file_exists($urlImg)) {
                unlink($urlImg);
            }
            $post['hinhanh'] = $newImage;
        }
        $post->save();
        return redirect()->route('get.post')->with('message', 'Đã cập nhật.');
    }
}