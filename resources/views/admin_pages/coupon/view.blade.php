@extends('templates.admins.layout')
@section('content')
<div class="container-fluid coupon form_ql">
    <div class="card_1">
        <h3 class="card-title">{{ $coupon->ten}}</h3>
        <div class="action">
            <a href="{{ route('get.admin.coupon')}}" class="btn_add primary">
                <i class="fa fa-sign-out" aria-hidden="true"></i>
                Quay lại
            </a>
        </div>
        <div class="form-submit">
            <form action="{{ route('post.coupon')}}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="form_group">
                            <label>Loại khuyến mãi</label>
                            <input class="form_control"
                                value="{{$coupon->dieukien === 1 ? 'Giảm giá cho từng sản phẩm' : 'Giảm giá cho toàn bộ đơn hàng.'}}"
                                readonly />
                        </div>
                        <div class="promotion">

                            <div class="coupon_code {{$coupon->dieukien === 2 ? '' : 'hide' }}">
                                <div class="form_group">
                                    <label>Code</label>
                                    <input name="code" autocomplete='off' class="form_control" value="{{$coupon->code}}"
                                        readonly />
                                </div>

                            </div>

                            <div class="form_group">
                                <label>Loại giảm</label>
                                <input class="form_control"
                                    value="{{$coupon->dieukien === 1 ? 'Giảm giá tiền theo %' : 'Giảm giá theo tiền'}}"
                                    readonly />
                            </div>
                            <div class="form_group">
                                <label>Tiền giảm</label>
                                <input autocomplete='off' name="giamgia" class="form_control"
                                    value="{{currency_format($coupon->giamgia, ($coupon->loaigiam === 2) ? 'đ' : '%')}}"
                                    readonly />
                            </div>
                            @if($errors->first('giamgia'))
                            <span class="error text-danger">{{ $errors->first('giamgia') }}</span>
                            @endif
                            <div class="group">
                                <div class="form_group">
                                    <label>Bắt đầu</label>
                                    <input name="ngaybd" class="form_control" readonly
                                        value=<?= date("d/m/Y", strtotime($coupon->ngaybd)); ?> />
                                </div>
                                <div class="form_group">
                                    <label>Kết thúc</label>
                                    <input name="ngaykt" class="form_control" readonly
                                        value=<?= date("d/m/Y", strtotime($coupon->ngaykt)); ?> />

                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form_group">
                            <div class=" img-preview">
                                <label for="img" class="preview">
                                    <img src="{{asset('uploads/coupon/' . $coupon->hinhanh)}}"
                                        alt="{{$coupon->hinhanh ?? ' Hình ảnh lỗi.'}}">
                                </label>
                            </div>
                        </div>

                        <div class="form_group">
                            <label>Mô tả</label>
                            <textarea class="form_control" readonly name="mota" id=""
                                rows="5">{{$coupon->mota}}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-2 promolist {{+$coupon->dieukien === 1 ? '' : 'hide' }}">
                            <h3 class="mt-2">Áp dụng với các sản phẩm</h2>
                                <div class="list-promo">
                                    <div class="list-cate-item">
                                        <div class="list-cate-item">
                                            @if(count($array))
                                            @foreach($array as $value)
                                            <h3>+ {{$value["cate"]->tenloai}}</h3>
                                            @foreach($value['products'] as $val)
                                            <div class="item_promo">
                                                <img class="img_promo"
                                                    src="{{asset('uploads/product/' . $val->hinhanh)}}">
                                                <span class=" name_promo">{{$val->tensp}}</span>

                                            </div>
                                            @endforeach
                                            @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>

<script>
window.onload = () => {
    const img = document.querySelector('#img');
    const preview = document.querySelector('.preview');
    const generate = document.querySelector('.generate');
    const inputCode = document.querySelector('.form_group input[name="code"]');
    const inputType = document.querySelector('.form_group select[name="loaigiam"]');
    const inputPrice = document.querySelector('.form_group input[name="giamgia"]');
    const selectKm = document.getElementById('loaikm');
    const selectKmCate = document.getElementById('promotion_l');
    const selectKmPro = document.getElementById('promotion_pro');
    const modal = document.querySelector('.modal_t');
    const modalBody = document.querySelector('.modal_t .table');
    const listPromotion = document.getElementById('listPromotion');
    const listPromo = document.querySelector('.list-promo');

    const url = '{{asset("/")}}';
    console.log(url)
    img.addEventListener('change', (e) => {
        let file = e.target.files[0];
        if (!file) {
            return;
        }
        let img = document.createElement('img');
        let fileReader = new FileReader();
        fileReader.readAsDataURL(file);
        // img.src = URL.createObjectURL(file);
        fileReader.onloadend = (e) => {
            img.src = e.target.result;
        }
        preview.appendChild(img);
    });

    generate.addEventListener('click', () => {
        let r = (Math.random() + 1).toString(36).substr(2);
        inputCode.value = r.toUpperCase();
    })

    inputType.addEventListener('change', (e) => {
        let type = e.target.value;
        if (type == 1) {
            inputPrice.max = "100";
        } else {
            inputPrice.max = "";
        }
    })

    inputCode.addEventListener('blur', (e) => {
        let input = e.target.value;
        if (!!input) {
            inputCode.value = input.toUpperCase();
        }
    })

    selectKm.addEventListener('change', (e) => {
        let loaiKm = parseInt(e.target.value);
        if (loaiKm && loaiKm === 1) {
            document.querySelector('.promotion .coupon_code').classList.add('hide');
            document.querySelector('.product_promotion').classList.remove('hide');
            document.querySelector('.promolist').classList.remove('hide');
        } else if (loaiKm && loaiKm === 2) {
            document.querySelector('.promotion .coupon_code').classList.remove('hide');
            document.querySelector('.product_promotion').classList.add('hide');
            document.querySelector('.promolist').classList.add('hide');
        }

    })

    const getListPromotion = {
        category: [],
        products: [],
        option: 0,

        getData: async function() {
            const response = await fetch('http://localhost/website_ban_nuoc/public/getListData');
            const data = await response.json();
            if (response && data) {
                let {
                    category,
                    products
                } = data;
                this.category = category;
                this.products = products;
            }
        },
        renderCate: function() {
            let render =
                '<thead><tr><th scope="col"><input class="checked" type="checkbox" /></th><th>STT</th><th>Tên</th></tr></thead><tbody>';
            render += this.category.map((item, index) => {
                return (`
                    <tr>
                    <td scope=" col"><input data-id="${item.id}" name="checkedAll[]" type="checkbox" /></td>
                    <td>${index}</td>
                    <td style="font-size: 16px; text-transform: uppercase; font-weight: 600;">
                    <img class="img-type" src="${url}/uploads/type/${item.hinhanh}"> ${item.tenloai}</td>
                   </tr>
                `);
            }).join('');
            render += '</tbody>';
            modalBody.innerHTML = render;
            this.handelCheckedAll();
        },
        renderProduct: function() {
            let render =
                '<thead><tr><th scope="col"><input class="checked" type="checkbox" /></th><th>STT</th><th>Tên</th><th>Loại</th></tr></thead><tbody>';
            render += this.products.map((item, index) => {
                return (`
                    <tr>
                    <td scope=" col"><input data-id="${item.id}" name="checkedAll[]" type="checkbox" /></td>
                    <td>${index}</td>
                    <td style="font-size: 16px; text-transform: uppercase; font-weight: 600;">
                    <img class="img-type" src="${url}/uploads/product/${item.hinhanh}"> ${item.tensp}</td>
                    <td style="text-transform: uppercase; font-weight: 600;">${item.id_loaisanpham.tenloai}</td>
                   </tr>
                `);
            }).join('');
            render += '</tbody>';
            modalBody.innerHTML = render;
            this.handelCheckedAll();
        },

        handelClick: function() {
            selectKmCate.addEventListener('change', (e) => {
                let value = +e.target.value;
                this.option = value;
                if (value === 1) {
                    this.renderCate();
                    modal.classList.add('showModal_t');
                }
                if (value === 2) {
                    this.renderProduct();
                    modal.classList.add('showModal_t');
                }
            })

            listPromotion.addEventListener('click', (e) => {
                e.preventDefault();
                const allCheckBox = document.querySelectorAll('input[name="checkedAll[]"]');
                let list = Array.from(allCheckBox).filter(item => item.checked);
                let list_id = list.map(item => +item.dataset.id);

                if (this.option === 1) {
                    let data = list_id.reduce((initValue, id) => {
                        let itemCate = this.category.find(item => item.id === +id);
                        initValue +=
                            ` <div class="list-cate-item"><h3>+ ${itemCate.tenloai}</h3>`;
                        initValue += this.products.map(item => {
                            if (item.id_loaisanpham.id_loai === itemCate.id) {
                                return (`
                                        <div class="item_promo">
                                            <img class="img_promo"
                                                src="${url}/uploads/product/${item.hinhanh}"">
                                            <span class="name_promo">${item.tensp}</span>
                                            <input hidden value=${item.id} name="id_products[]" />
                                        </div>
                                `)
                            }
                        }).join('');
                        initValue += `</div>`;
                        return initValue;
                    }, '');
                    listPromo.innerHTML = data;
                    modal.classList.remove('showModal_t');

                }
                if (this.option === 2) {
                    let arrayCate = this.products.map(item => {
                        if (list_id.includes(item.id)) {
                            return item.id_loaisanpham.id_loai
                        }
                    }).filter(item => item !== undefined);
                    let category = [...new Set(arrayCate)];
                    let data = category.reduce((initValue, id) => {
                        let itemCate = this.category.find(item => item.id === +id);
                        initValue +=
                            ` <div class="list-cate-item"><h3>+ ${itemCate.tenloai}</h3>`;
                        initValue += list_id.map(id => {
                            let item = this.products.find(item => item.id === +id);
                            if (item.id_loaisanpham.id_loai === itemCate.id) {
                                return (`
                                            <div class="item_promo">
                                                <img class="img_promo"
                                                    src="${url}/uploads/product/${item.hinhanh}"">
                                                <span class="name_promo">${item.tensp}</span>
                                                <input hidden value=${item.id} name="id_products[]" />
                                            </div>
                                    `)
                            }
                        }).join('');
                        initValue += `</div>`;
                        return initValue;
                    }, '');
                    listPromo.innerHTML = data;
                    modal.classList.remove('showModal_t');
                }

            })
        },

        handelCheckedAll: function() {
            const checkbox = document.querySelector('.checked');
            const allCheckBox = document.querySelectorAll('input[name="checkedAll[]"]');
            checkbox.addEventListener('change', (e) => {
                let isCheck = e.target.checked;
                allCheckBox.forEach(item => {
                    item.checked = isCheck;
                })
            })

            function getCount() {
                let count = Array.from(allCheckBox).reduce((initial, item) => {
                    return item.checked ? initial + 1 : initial;
                }, 0);

                return count;
            }

            allCheckBox.forEach(item => {
                item.addEventListener('change', (e) => {
                    checkbox.checked = allCheckBox.length === getCount();
                })
            })
        },

        start: function() {
            this.getData();
            this.handelClick();
        }
    }

    getListPromotion.start();
}
</script>


@stop