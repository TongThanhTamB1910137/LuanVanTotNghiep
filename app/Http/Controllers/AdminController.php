<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestStatic;
use App\Http\Requests\SlideRequest;
use App\Models\Comments;
use App\Models\Contact;
use App\Models\Image;
use App\Models\Posts;
use App\Models\StaticSetting;
use App\Models\StaticW;
use Egulias\EmailValidator\Warning\Comment;
use Facade\FlareClient\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\In;
use Svg\Tag\Rect;

use function Symfony\Component\VarDumper\Dumper\esc;

class AdminController extends Controller
{
    protected $url = 'admin_pages.image.';

    public function getSlide()
    {
        $slide = Image::where('loai', 'slide')->orderBy('vitri')->get();
        $viewData = [
            'slide' => $slide,
            'count' => count($slide)
        ];
        return view($this->url . 'index', $viewData);
    }
    public function addSlide()
    {
        return view($this->url . 'add');
    }

    public function postAddSlide(SlideRequest $request)
    {
        $data['ten'] = $request->ten;
        $data['link'] =  $request->link;
        $data['loai'] = 'slide';
        $data['vitri'] = 1;
        $img = $request->file('hinhanh');
        if ($img) {
            $newImage = rand(1, 9999999) . '.' . $img->getClientOriginalExtension();
            $img->move('uploads/slide', $newImage);
            $data['hinhanh'] = $newImage;
        }
        $data['trangthai'] = 1;
        Image::create($data);
        return Redirect()->back();
    }

    public function editSlide($id)
    {
        $image = Image::find($id);
        $viewData = [
            'image' => $image,
        ];
        return view($this->url . 'edit', $viewData);
    }

    public function postEdit($id, Request $request)
    {
        $request->validate([
            'ten' => 'required',
        ], [
            'ten.required' => "Tên không để trống.",
        ]);
        $slide = Image::find($id);
        $slide->ten = $request->ten;
        $slide->link = $request->link;
        $img = $request->file('hinhanh');
        if ($img) {
            $newImage = rand(1, 9999999) . '.' . $img->getClientOriginalExtension();
            $img->move('uploads/slide', $newImage);
            $slide['hinhanh'] = $newImage;
        }
        $slide->save();
        return redirect()->route('get.slide');
    }

    public function deleteSlide($id)
    {
        $slide = Image::find($id);
        $slide->delete();
        return redirect()->back();
    }

    public function activeSlide($id)
    {
        $slide = Image::find($id);
        $slide->trangthai = +!$slide->trangthai;

        $slide->save();
        return redirect()->route('get.slide');
    }
    public function positionSlide($id, Request $request)
    {
        $slide = Image::find($id);
        $slide->vitri = $request->vitri;
        $slide->save();

        return redirect()->back();
    }

    public function staticWeb()
    {
        $options = StaticSetting::first();
        if ($options) {
            return view('admin_pages.static.index', ['setting' => json_decode($options->options)]);
        } else {
            return view('admin_pages.static.index');
        }
    }
    public function postStatic(RequestStatic $request)
    {
        $setting = (object) array(
            'name' => $request->ten,
            'email' => $request->email,
            'dienthoai' => $request->dienthoai,
            'diachi' => $request->diachi,
            'iframemap' => trim($request->iframemap),
        );
        $options = StaticSetting::first();
        if ($options) {
            $options->update(['options' => json_encode($setting)]);
        } else {
            StaticSetting::create(['options' => json_encode($setting)]);
        }
        return redirect()->back();
    }

    public function getBanner()
    {
        $bannerHome = Image::where('loai', 'bannerHome')->first();
        $bannerProduct = Image::where('loai', 'bannerProduct')->first();
        $viewData = [
            'bannerHome' => $bannerHome,
            'bannerProduct' => $bannerProduct
        ];
        return view('admin_pages.static.banner', $viewData);
    }
    public function createBanner($img, $imgfile, $type)
    {
        $img->ten = 'banner';
        $img->loai = $type;
        $image = $imgfile;
        if ($image) {
            $newImage = rand(1, 9999999) . '.' . $image->getClientOriginalExtension();
            $image->move('uploads/slide', $newImage);
            $img->hinhanh = $newImage;
        }
        $img->save();
    }
    public function postBanner(Request $request)
    {
        if ($request->bannerHome) {
            $bannerHome = Image::where('loai', 'bannerHome')->first();
            if (!$bannerHome) {
                $bannerHome = new Image;
            } else {
                $urlImg =  'uploads/slide/' . $bannerHome->hinhanh;
                if (file_exists($urlImg)) {
                    unlink($urlImg);
                }
            }
            $this->createBanner($bannerHome, $request->file('bannerHome'), 'bannerHome');
        }
        if ($request->bannerProduct) {
            $bannerProduct = Image::where('loai', 'bannerProduct')->first();
            if (!$bannerProduct) {
                $bannerProduct = new Image;
            } else {
                $urlImg =  'uploads/slide/' . $bannerProduct->hinhanh;
                if (file_exists($urlImg)) {
                    unlink($urlImg);
                }
            }
            $this->createBanner($bannerProduct, $request->file('bannerProduct'), 'bannerProduct');
        }
        return redirect()->back();
    }

    public function delBanner($id)
    {
        $banner = Image::find($id);
        $urlImg =  'uploads/slide/' . $banner->hinhanh;
        if (file_exists($urlImg)) {
            unlink($urlImg);
        }
        $banner->delete();
        return redirect()->back();
    }
    public function bannerShow($id)
    {
        $banner = Image::find($id);
        $banner->trangthai = +$banner->trangthai === 1 ? 2 : 1;
        $banner->save();
        return redirect()->back();
    }

    public function contact()
    {
        $contact = Contact::paginate(10);
        return view('admin_pages.contact.index', ['contact' => $contact]);
    }
    public function edit($id)
    {
        $contact = Contact::find($id);
        return view('admin_pages.contact.edit', ['contact' => $contact]);
    }
    public function delete($id)
    {
        Contact::find($id)->delete();
        return redirect()->back();
    }
    public function sendmail(Request $request)
    {
        $contact = Contact::find($request->id);
        $request->validate([
            'tieudemail' => 'required',
            'noidungmail' => 'required',
        ], [
            'tieudemail.required' => "Tiêu đề không được bỏ trống.",
            'noidungmail.required' => "Nội dung không được bỏ trống.",
        ]);

        try {
            Mail::send('admin_pages.contact.email', ['data' => $request->noidungmail], function ($email) use ($contact) {
                $email->subject('Drinks - Web');
                $email->to($contact->email, ($contact->ten) ? $contact->ten : "");
            });
            $contact->trangthai = 1;
            $contact->save();
            return redirect()->route('get.contact');
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back();
        }
    }

    public function sendmailAll(Request $request)
    {
        $request->validate([
            'tieudemail' => 'required',
            'noidungmail' => 'required',
        ], [
            'tieudemail.required' => "Tiêu đề không được bỏ trống.",
            'noidungmail.required' => "Nội dung không được bỏ trống.",
        ]);
        if (!$request->checks) {
            return redirect()->back()->with('errorSendMail', 'Chưa chọn gmail để gửi.');
        }
        $contact = Contact::whereIn('id', $request->checks)->get();
        $data = [];
        foreach ($contact as $value) {
            $data['email'][] = $value->email;
        }
        try {
            Mail::send('admin_pages.contact.email', ['data' => $request->noidungmail], function ($email) use ($data) {
                $email->subject('Drinks - Web');
                $email->to($data['email'], 'Gửi liên hệ khách hàng.');
            });
            Contact::whereIn('id', $request->checks)->update(['trangthai' => 1]);
            return redirect()->back()->with('successSendMail', 'Đã gửi mail liên hệ thành công.');
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('errorSendMail', 'Gửi mail thất bại.');
        }
    }

    public function getIntro()
    {
        $intro = StaticW::where(['loai' => 'gioi-thieu', 'trangthai' => 1])->first();
        return view('admin_pages.static.intro', ['intro' => $intro]);
    }
    public function saveIntro(Request $request)
    {
        $request->validate([
            'intro' => 'required',
        ], [
            'intro.required' => "Nội dung không để trống.",
        ]);
        $data = new StaticW;
        if ($request->id) {
            $data = StaticW::find($request->id);
        }
        $data['noidung'] = $request->intro;
        $data['trangthai'] = 1;
        $data['loai'] = 'gioi-thieu';
        $data->save();
        if ($data) {
            return redirect()->back()->with('message', 'Đã cập nhật giới thiệu.');
        } else {
            return redirect()->back()->with('message', 'Cập nhật không thành công.');
        }
    }

    public function getComment()
    {
        $comments = Comments::paginate(10);
        return view('admin_pages.static.comments', ['comments' => $comments]);
    }

    public function deleteComment($id)
    {
        $slide = Comments::find($id);
        $slide->delete();
        return redirect()->route('get.all.comments')->with('message', 'Đã xoá thành công.');
    }
}