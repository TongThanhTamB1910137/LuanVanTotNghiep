@extends('templates.admins.layout')
@section('content')
<div class="content-order">
    <div class="container-fluid p-0">
        <h1 class="h6 mb-3">Tạo mới đơn hàng</h1>
        <form action="{{ route('post.saveOrderAd')}}" method="post" class="form-submit submit-create-order">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="fa fa-info"></i> Sản phẩm</h5>
                        </div>
                        <div class="card-body row">
                            <div class="col-12">
                                <div class="form_group">
                                    <input autocomplete='off' placeholder="Tìm kiếm sản phẩm"
                                        class="form_control searchProduct" />
                                    <div class="list-pros">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 rowinfo ">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="fa fa-tasks"></i> Thông tin khách hàng</h5>
                        </div>
                        <div class="card-body">
                            <button class="btn-add-info">
                                Thêm thông tin khách hàng
                            </button>
                            <div class="editInfo">
                                <i class="fa fa-pencil ml-2" aria-hidden="true"></i>
                                Sửa
                            </div>
                            <div class="row content-info">

                            </div>
                        </div>
                        @if($errors->first('hoten'))
                        <span class="error text-danger">{{ $errors->first('hoten') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-12 mt-4">

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="fa fa-info"></i> Sản phẩm đã chọn</h5>
                        </div>
                        <div class="card-body row">
                            <div class="col-12 list-prodcut-order">
                                @if(Session::has('cartAD') != null && Session::get('cartAD')->products)
                                <table class="table table-pro">
                                    <thead>
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th>Size</th>
                                            <th>Số lượng</th>
                                            <th>Giảm giá</th>
                                            <th>Giá bán</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(Session::get('cartAD')->products as $key => $value)
                                        <tr>
                                            <th style="display: flex;gap: 10px;">
                                                <img style="width: 70px;height: 70px;object-fit: cover;border-radius: 4px;"
                                                    src="{{ asset('uploads/product').'/'.$value['productInfo']->hinhanh }}"
                                                    class="img-fluid" alt="" />
                                                <span>{{$value['productInfo']->tensp}}</span>
                                            </th>
                                            <th>
                                                <span class="badge badge-primary">{{$value['size']->size_name}}</span>
                                            </th>
                                            <th>
                                                <div class="quanty-updown">
                                                    <button class="arrow down" data-id="{{$key}}"><i class="fa fa-minus"
                                                            aria-hidden="true"></i></button>
                                                    <input readonly class="arrow-input arrow-input-{{$key}}" min='1'
                                                        max='100' data-key="{{$key}}" data-size="{{$value['size']->id}}"
                                                        value="{{$value['quanty']}}" />
                                                    <button class="arrow up" data-id="{{$key}}"><i class="fa fa-plus"
                                                            aria-hidden="true"></i></button>

                                                </div>
                                            </th>
                                            <th>
                                                @if(count($value['productInfo']->Coupon) > 0)
                                                {{'( - '.currency_format($value['productInfo']->Coupon[0]->giamgia, ($value['productInfo']->Coupon[0]->loaigiam === 2) ? 'đ' : '%').' )'}}
                                                @else
                                                0đ
                                                @endif
                                            </th>
                                            <th>
                                                @if(count($value['productInfo']->Coupon) > 0)
                                                <span class="price-old">
                                                    {{ currency_format($value['productInfo']->giagoc + $value['size']->price)}}
                                                </span>
                                                @endif
                                                <span>
                                                    {{ currency_format($value['productInfo']->giaban + $value['size']->price)}}
                                                </span>
                                            </th>
                                            <th>
                                                <a href="#" id="deletecart" data-id="{{$key}}"
                                                    class="btn btn-danger mgr-5">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </a>
                                            </th>
                                        </tr>
                                        @endforeach
                                        <tr class="td-right">
                                            <td colspan="4" class="td-right pr-10pt">
                                                <b> Tổng tiền sản phẩm :</b>
                                            </td>
                                            <td class="td-left" colspan="2">
                                                <span class="">
                                                    {{currency_format(Session::get('cartAD')->totalPrice)}}</span>
                                            </td>
                                        </tr>

                                        <tr class="td-right">
                                            <td colspan="4" class=" pr-10pt">
                                                <b>Tiền phí vận chuyển : </b>
                                            </td>
                                            <td class="td-left" colspan="2">
                                                <span class="">
                                                    @if(Session::get('cartAD')->feeShip)
                                                    + {{currency_format(Session::get('cartAD')->feeShip)}}
                                                    @else
                                                    {{currency_format(0)}}
                                                    @endif
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="td-right">
                                            <td colspan="4" class="pr-10pt">
                                                <b>Thành tiền :</b>
                                            </td>
                                            <td class="td-left" colspan="2">
                                                <span class="">
                                                    <?php
                                                    $price = (Session::get('cartAD')->totalPrice + Session::get('cartAD')->feeShip);
                                                    ?>
                                                    {{currency_format($price > 0 ? $price : 0)}}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                @endif
                            </div>
                        </div>
                        @if($errors->first('sanpham'))
                        <span class="error text-danger">{{ $errors->first('sanpham') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                        Lưu đơn hàng
                    </button>
                </div>
            </div>
        </form>

    </div>

</div>
</div>
<div class="modal-order modal-s">
    <div class="modal_overlay"></div>
    <div class="modal_body">
        <div class="modal_close">
            <i class="fa fa-times"></i>
        </div>
        <div class="modal-content">
            <header class="modal_header">
                THÔNG TIN KHÁCH HÀNG
            </header>
            <div class="modal-order-content">
                <div class="search-auto">
                    <input class="input" placeholder="Tìm khách hàng đã đăng kí" />
                    <i class="fa fa-search" aria-hidden="true"></i>
                    <ul class="list-cus">

                        <li class="list-cus-item">
                        </li>

                    </ul>
                </div>
                <div class="row mra-2 form-submit">
                    <div class="col-12">
                        <span class="noc-valid">Vui lòng nhập đầy đủ thông tin</span>
                    </div>
                    <div class="col-12">
                        <div class="form_group">
                            <label>Họ tên</label>
                            <input autocomplete='off' name="hoten" class="form_control" />
                            <input hidden name="idUser" class="form_control" />
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form_group">
                            <label>Email</label>
                            <input type="email" autocomplete='off' name="email" class="form_control" />
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form_group">
                            <label>Số điện thoại</label>
                            <input type="number" autocomplete='off' name="sodienthoai" class="form_control" />
                        </div>

                    </div>
                    <div class="col-md-4 col-12">
                        <div class="form_group">
                            <label>Thành phố / Tỉnh: </label>
                            <select class="form_control province">
                                <option value="">
                                    Chọn Tỉnh / Thành phố
                                </option>
                                @if($province)
                                @foreach($province as $value)
                                <option value="{{$value->GetProvince->province_code}}">
                                    {{$value->GetProvince->province_name}}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="form_group">
                            <label>Quận / Huyện:</label>
                            <select class="form_control district">
                            </select>
                        </div>

                    </div>
                    <div class="col-md-4 col-12">
                        <div class="form_group">
                            <label>Xã / Phường:</label>
                            <select class="form_control ward">

                            </select>
                        </div>

                    </div>
                    <div class="col-12">
                        <div class="form_group">
                            <label>Địa chỉ chi tiết</label>
                            <textarea autocomplete='off' name="diachi" class="form_control"> </textarea>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="modal_footer">
                <a href="" class="btn gray">Huỷ</a>
                <a href="" class="btn primary save-info">Lưu</a>
            </footer>

        </div>
    </div>
</div>
<div class="modal-order-product modal-s">
    <div class="modal_overlay"></div>
    <div class="modal_body">
        <div class="modal_close close-pro">
            <i class="fa fa-times"></i>
        </div>
        <div class="modal-content">
            <header class="modal_header">
                SẢN PHẨM
            </header>
            <div class="modal-order-content">
                <div class="row form-submit mb-2">
                    <div class="col-4">
                        <div class="form_group">
                            <label>Tìm kiếm</label>
                            <input autocomplete='off' class="form_control searchAutoProduct" />
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form_group">
                            <label>Danh mục sản phẩm</label>
                            <select class="form_control category">
                                <option value="">Tất cả</option>
                                @foreach($category as $value)
                                <option value="{{$value->id}}">{{$value->tenloai}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="scroll-y">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="choose" /></th>
                                <th style="width: 5%;">STT</th>
                                <th style="width: 30%;">Hình ảnh</th>
                                <th>Danh mục</th>
                                <th>Size</th>
                                <th>Giá bán</th>
                            </tr>
                        </thead>
                        <tbody class="list-product">

                        </tbody>
                    </table>
                </div>
            </div>
            <footer class="modal_footer">
                <a href="" class="btn gray close-pro">Huỷ</a>
                <a href="" class="btn primary save-product ">Lưu</a>
            </footer>

        </div>
    </div>
</div>
<script>
window.onload = () => {
    const btnInfo = document.querySelector('.btn-add-info');
    const listProductOrder = document.querySelector('.list-prodcut-order');
    const submitCreateOrder = document.querySelector('.submit-create-order');

    // modal san pham
    const modalProduct = document.querySelector('.modal-order-product');
    const closeProduct = document.querySelectorAll('.modal-order-product .close-pro');
    const modalBodyProduct = document.querySelector('.modal-order-product .modal_body');


    // modal khach hang
    const close = document.querySelector('.modal-order .modal_close');
    const modal = document.querySelector('.modal-order');
    const modalBody = document.querySelector('.modal-order .modal_body');



    const ul = document.querySelector('.list-cus');
    const contentClose = document.querySelector('.modal-order-content');
    const province = document.querySelector('.province');
    const district = document.querySelector('.district');
    const ward = document.querySelector('.ward');

    const btnGray = document.querySelector('.btn.gray');
    const btnSave = document.querySelector('.save-info');
    const btnEdit = document.querySelector('.editInfo');
    const btnSaveProduct = document.querySelector('.save-product');

    const inputAuto = document.querySelector('.input');
    const inputAutoProduct = document.querySelector('.searchProduct');
    const searchAutoProduct = document.querySelector('.searchAutoProduct');
    const searchAutoCategory = document.querySelector('.category');




    // thong tin khach hang
    let hoten = document.querySelector('input[name="hoten"');
    let id = document.querySelector('input[name="idUser"');
    let email = document.querySelector('input[name="email"');
    let sodienthoai = document.querySelector('input[name="sodienthoai"');
    let diachi = document.querySelector('textarea[name="diachi"');


    close.addEventListener("click", (e) => {
        e.preventDefault();
        modal.classList.remove('showmodal_order')
    })
    btnGray.addEventListener("click", (e) => {
        e.preventDefault();
        modal.classList.remove('showmodal_order')
    })

    modal.addEventListener("click", () => {
        modal.classList.remove('showmodal_order')
    })
    modalBody.addEventListener("click", (e) => {
        e.stopPropagation();
    })

    // modal san pham
    Array.from(closeProduct).forEach(item => {
        item.addEventListener("click", (e) => {
            e.preventDefault();
            modalProduct.classList.remove('showmodal_order')
        })
    })

    modalProduct.addEventListener("click", () => {
        modalProduct.classList.remove('showmodal_order')
    })
    modalBodyProduct.addEventListener("click", (e) => {
        e.stopPropagation();
    })



    btnInfo.addEventListener('click', (e) => {
        e.preventDefault();
        modal.classList.add('showmodal_order');
    })
    btnEdit.addEventListener('click', (e) => {
        e.preventDefault();
        modal.classList.add('showmodal_order');
    })
    const order = {
        customer: [],
        products: [],
        categories: [],

        getData: function() {
            (async () => {
                let url = "{{route('get.customer')}}";
                const response = await fetch(
                    url);
                if (response && response.status === 200) {

                    const cus = await response.json();
                    this.customer = cus.customer;
                    this.renderCustomer(this.customer);
                } else {
                    alert('Lấy dữ liệu thất bại!!!')
                }
            })();
        },
        renderCustomer: function(listCus) {
            let data = `<li class="list-cus-item" data-id=''>Khách hàng mới
                    </li>`;
            data += listCus.map((item, index) => {
                return (
                    `<li class="list-cus-item" data-id='${item.id}'>
                    ${index + 1}.  
                    ${item.ten || 'Khách hàng ' + index} 
                    ${item.email ? ' - ' + item.email : '' }
                    ${item.sodienthoai ? ' - ' + item.sodienthoai : ''}
                    </li>`
                )
            }).join('');
            ul.innerHTML = data || `<li>Không có dữ liệu.</li>`;
            let item = document.querySelectorAll('.list-cus-item');
            Array.from(item).forEach(item => {
                item.addEventListener('click', e => {
                    let cus = this.customer.find(item => item.id === +e.target.dataset.id);
                    this.renderInfo(cus);
                    ul.classList.remove('show-order');
                })
            })
        },
        renderProduct: function(listPro) {
            let content = document.querySelector('.list-product');
            let url = "<?= asset('uploads/product/') ?>";
            let data = listPro.map((item, index) => {
                return (`
                                <tr>
                                    <td><input type="checkbox" data-id='${item.id}' name="choose[]" /></td>
                                    <td scope="row">${index + 1}</td>
                                    <td style="display: flex;gap: 10px;"><img
                                            style="width:70px;height:70px;max-width: 70px;border-radius: 4px; object-fit: cover;"
                                            src="${ url +'/'+item.hinhanh}" alt="">
                                        ${item.tensp}
                                    </td>
                                    <td>${item.danhmuc.tenloai}</td>
                                    <td>
                                        <div class="size-product">
                                        ${item.size.length > 1 ?
                                            `
                                            ${
                                            item.size.map(size_item =>{
                                            return (`
                                            <div class="size">
                                                <input type="checkbox" id="${item.id}${size_item.id}" data-id='${size_item.id}' name='size-${item.id}[]' />
                                                <label style="user-select: none;" for="${item.id}${size_item.id}">${size_item.size_name}<span class="ml-2 price-plus">+${size_item.price.toLocaleString('vi-VN', {style : 'currency', currency : 'VND'})}</span></
                                                        label>
                                            </div>
                                            `)
                                            }).join('')
                                            }
                                            ` 
                                             : 
                                             `
                                             ${
                                            item.size.map(size_item =>{
                                            return (`
                                            <div class="size">
                                                <input type="checkbox" id="${item.id}${size_item.id}" data-id='${size_item.id}' name='size-${item.id}[]' />
                                                <label style="user-select: none;" for="${item.id}${size_item.id}">${size_item.size_name}</
                                                        label>
                                            </div>
                                            `)
                                            }).join('')
                                            }
                                             `
                                            }
                                           
                                        </div>
                                    </td>
                                    <td>
                                        ${item.giaban}
                                    </td>
                                </tr>
                                `)
            }).join('');
            content.innerHTML = data;
            this.checkbox();
            this.checkboxItem();
        },

        renderInfo: function(data) {
            if (!data) {
                hoten.value = '';
                hoten.disabled = false;
                id.value = '';
                id.disabled = false;
                email.value = '';
                email.disabled = false;
                sodienthoai.value = '';
                sodienthoai.disabled = false;
                diachi.value = '';

                return;
            }
            hoten.value = data.ten;
            // hoten.disabled = true;
            id.value = data.id;
            // id.disabled = true;
            email.value = data.email;
            // email.disabled = true;
            sodienthoai.value = data.sodienthoai;
            // sodienthoai.disabled = true;
            diachi.value = data.diachi;
        },
        handleEvent: function(data) {

            // tim kiem khach hang
            inputAuto.addEventListener('input', e => {
                let keyword = e.target.value;
                if (keyword) {
                    let cust = this.customer.filter(item => {
                        return item.ten && item.ten.includes(keyword) || item
                            .email &&
                            item.email.includes(e
                                .target.value) || item.sodienthoai && item.sodienthoai
                            .includes(keyword);
                    });
                    this.renderCustomer(cust);
                } else {
                    this.renderCustomer(this.customer);
                }
            })

            // luu thoong tin khach hang
            btnSave.addEventListener('click', (e) => {
                e.preventDefault();
                if (hoten.value && email.value && sodienthoai.value && province
                    .value && district.value && ward.value) {
                    let provinceText = province.options[province.selectedIndex].text;
                    let districtText = district.options[district.selectedIndex].text;
                    let wardText = ward.options[ward.selectedIndex].text;
                    document.querySelector('.content-info').innerHTML = `
                                <div class="col-12 info-cus">
                                    <span> <i class="fa fa-id-card-o" aria-hidden="true"></i> Họ tên:</span>
                                    <span class="text-info-user"> ${hoten.value}</span>
                                    <input hidden name="hoten" value='${hoten.value}' />
                                    ${
                                        id.value ?
                                        ` <input name="id" hidden value='${id.value}' />`
                                        : ''
                                    }
                                </div>
                                <div class="col-12 info-cus">
                                    <span><i class="fa fa-envelope-o" aria-hidden="true"></i> Email:</span>
                                    <span class="text-info-user" > ${email.value}</span>
                                    <input hidden name="email" value='${email.value}' />
                                </div>
                                <div class="col-12 info-cus">
                                    <span><i class="fa fa-phone" aria-hidden="true"></i> Số điện thoại:</span>
                                    <span class="text-info-user"> ${sodienthoai.value}</span>
                                    <input hidden name="sodienthoai" value='${sodienthoai.value}' />
                                </div>
                                <div class="col-12 info-cus">
                                    <span><i class="fa fa-map-marker" aria-hidden="true"></i> Địa chỉ:</span>
                                    <span class="text-info-user"> ${diachi.value +', '+ wardText+', ' + districtText+', ' + provinceText}</span>
                                    <input hidden name="diachi" value='${diachi.value}' />
                                    <input hidden name="province" value='${province.value}' />
                                    <input hidden name="district" value='${district.value}' />
                                    <input hidden name="ward" value='${ward.value}' />
                                </div>`;
                    document.querySelector('.rowinfo').classList.add('edit');

                } else {
                    let valid = document.querySelector('.noc-valid');
                    valid.classList.add('show');
                    setTimeout(() => {
                        valid.classList.remove('show');
                    }, 5000)
                    return;
                }
                modal.classList.remove('showmodal_order')

            })

            // tim kiem san pham
            inputAutoProduct.addEventListener('click', e => {
                this.renderProduct(this.products);
                modalProduct.classList.add('showmodal_order');
            })


            searchAutoProduct.addEventListener('input', (e) => {
                let keyword = e.target.value;
                if (keyword) {
                    let products = this.products.filter(item => {
                        return item.tensp.toLowerCase().includes(keyword.toLowerCase());
                    });
                    this.renderProduct(products);
                } else {
                    this.renderProduct(this.products);
                }
            })
            searchAutoCategory.addEventListener('change', (e) => {
                let id = e.target.value;
                if (id) {
                    let products = this.products.filter(item => {
                        return item.danhmuc.id === +id;
                    });
                    this.renderProduct(products);
                } else {
                    this.renderProduct(this.products);
                }
            })

            // luu san Pham
            btnSaveProduct.addEventListener('click', e => {
                e.preventDefault();
                let checkbox = document.querySelectorAll('input[name="choose[]"]');
                let checked = Array.from(checkbox).filter(item => item.checked);
                let listProduct = [];
                let isValid = false;
                checked.forEach(item => {
                    let check = this.checkValidSize(item, item.dataset.id);
                    if (!check) {
                        isValid = true;
                        return;
                    }
                    listProduct.push({
                        'idProduct': item.dataset.id,
                        'listSize': check
                    });
                })
                if (isValid) {
                    return;
                }
                this.createCart(listProduct);
            }, false)



        },
        checkbox: function() {
            const checkbox = document.querySelector('.choose');
            const allCheckBox = document.querySelectorAll('input[name="choose[]"]');
            checkbox.addEventListener('change', (e) => {
                let isCheck = e.target.checked;
                allCheckBox.forEach(item => {
                    item.checked = isCheck;
                })
            })

        },
        checkboxItem: function() {
            const checkbox = document.querySelector('.choose');
            const allCheckBox = document.querySelectorAll('input[name="choose[]"]');
            allCheckBox.forEach(item => {
                item.addEventListener('change', (e) => {
                    checkbox.checked = allCheckBox.length === this.getCount(allCheckBox);
                })
            })

        },
        getCount: function(allCheckBox) {
            let count = Array.from(allCheckBox).reduce((initial, item) => {
                return item.checked ? initial + 1 : initial;
            }, 0);
            return count;
        },

        checkValidSize: function(input, id) {
            let selector = `input[name="size-${id}[]"]`;
            let checkbox = document.querySelectorAll(selector);
            let isCheck = Array.from(checkbox).some(item => {
                if (!item.checked) {
                    item.parentElement.classList.add('valid');
                    setTimeout(() => {
                        item.parentElement.classList.remove('valid');
                    }, 3000)
                }
                return item.checked;

            })
            if (isCheck) {
                return Array.from(checkbox).map(item => item.checked && item.dataset.id);
            } else {
                return null;
            }

        },
        createCart: function(value) {
            (async () => {
                let url = "{{route('post.createcart')}}";
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        value
                    })
                });
                if (response && response.status === 200) {
                    const check = await response.json();
                    if (check) {
                        listProductOrder.innerHTML = check.html;
                        modalProduct.classList.remove('showmodal_order');
                        this.addEventDelete();
                        this.addCountProduct();
                    }
                } else {
                    alert('laasy du lieu that bai !!!')
                }
            })();
        },
        deleteItemCart: function(value) {
            (async () => {
                let url = "{{route('post.deletecart')}}";
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        'keyCart': value
                    })
                });
                if (response && response.status === 200) {
                    const check = await response.json();
                    if (check) {
                        listProductOrder.innerHTML = check.html;
                        this.addEventDelete();
                        this.addCountProduct();
                    }
                } else {
                    alert('laasy du lieu that bai !!!')
                }
            })();
        },

        addEventDelete: function() {
            const deleteCart = document.querySelectorAll('#deletecart');
            if (!deleteCart) {
                return;
            }
            deleteCart.forEach(item => {
                item.addEventListener('click', e => {
                    e.preventDefault();
                    let id = e.target.closest('#deletecart').dataset.id;
                    if (id) {
                        this.deleteItemCart(id);
                    }
                })
            })

        },
        addCountProduct: function() {

            const arrowUp = document.querySelectorAll('.up');
            const arrowDown = document.querySelectorAll('.down');

            if (!arrowUp || !arrowDown) {
                return;
            }
            Array.from(arrowUp).forEach(item => {
                item.addEventListener('click', e => {
                    e.preventDefault();
                    let id = e.target.closest('.up').dataset.id;
                    const arrowInput = document.querySelector(`.arrow-input-${id}`);
                    let key = arrowInput.dataset.key;
                    let size = arrowInput.dataset.size;
                    let value = +arrowInput.value + 1;
                    if (+value > 100) {
                        return;
                    } else {
                        arrowInput.value = value;
                    }
                    this.updateCart(key, arrowInput.value, size);
                })

            })

            Array.from(arrowDown).forEach(item => {
                item.addEventListener('click', e => {
                    e.preventDefault();
                    let id = e.target.closest('.down').dataset.id;
                    const arrowInput = document.querySelector(`.arrow-input-${id}`);
                    let key = arrowInput.dataset.key;
                    let size = arrowInput.dataset.size;
                    let value = +arrowInput.value - 1;
                    if (+value < 1) {
                        return;
                    } else {
                        arrowInput.value = value;
                    }
                    this.updateCart(key, arrowInput.value, size);
                })
            })
        },

        updateCart: function(key, sl, size) {
            if (!key || !sl || !size) {
                return;
            }
            (async () => {
                let url = "{{route('post.upCartAd')}}";
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        key,
                        sl,
                        size
                    })
                });
                if (response && response.status === 200) {
                    const check = await response.json();
                    if (check) {
                        listProductOrder.innerHTML = check.html;
                        this.addEventDelete();
                        this.addCountProduct();
                    }
                } else {
                    alert('laasy du lieu that bai !!!')
                }
            })();


        },

        checkProductExist: function() {

        },
        start: function(product, category) {
            this.products = product;
            this.categories = category;
            this.getData();
            this.handleEvent();
            this.addEventDelete();
            this.addCountProduct();
            this.checkProductExist();
        }
    }

    order.start(<?= $product ?>, <?= $category ?>);

    inputAuto.addEventListener('click', (e) => {
        e.stopPropagation();
        ul.classList.add('show-order');
    })
    contentClose.addEventListener('click', e => {
        if (e.target.classList.contains('list-cus-item')) {
            e.preventDefault();
        } else {
            ul.classList.remove('show-order');
        }
    })

    province.addEventListener('change', e => {
        let province = e.target.value;
        if (!province) {
            return;
        }
        getDataLocation('https://vapi.vnappmob.com/api/province/district/', province, (
            data) => {
            district.innerHTML = data.map(item => {
                return (
                    `<option value="${item.district_id}">${item.district_name}</option>`
                )
            })
        });
    })
    district.addEventListener('change', e => {
        let district = e.target.value;
        if (!district) {
            return;
        }
        getDataLocation('https://vapi.vnappmob.com/api/province/ward/', district, (
            data) => {
            ward.innerHTML = data.map(item => {
                return (
                    `<option value="${item.ward_id}">${item.ward_name}</option>`
                )
            })
        });
    })
    ward.addEventListener('change', e => {
        let id = e.target.value;
        if (!id) {
            return;
        }
        (async () => {
            let url = "{{asset('/getprice/')}}";
            const response = await fetch(
                `${url}/${id}`
            );
            if (response && response.status === 200) {
                const check = await response.json();
            } else {
                alert('laasy du lieu that bai !!!')
            }
        })();

    })
    const getDataLocation = (url, value, callback) => {
        (async () => {
            const response = await fetch(url + value);
            if (response && response.status === 200) {
                const district = await response.json();
                callback(district.results);
            } else {
                alert('Lấy dữ liệu thất bại !!!')
            }
        })();

    }





}
</script>

@stop