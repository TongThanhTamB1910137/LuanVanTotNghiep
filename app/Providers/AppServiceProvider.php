<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Order;
use App\Models\Posts;
use App\Models\StaticSetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\User;
use Illuminate\Support\Facades\Auth;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        $options = StaticSetting::first();
        if ($options) {
            View::share('setting', json_decode($options->options));
        }

        $all = Order::whereNotNull('trangthai')->count();
        $receive = Order::where('trangthai', 1)->count();
        $process = Order::whereNotIn('trangthai', [1, 4, -1, 5])->count();
        $success = Order::where('trangthai', 4)->orWhere('trangthai', 5)->count();
        $cancel = Order::where('trangthai', -1)->count();

        // $getIDLog = Auth::user()->id;
        // $getlogininfo = User::where('id', $getIDLog)->first();

        $viewData = [
            'all' => $all,
            'receive' => $receive,
            'process' => $process,
            'cancel' => $cancel,
            'success' => $success,
            // 'infolog' => $getlogininfo->name_staff
        ];
        $policy = Posts::where(['trangthai' => 1, 'loaibaiviet' => 'chinh-sach'])->get();
        View::share('policy', $policy);
        View::share($viewData);
    }
}
