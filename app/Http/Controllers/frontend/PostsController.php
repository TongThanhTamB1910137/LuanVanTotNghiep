<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Baiviet;
use App\Models\Comments;
use App\Models\DMBaiviet;
use App\Models\MenuPosts;
use App\Models\Posts;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function index()
    {

        //danh sách bài viết
        $posts = Posts::where(['trangthai' => 1, 'loaibaiviet' => 'tin-tuc'])->get();

        //danh mục bài viết
        $cate = MenuPosts::where('trangthai', 1)->get();
        $viewData = [
            'posts' => $posts,
            'cate' => $cate
        ];
        return view('templates.clients.posts.index', $viewData);
    }

    public function getPosts($slug, Request $request)
    {
        $detail = Posts::where('slug', $slug)->firstOrFail();
        $lated = Posts::where(['trangthai' =>  1, 'loaibaiviet' => 'tin-tuc'])
            ->orderBy('created_at')
            ->limit(5)
            ->get();
        $comments = Comments::where('id_baiviet', $detail->id)
            ->where('type', 'post')
            ->get();
        $meta['title'] = $detail->tieude;
        $meta['description'] = '';
        $meta['url'] = $request->url();
        $meta['image'] = asset('uploads/post/' . $detail->hinhanh);
        return view('templates.clients.posts.detail', ['post' => $detail, 'lated' => $lated, 'comments' => $comments, 'meta' => $meta]);
    }

    public function showPolicy($slug)
    {
        $policyDetails = Posts::where(['trangthai' => 1, 'slug' => $slug])->first();
        if ($policyDetails) {
            return view('templates.clients.posts.policy', ['policyDetails' => $policyDetails]);
        }
        return redirect()->route('get.home');
    }
}