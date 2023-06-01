<?php

namespace App\Http\Controllers;

use App\Exports\multipleExport;
use App\Exports\saleExport;
use App\Mail\sendMail;
use App\Models\Cart;
use App\Models\ManagerMaterialUse;
use App\Models\Order;
use App\Models\Products;
use App\Models\Sale_statisticals;
use App\Models\Visitors;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Aws\Middleware;
use CKSource\CKFinder\Command\Proxy;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function __construct()
    {
        // $this->middleware('checkrole');
    }
    public function show(Request $req)
    {
        $nowMonth = Carbon::now('Asia/Ho_Chi_Minh')->month;
        $nowYear = Carbon::now('Asia/Ho_Chi_Minh')->year;
        $nowday = Carbon::now('Asia/Ho_Chi_Minh')->day;
        $statisByYear = array();
        $statisByDay = array();
        $datadays = null;
        for ($i = 1; $i < 13; $i++) {
            $month = 'Tháng ' . $i;
            $data['name'] = $month;
            $data['y'] = null;
            $data['drilldown'] = $i;
            if ($nowMonth >= $i) {
                $d = $this->statisByMonthy1($i);
                $data['y'] = $d;
                $datadays['data'] = array();
                // lay thong ke theo tung ngay
                $days = Carbon::createFromDate($nowYear, $i)->daysInMonth;
                for ($j = 1; $j <= $days; $j++) {
                    $day = 'Ngày ' . $j . '/' . $i . '/' . $nowYear;
                    if ($i <= $nowMonth) {
                        if ($i === $nowMonth && $j > $nowday) {
                            array_push($datadays['data'], [$day, null]);
                        } else {
                            array_push($datadays['data'], [$day, $this->statisByDay($i, $j)]);
                        }
                    } else {
                        array_push($datadays['data'], [$day, null]);
                    }
                }
            }
            $datadays['name'] = $data['name'] = $month;
            $datadays['id'] = $i;
            array_push($statisByYear, $data);
            array_push($statisByDay, $datadays);
            $datadays = array();
        }
        $nameOrder = DB::select("SELECT id_san_pham_order,so_luot_dat FROM order_statisticals ORDER BY so_luot_dat DESC LIMIT 5");
        $data = [];
        for ($i = 0; $i < count($nameOrder); $i++) {
            $nameP = DB::select("SELECT tensp FROM products WHERE id=" . $nameOrder[$i]->id_san_pham_order);

            $value['name'] = $nameP[0]->tensp;
            $value['y'] = $nameOrder[$i]->so_luot_dat;
            array_push($data, $value);
        }
        $name_login = auth()->user()->name_staff;
        if ($name_login == null) {
            return view('auths.login');
        }
        $countProduct = Products::where('trangthai', 1)->count();
        $countOrder = Order::whereDate('ngaytao', Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d'))->count();
        return view('templates.admins.index', compact('name_login'), ['topproduct' => json_encode($data), 'statisByYear' => json_encode($statisByYear), 'statisByDay' => json_encode($statisByDay), 'countProduct' => $countProduct, 'countOrder' => $countOrder]);
    }

    public function showVisitors(Request $req)
    {
        // $user_ip_address = $req->ip();
        $user_ip_address = $this->getUserIpAddr();
        //current user online
        $visitors_current = Visitors::where('ip_address', $user_ip_address)->get();
        $visitors_count = $visitors_current->count();
        if ($visitors_count < 1) {
            $visitor = new Visitors();
            $visitor->ip_address = $user_ip_address;
            $visitor->date_visitor = Carbon::now('asia/Ho_Chi_Minh')->toDateString();
            $visitor->save();
        }
    }

    public function getUserIpAddr()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public function getDataToDrawOrders()
    {
        // $nameOrder = DB::select("select id_san_pham_order,so_luot_dat from order_statisticals");
        $nameOrder = DB::select("SELECT id_san_pham_order,so_luot_dat FROM order_statisticals ORDER BY so_luot_dat DESC LIMIT 5");
        $newArrName = array();
        $newArrNum = array();
        for ($i = 0; $i < count($nameOrder); $i++) {
            $nameP = DB::select("SELECT tensp FROM products WHERE id=" . $nameOrder[$i]->id_san_pham_order);
            array_push($newArrName, $nameP[0]->tensp);
            array_push($newArrNum, $nameOrder[$i]->so_luot_dat);
        }
        return response()->json([
            'top_sale_name' => $newArrName,
            'top_sale_num' => $newArrNum,
        ]);
    }

    public function getVisitor()
    {
        $getVisitor = Visitors::all();
        $countVisitor = $getVisitor->count();
        return response()->json(
            [
                'count_vistor' =>  $countVisitor
            ]
        );
    }

    public function statisByDate(Request $req)
    {
        $turnover = 0;
        $getUseMaterialByDate = ManagerMaterialUse::where('ngay_tong_ket', $req->statisDate)->get();
        $getSaleByDate = Sale_statisticals::where('ngay_ban', $req->statisDate)->get();

        if ($getUseMaterialByDate->count() > 0 && $getSaleByDate->count() > 0) {
            $moneySale = 0;
            $moneyBuyMaterial = 0;

            foreach ($getSaleByDate as $val) {
                $moneySale += $val->tien_don_hang;
            }

            foreach ($getUseMaterialByDate as $val) {
                $moneyBuyMaterial += $val->so_luong * $val->don_gia;
            }

            $turnover = $moneySale - $moneyBuyMaterial;
            return response()->json(
                [
                    'result' => $turnover,
                    'result_error' => '0',
                ]
            );
        }

        return response()->json(
            [
                'result_error' => '1',
            ]
        );
    }

    public function statisByMonth(Request $req)
    {
        $getMonthS = $req->getMonth;
        // ManagerMaterialUse 
        $moneySale = 0;
        $moneyBuyMaterial = 0;
        // echo "thang chon la:".$getMonth;
        $getSaleByMonth = Sale_statisticals::whereMonth('ngay_ban', $getMonthS)->get();
        $getUseMaterialByMonth = ManagerMaterialUse::whereMonth('ngay_tong_ket', $getMonthS)->get();
        if ($getUseMaterialByMonth->count() > 0 && $getSaleByMonth->count() > 0) {
            foreach ($getSaleByMonth as $val) {
                $moneySale += $val->tien_don_hang;
            }
            foreach ($getUseMaterialByMonth as $val) {
                $moneyBuyMaterial += $val->so_luong * $val->don_gia;
            }
            $turnoverMonth = $moneySale - $moneyBuyMaterial;
            return response()->json([
                'result_error' => '0',
                'month_turnover' => $turnoverMonth
            ]);
        }
        return response()->json([
            'result_error' =>  '1'
        ]);
    }

    function statisByMonthy1($month)
    {
        $nowYear = Carbon::now('Asia/Ho_Chi_Minh')->year;
        $moneySale = 0;
        $moneyBuyMaterial = 0;
        $getSaleByMonth = Sale_statisticals::whereYear('ngay_ban', $nowYear)->whereMonth('ngay_ban', $month)->get();
        $getUseMaterialByMonth = ManagerMaterialUse::whereYear('ngay_tong_ket', $nowYear)->whereMonth('ngay_tong_ket', $month)->get();
        if ($getSaleByMonth->count() > 0) {
            foreach ($getSaleByMonth as $val) {
                $moneySale += $val->tien_don_hang;
            }
            if ($getUseMaterialByMonth->count() > 0) {
                foreach ($getUseMaterialByMonth as $val) {
                    $moneyBuyMaterial += $val->so_luong * $val->don_gia;
                }
            } else {
                $moneyBuyMaterial += 0;
            }
            $turnoverMonth = $moneySale - $moneyBuyMaterial;
            return $turnoverMonth;
        }
        return 0;
    }
    function statisByDay($month, $day)
    {
        $nowYear = Carbon::now('Asia/Ho_Chi_Minh')->year;
        $date = Carbon::createFromDate($nowYear, $month, $day)->format('Y-m-d');
        $moneySale = 0;
        $moneyBuyMaterial = 0;
        $getSaleByMonth = Sale_statisticals::whereDate('ngay_ban', $date)->get();
        $getUseMaterialByMonth = ManagerMaterialUse::whereDate('ngay_tong_ket', $date)->get();
        if ($getSaleByMonth->count() > 0) {
            foreach ($getSaleByMonth as $val) {
                $moneySale += $val->tien_don_hang;
            }
            if ($getUseMaterialByMonth->count() > 0) {
                foreach ($getUseMaterialByMonth as $val) {
                    $moneyBuyMaterial += $val->so_luong * $val->don_gia;
                }
            } else {
                $moneyBuyMaterial += 0;
            }
            $turnoverMonth = $moneySale - $moneyBuyMaterial;
            return $turnoverMonth;
        }
        return 0;
    }

    function statisByMonthy($month)
    {
        $nowYear = Carbon::now('Asia/Ho_Chi_Minh')->year;
        $moneySale = 0;
        $moneyBuyMaterial = 0;
        $getSaleByMonth = Sale_statisticals::whereYear('ngay_ban', $nowYear)->whereMonth('ngay_ban', $month)->get();
        $getUseMaterialByMonth = ManagerMaterialUse::whereYear('ngay_tong_ket', $nowYear)->whereMonth('ngay_tong_ket', $month)->get();
        if ($getUseMaterialByMonth->count() > 0 && $getSaleByMonth->count() > 0) {
            foreach ($getSaleByMonth as $val) {
                $moneySale += $val->tien_don_hang;
            }
            foreach ($getUseMaterialByMonth as $val) {
                $moneyBuyMaterial += $val->so_luong * $val->don_gia;
            }
            $turnoverMonth = $moneySale - $moneyBuyMaterial;
            return $turnoverMonth;
        }
        return 0;
    }

    public function drawstatisyear(Request $req)
    {
        $data = array();
        for ($i = 1; $i < 13; $i++) {
            $d = $this->statisByMonthy1($i);
            array_push($data, $d);
        }
        return response()->json([
            'data' => $data
        ]);
    }

    public function showSaleDaily()
    {
        $today = Carbon::now()->format('Y/m/d');
        $moneySale = 0;
        $moneyBuyMaterial = 0;
        $turnover = 0;
        $getUseMaterialDaily = ManagerMaterialUse::where('ngay_tong_ket', $today)->get();
        $getSaleDaily = Sale_statisticals::where('ngay_ban', $today)->get();
        // if ($getUseMaterialDaily->count() > 0 && $getSaleDaily->count() > 0)
        if ($getSaleDaily->count() > 0) {

            foreach ($getSaleDaily as $val) {
                $moneySale += $val->tien_don_hang;
            }
            if ($getUseMaterialDaily->count() > 0) {
                foreach ($getUseMaterialDaily as $val) {
                    $moneyBuyMaterial += $val->so_luong * $val->don_gia;
                }
            } else {
                $moneyBuyMaterial += 0;
            }
            // foreach ($getUseMaterialDaily as $val) {
            //     $moneyBuyMaterial += $val->so_luong * $val->don_gia;
            // }
            $turnover = $moneySale - $moneyBuyMaterial;
            return response()->json([
                "today" => $turnover
            ]);
        }
        return response()->json([
            "today" => 0
        ]);
    }

    public function export()
    {
        $nameFile = "thong_ke.xlsx";
        // Excel::store(new saleExport(), 'file_exel/' . $nameFile);
        Storage::download('file_exel/' . $nameFile);
    }

    public function ExportFiles(Request $request)
    {


        $codeQ = $request->chooseTypeExport;
        $dataMonth = $request->chooseMonthExport;
        $dataDay = $request->chooseDayExport;

        //convert time to timestamp
        $today = Carbon::now()->timestamp;
        $timePicker = new Carbon($dataDay);
        $timestampPicker = $timePicker->timestamp;

        if ($timestampPicker - $today > 0) {
            return view('templates.admins.index');
        }
        $data = null;
        if ($codeQ == 1) {
            $data = $dataDay;
        } else {
            $data = $dataMonth;
        }
        ob_end_clean(); // this
        ob_start(); // and this
        $nameFile = "thong_ke.xlsx";
        Excel::store(new multipleExport($codeQ, $data, $request->datepickyear), 'file_exel/' . $nameFile);
        return  Storage::download('file_exel/' . $nameFile);
    }

    public function infologin()
    {
        $idLogin = auth()->user()->id;
        $getLogin = User::where('id', $idLogin)->first();
        return view('admin_pages.infologin.index', compact('getLogin'));
    }

    public function changepasswview()
    {
        return view('admin_pages.infologin.changepass');
    }

    public function changepassw(Request $req)
    {
        $oldpass = $req->oldpass;
        $newpass = $req->newpass;
        $idLog = auth()->user()->id;
        $getAccountLogin = User::where('id', $idLog)->first();
        $emailUser = $getAccountLogin->email;
        $getPass = $getAccountLogin->password;
        if (Hash::check($oldpass, $getPass)) {
            $user = User::find($idLog);
            $user->password = bcrypt($newpass);
            $user->save();
            $mailable = new sendMail($newpass);
            Mail::to($emailUser)->send($mailable);
            $idLogin = auth()->user()->id;
            $getLogin = User::where('id', $idLogin)->get();
            session()->put('change_pass', 'Thay đổi mật khẩu thành công');
        }

        Auth::logout();
        return redirect('admin');
        // $idLogin = auth()->user()->id;
        // $getLogin = User::where('id', $idLogin)->get();
        // return view('admin_pages.infologin.index', compact('getLogin'));
    }


    function getMoneySaleDaily()
    {
        $today = Carbon::now('asia/Ho_Chi_Minh');
        $f_today = Carbon::parse($today)->format('y-m-d');
        $getMoney = Sale_statisticals::where('ngay_ban', $f_today)->get();
        $sumMoney = 0;
        foreach ($getMoney as $value) {
            $sumMoney += $value->tien_don_hang;
        }
        return response()->json([
            'data' => $sumMoney
        ]);
    }
}