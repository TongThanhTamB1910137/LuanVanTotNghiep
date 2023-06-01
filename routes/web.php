<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\frontend\CouponController;
use App\Models\Order_statisticals;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you cay the RouteServiceProvider within a group which
| contains the "wn register web routes for your application. These
| routes are loaded beb" middleware group. Now create something great!
|
*/

//Dashboard
Route::get('admin/dashboard', 'DashboardController@show')->name('showDashboard');
Route::group(['middleware' => ['auth', 'checkrole', 'salestaff']], function () {
    Route::post('getdata', 'DashboardController@getDateAnalytics')->name('dateget');
    Route::post('/admin/getvisitor', 'DashboardController@getVisitor');
    Route::post('/admin/draworders', 'DashboardController@getDataToDrawOrders');
    Route::post('/admin/statisbydate', 'DashboardController@statisByDate');
    Route::post('/admin/statisbymonth', 'DashboardController@statisByMonth');
    Route::get('/admin/download-exel', 'DashboardController@export');
    Route::post('/admin/drawstatisyear', 'DashboardController@drawstatisyear');
    Route::post('/admin/showSaleDaily', 'DashboardController@showSaleDaily');
    Route::post('admin/export', 'DashboardController@ExportFiles')->name('exportFile');
    Route::post('admin/doi-mat-khau', 'DashboardController@changepassw')->name('changepass');
    Route::get('admin/doi-mat-khaus', 'DashboardController@changepasswview')->name('viewupdatepass');
    Route::get('/admin/get-sales', 'DashboardController@getMoneySaleDaily');
});
Route::get('admin/thong-tin-tai-khoan', 'DashboardController@infologin')->name('infologin');




Route::get('admin/reset-password', 'LoginController@resetPasswordview')->name('viewinputmail');
Route::post('admin/send-mail-reset', 'LoginController@sendMailReset')->name('sendmailreset');
Route::get('admin/reset-pasworods/{email}', 'LoginController@viewchangepassword')->name('form-reset-password');
Route::post('admin/reset-pasworods/{email}', 'LoginController@handlerspw')->name('handlerspw');







//search name material by ajax request
Route::get('/admin/search-name-material', 'ManagerMaterialUseController@searchmal');



//category
Route::group(['middleware' => ['auth', 'checkrole']], function () {
    Route::get('admin/category', 'CategoriesController@index')->name('category.show');
    Route::get('admin/category-add', 'CategoriesController@add')->name('categories.addview');
    Route::post('admin/category-adds', 'CategoriesController@create')->name('categories.addhandle');
    Route::get('admin/category-del/{id}', 'CategoriesController@deletecat')->name('categories.del');
    Route::get('admin/category-edit/{slug}', 'CategoriesController@edit')->name('categories.editview');
    Route::post('admin/category-edit/{id}', 'CategoriesController@update')->name('categories.edithandle');
});


//roles
Route::group(['middleware' => ['checkrole', 'auth']], function () {
    Route::get('admin/phan-quyen', 'RoleController@index')->name('roles.show');
    Route::get('admin/phan-quyen/them-nv', 'RoleController@addview')->name('roles.addview');
    Route::post('admin/phan-quyen/them-nv', 'RoleController@addhandle')->name('roles.addstaff');
    Route::get('admin/phan-quyen/xoa-nv/{id}', 'RoleController@delstaff')->name('del_staff');
    Route::get('admin/phan-quyen/sua-nv/{id}', 'RoleController@edit')->name('edithandle');
    Route::post('admin/phan-quyen/sua-nv/{id}', 'RoleController@update')->name('staff.edithandle');

    Route::get('admin/cap-nhat-thong-tin', 'RoleController@editinfo')->name('updateinfo.view');
    Route::post('admin/cap-nhat-thong-tin/{id}', 'RoleController@updateinfo')->name('updateinfo.handle');
});


//manager material use  
Route::group(['middleware' => ['checkrole', 'auth']], function () {
    Route::get('/admin/quan-ly-nguyen-lieu-su-dung', 'ManagerMaterialUseController@index')->name('quanlysudungnglieu');
    Route::get('admin/add-material-use', 'ManagerMaterialUseController@add')->name('mmu.addview');
    Route::post('admin/add-material-use', 'ManagerMaterialUseController@create')->name('mmu.addhandle');
    Route::get('admin/edit-material-use/{slug}', 'ManagerMaterialUseController@edit')->name('mmu.editview');
    Route::post('admin/edit-material-use', 'ManagerMaterialUseController@update')->name('mmu.edithandle');
    Route::get('/admin/xoa-MMU/{id}', 'ManagerMaterialUseController@delMMU')->name('mmu.del');
    Route::post('/admin/tong-ket', 'ManagerMaterialUseController@turnover')->name('turnover');
    Route::get('/admin/sort-Mal-By-Day', 'ManagerMaterialUseController@sortMalByDay')->name('sort-mmu-by-day');
});



// Route::get('admin/nguyen-lieu', 'MaterialController@show')->name('showMaterial');
// Route::get('admin/sua-nguyen-lieu/{id}', 'MaterialController@editMaterialView')->name('material.editview');
// Route::post('admin/sua-nguyen-lieu/{id}', 'MaterialController@updateMaterial')->name('material.edithandle');
// Route::get('admin/them-nguyen-lieu', 'MaterialController@addMaterialView')->name('material.addview');
// Route::post('admin/them-nguyen-lieu', 'MaterialController@addMaterialHandle')->name('material.addhandle');
// Route::get('admin/xoa-nguyen-lieu/{id}', 'MaterialController@delMaterial')->name('material.delete');
// Route::post('admin/tim-kiem/', 'MaterialController@searchMaterial')->name('material.search');



//products
// Route::group(['middleware' => ['checkrole', 'auth']], function () {
Route::get('admin/san-pham', 'ProductController@show')->name('products.show');
Route::get('admin/them-san-pham', 'ProductController@addProductView')->name('products.addview');
Route::post('admin/them-san-pham', 'ProductController@addProductHandle')->name('products.addhandle');
Route::get('admin/xoa-san-pham/{id}', 'ProductController@deleteProduct')->name('products.del');
Route::get('admin/sua-san-pham/{slug}', 'ProductController@editProductView')->name('products.editview');
Route::post('admin/sua-san-pham/{id}', 'ProductController@updateProduct')->name('products.edithandle');
Route::post('/admin/cap-nhat-trang-thai', 'ProductController@updateStatus')->name('products.updatestatus');
Route::get('/admin/tim-kiem-san-pham', 'ProductController@search')->name('product.search');
// });




//auth
// Route::get('/register', 'RegisterController@showFormRegister')->name('auth.register');
// Route::post('/register', 'RegisterController@postRegister')->name('authregister');
Route::post('/admin/login', 'LoginController@postLogin')->name('authlogin');
Route::get('/admin', 'LoginController@getLogin')->name('auth.login');
Route::get('/admin/login', 'LoginController@logout')->name('auth.logout');

//nguyen lieu
Route::group(['middleware' => ['checkrole', 'auth']], function () {
    Route::get('/admin/nguyen-lieu-ajax', 'MaterialController@showMalAjax');
    Route::get('admin/nguyen-lieu', 'MaterialController@show')->name('showMaterial');
    Route::get('admin/sua-nguyen-lieu/{slug}', 'MaterialController@edit')->name('material.editview');
    Route::post('admin/sua-nguyen-lieu/{id}', 'MaterialController@update')->name('material.edithandle');
    Route::get('admin/them-nguyen-lieu', 'MaterialController@add')->name('material.addview');
    Route::post('admin/them-nguyen-lieu', 'MaterialController@create')->name('material.addhandle');
    Route::get('admin/xoa-nguyen-lieu/{id}', 'MaterialController@delMaterial')->name('material.delete');
    Route::post('admin/tim-kiem/', 'MaterialController@searchMaterial')->name('material.search');
});

//san pham
// Route::get('admin/san-pham', 'ProductController@show')->name('products.show');
// Route::get('admin/them-san-pham', 'ProductController@addProductView')->name('products.addview');
// Route::post('admin/them-san-pham', 'ProductController@addProductHandle')->name('products.addhandle');
// Route::get('admin/sua-san-pham/{slug}', 'ProductController@editProductView')->name('products.editview');
// Route::post('admin/sua-san-pham/{id}', 'ProductController@updateProduct')->name('products.edithandle');

//auth


// Route::get('/fetchData','ProductController@sendData');   
// Route::get('/admin/them-nguyen-lieu-ajax', 'MaterialController@addMaterialViewAjax');
// Route::post('/admin/them-nguyen-lieu-ajax1', 'MaterialController@addMaterialHandleAjax');
// Route::get('/register', 'RegisterController@showFormRegister')->name('auth.register');
// Route::post('/register', 'RegisterController@postRegister')->name('authregister');
// Route::post('admin/login', 'LoginController@postLogin')->name('authlogin');
// Route::get('admin/login', 'LoginController@getLogin')->name('auth.login');
// Route::get('admin/logins', 'LoginController@logout')->name('auth.logout');

//danh sach tai khoan khach hang
Route::get('/customers', 'CustomerController@index')->name('show.customer');
Route::get('/del_customers/{id}', 'CustomerController@delete')->name('delete.customer');
Route::get('/status_customers/{id}', 'CustomerController@updateStatus')->name('update.status.customer');
Route::get('/add_customers', 'CustomerController@add')->name('get.add.customer');
Route::post('/save_customers', 'CustomerController@saveCustomer')->name('get.save.customer');
Route::get('/edit_customers/{id}', 'CustomerController@getEditCustomer')->name('get.edit.customer');
Route::post('/save_edit_customers{id}', 'CustomerController@saveEditCustomer')->name('save.edit.customer');
Route::post('/sendmailcoupon', 'CustomerController@sendmailCustomer')->name('sendmail.coupon');


//xử lí đơn hàng

Route::group(['middleware' => ['salestaff', 'checkrole', 'auth']], function () {
    Route::get('order/{orderStatus}', 'OrderController@index')->name('get.order');
    Route::get('delorder/{id}', 'OrderController@del')->name('get.del');
    Route::get('viewDetail/{id}', 'OrderController@viewDetail')->name('get.viewDetail');
    Route::get('action/{action}/{id}', 'OrderController@action')->name('get.action');
    Route::get('update/{madh}', 'OrderController@update')->name('get.update');
    Route::get('actionPayment/{action}/{id}', 'OrderController@actionPayment')->name('get.actionPayment');
    Route::get('print-order/{madh}', 'OrderController@print_order')->name('print.order');
    Route::post('cancel-order/{id}', 'OrderController@calcelOrder')->name('cancel.order');
    Route::get('confirm-order/{id}', 'OrderController@confirmOrder')->name('confirm.order');
    Route::post('dels', 'OrderController@dels')->name('dels');
    // tao mo don hang
    Route::get('createOrder', 'OrderController@createOrder')->name('create.order');
    Route::get('getCustomer', 'OrderController@getCustomer')->name('get.customer');
    Route::post('createcart', 'OrderController@createcart')->name('post.createcart');
    Route::post('deleteCartAd', 'OrderController@deleteCartAd')->name('post.deletecart');
    Route::post('saveOrderAd', 'OrderController@saveOrderAd')->name('post.saveOrderAd');
    Route::post('updateCartAd', 'OrderController@upCartAd')->name('post.upCartAd');
    Route::get('checkProductExist', 'OrderController@checkProductExist')->name('get.checkProductExist');
});

// thêm mã khuyễn mãi
// Route::get('coupon', 'CouponController@index')->name('get.admin.coupon');
// Route::get('addcoupon', 'CouponController@add')->name('add.coupon');
// Route::post('postcoupon', 'CouponController@post')->name('post.coupon');
// Route::get('deletecoupon/{id}', 'CouponController@delete')->name('delete.coupon');
// Route::get('detailCoupon/{id}', 'CouponController@detailCoupon')->name('get.detail.coupon');
// Route::get('edit/{id}', 'CouponController@edit')->name('get.edit');
// Route::post('editpost/{id}', 'CouponController@editpost')->name('edit.coupon');
// Route::get('show-coupon/{id}', 'CouponController@showCoupon')->name('show.coupon');
// Route::get('active-coupon/{id}', 'CouponController@activeCoupon')->name('active.coupon');


Route::group(['middleware' => ['auth', 'checkrole']], function () {
    Route::get('coupon', 'CouponController@index')->name('get.admin.coupon');
    Route::get('addcoupon', 'CouponController@add')->name('add.coupon');
    Route::post('postcoupon', 'CouponController@post')->name('post.coupon');
    Route::get('deletecoupon/{id}', 'CouponController@delete')->name('delete.coupon');
    Route::get('detailCoupon/{id}', 'CouponController@detailCoupon')->name('get.detail.coupon');
    Route::get('edit/{id}', 'CouponController@edit')->name('get.edit');
    Route::post('editpost/{id}', 'CouponController@editpost')->name('edit.coupon');
    Route::post('editpost/{id}', 'CouponController@editpost')->name('edit.coupon');
    Route::get('show-coupon/{id}', 'CouponController@showCoupon')->name('show.coupon');
    Route::get('active-coupon/{id}', 'CouponController@activeCoupon')->name('active.coupon');
    Route::get('getCategoryPromo', 'CouponController@getCategoryPromo');
    Route::get('getProductPromo', 'CouponController@getProductPromo');
    Route::get('getListData', 'CouponController@getListData');
});


// gioi thieu 

Route::get('intro', 'AdminController@getIntro')->name('get.intro');
Route::post('saveintro', 'AdminController@saveIntro')->name('save.intro');

// chinh sach
Route::get('policy', 'PostController@getPolicy')->name('get.policy');
Route::get('create-policy', 'PostController@createPolicy')->name('create.policy');
Route::post('save-policy', 'PostController@savePolicy')->name('save.policy');
Route::get('active-policy/{id}', 'PostController@activePolicy')->name('active.policy');
Route::get('delete-policy/{id}', 'PostController@deletePolicy')->name('delete.policy');
Route::get('edit-policy/{id}', 'PostController@editPolicy')->name('edit.policy');
Route::post('save-edit-policy/{id}', 'PostController@saveeditPolicy')->name('save.edit.policy');

// loai bai viet
Route::get('typepost', 'PostController@getTypePost')->name('get.typepost');
Route::get('active-menupost/{id}', 'PostController@activeMenuPost')->name('active.menupost');
Route::get('delete-menupost/{id}', 'PostController@deleteMenuPost')->name('delete.menupost');

Route::get('create-menupost', 'PostController@createMenuPost')->name('create.menupost');
Route::post('save-menupost', 'PostController@saveMenuPost')->name('save.menupost');

Route::get('edit-menupost/{id}', 'PostController@editMenuPost')->name('edit.menupost');
Route::post('save-edit-menupost/{id}', 'PostController@saveeditMenuPost')->name('save.edit.menupost');


// bai viet
Route::get('post', 'PostController@getPost')->name('get.post');
Route::get('hot-post/{id}', 'PostController@hotPost')->name('hot.post');
Route::get('active-post/{id}', 'PostController@activePost')->name('active.post');
Route::get('delete-post/{id}', 'PostController@deletePost')->name('delete.post');

Route::get('create-post', 'PostController@createPost')->name('create.post');
Route::post('save-post', 'PostController@savePost')->name('save.post');

Route::get('edit-post/{id}', 'PostController@editPost')->name('edit.post');
Route::post('save-edit-post/{id}', 'PostController@saveeditPost')->name('save.edit.post');

Route::get('comments', 'AdminController@getComment')->name('get.all.comments');
Route::get('delete-comments/{id}', 'AdminController@deleteComment')->name('delete.comments');







// thêm phí vận chuyển

Route::get('van-chuyen', 'ShippingController@index')->name('get.shipping');

Route::post('/priceprovince', 'ShippingController@post')->name('post.province');
Route::post('/changefeeship', 'ShippingController@change')->name('change.province');
Route::get('/getward/{district}', 'ShippingController@getWard')->name('get.ward');
Route::get('/getprice/{id}', 'ShippingController@getPrice')->name('get.price');
Route::get('/delprovince/{procode}', 'ShippingController@delProvince')->name('del.feeprovince');

// slide
Route::get('slide', 'AdminController@getSlide')->name('get.slide');
Route::get('addSlide', 'AdminController@addSlide')->name('add.slide');
Route::post('addSlideP', 'AdminController@postAddSlide')->name('post.slide');
Route::get('editSlide/{id}', 'AdminController@editSlide')->name('edit.slide');
Route::post('editSlideP/{id}', 'AdminController@postEdit')->name('post.edit.slide');

Route::get('deleteSlide/{id}', 'AdminController@deleteSlide')->name('delete.slide');
Route::get('activeSlide/{id}', 'AdminController@activeSlide')->name('active.slide');
Route::post('positionSlide/{id}', 'AdminController@positionSlide')->name('position.slide');


// thông tin hệ thống
Route::get('satic', 'AdminController@staticWeb')->name('get.static');
Route::post('poststatic', 'AdminController@postStatic')->name('post.static');
Route::get('banner', 'AdminController@getBanner')->name('get.banner');
Route::post('postbanner', 'AdminController@postBanner')->name('post.banner');
Route::get('delBanner/{id}', 'AdminController@delBanner')->name('del.banner');
Route::get('bannerShow/{id}', 'AdminController@bannerShow')->name('show.banner');

// lien he
Route::get('contact', 'AdminController@contact')->name('get.contact');
Route::get('editContact/{id}', 'AdminController@edit')->name('detail.contact');
Route::get('deleteContact/{id}', 'AdminController@delete')->name('delete.contact');
Route::post('sendmailcontact', 'AdminController@sendmail')->name('sendmail.contact');
Route::post('sendallMail', 'AdminController@sendmailAll')->name('sendmail.all.contact');



Route::group(['namespace' => 'frontend'], function () {
    //trang chủ
    Route::get('/', 'HomeController@index')->name('get.home');
    //quickview 
    Route::post('/quickview', 'HomeController@quickView')->name('quickview');

    //giỏ hàng
    Route::post('/addCart', 'CartController@addCart')->name('add.cart');
    Route::post('/delItemCart', 'CartController@delItemCart')->name('del.cart');


    Route::get('/Cart', 'CartController@index')->name('get.cart');


    Route::get('/delCart', 'CartController@delCart')->name('testdl');

    //cart update 
    Route::post('/upCart', 'CartController@upCart')->name('get.upCart');
    Route::post('/pupCart', 'CartController@postupCart')->name('postup.cart');
    Route::post('/checkout', 'CartController@postPay')->name('post.checkout');


    Route::get('invoice', 'CartController@InvoiceConfirm')->name('invoice.confirm');

    //thanh toán Paypal sandbox
    Route::get('create-transaction', 'PayPalController@createTransaction')->name('createTransaction');
    Route::get('process-transaction', 'PayPalController@processTransaction')->name('processTransaction');
    Route::get('success-transaction', 'CartController@successTransaction')->name('successTransaction');
    Route::get('cancel-transaction', 'CartController@cancelTransaction')->name('cancelTransaction');


    //thanh toán vnpay sandbox
    Route::post('vnpayPayment', 'VnpayController@vnpayPayment')->name('vnpayPayment');

    //thanh toán momo sandbox
    Route::post('momoPayment', 'VnpayController@momoPayment')->name('momoPayment');
    Route::post('momoPaymentQR', 'VnpayController@momoQR')->name('momoPaymentQR');
    Route::post('returnData', 'VnpayController@returnData');


    //mua hàng thành công

    Route::get('checkoutcomplete', 'CartController@checkoutComplete')->name('checkoutcomplete');


    // tra cứu đơn hàng
    Route::get('searchorder', 'HomeController@searchOrder')->name('search.order');
    Route::post('resultsearchorder', 'HomeController@searchOrderResult')->name('result.searchOrder');







    // đăng nhâp với facebook
    Route::get('/login/{type}', 'LoginSocialController@login')->name('login.facebook');
    Route::get('/callback/{type}', 'LoginSocialController@callback');

    Route::get('/logout', 'LoginSocialController@logout')->name('logout');

    Route::post('/register', 'HomeController@register')->name('register');

    Route::post('/registerSDT', 'LoginSocialController@registerSDT')->name('registerSDT');
    Route::post('/active', 'LoginSocialController@activeSDT')->name('activeSDT');




    //tin tức
    Route::get('posts',  'PostsController@index')->name('get.posts');
    Route::get('posts/{slug}',  'PostsController@getPosts')->name('detail.posts');
    Route::get('policy/{slug}',  'PostsController@showPolicy')->name('show.policy');


    Route::get('about',  'AboutController@index')->name('about');
    Route::post('contact',  'AboutController@contact')->name('send.contact');


    Route::get('products',  'ProductController@index')->name('product');
    Route::get('detail/{p}',  'ProductController@detail')->name('detail');
    Route::post('search',  'ProductController@search')->name('get.search');


    //tài khoản

    Route::get('account/{nav}', 'LoginSocialController@getInfo')->name('get.infouser');
    Route::get('update_user', 'LoginSocialController@update_user')->name('update.user');
    Route::get('transaction', 'AccountController@index')->name('get.user.transaction');
    Route::post('detail', 'AccountController@detail')->name('get.user.detail');
    Route::post('wishlist/{id}', 'AccountController@wishlist')->name('get.user.wishlist');
    Route::get('delwishlist/{id}', 'AccountController@delwishlist')->name('del.user.wishlist');







    //đăng kí 
    Route::get('register', 'RegisterController@index')->name('get.register');
    Route::post('Pregister', 'RegisterController@register')->name('post.register');
    Route::get('active/{customer}/{token}', 'RegisterController@active')->name('register.active');
    Route::get('reActive/{email}', 'RegisterController@reSendMail')->name('re.sendMail');


    //đăng nhập
    Route::post('loginAcc', 'LoginSocialController@loginAcc')->name('post.login');


    Route::get('x', 'RegisterController@get');


    //quên mật khẩu 
    Route::post('forgetPassword', 'LoginSocialController@loginAcc')->name('post.login');
    Route::post('forget-password', 'RegisterController@postforgetPasss')->name('post.forget');
    Route::get('/get-password/{customer}/{token}', 'RegisterController@getPass')->name('get.pass');
    Route::post('/get-password/{customer}', 'RegisterController@postPass')->name('post.pass');

    Route::get('/changepassword', 'AccountController@changePass')->name('change.pass');
    Route::post('/updatePass', 'AccountController@changePassPost')->name('post.change.pass');




    //bình luận
    Route::post('comment/{type}/{id}', 'CommentController@comment')->name('get.comment');
    Route::get('delete/{id}', 'CommentController@deleteComment')->name('delete.comment');

    //ma khuyen mai

    Route::get('getcoupon', 'CouponController@getCoupon')->name('get.coupon');
    Route::post('checkcoupon/', 'CouponController@checkCoupon')->name('check.coupon');




    // tat ca khuyen mai
    Route::get('promotion', 'CouponController@getAllPromotion')->name('get.all.promotion');


    // lay danh sach tinh huyen xa

    Route::get('province', 'LocationController@getProvince')->name('get.db.province');
    Route::get('province/district/{province}', 'LocationController@getDistrict')->name('get.db.district');
    Route::get('province/ward/{district}', 'LocationController@getWard')->name('get.db.ward');
});
