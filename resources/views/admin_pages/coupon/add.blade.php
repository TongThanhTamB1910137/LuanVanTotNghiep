@extends('templates.admins.layout')
@section('content')
<div class="container-fluid coupon form_ql">
    <div class="card_1">
        <h3 class="card-title">Tạo mã khuyến mãi</h3>
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
                            <select name="loaikm" id="loaikm" class="form_control">
                                <option value="1">Giảm giá cho từng sản phẩm.</option>
                                <option value="2">Tạo mã giảm giá áp dụng cho tổng đơn hàng.</option>
                            </select>
                            @if($errors->first('loaikm'))
                            <span class="error text-danger">{{ $errors->first('loaikm') }}</span>
                            @endif
                        </div>
                        <div class="promotion">

                            <div class="form_group">
                                <label>Tên khuyến mãi</label>
                                <input name="ten" class="form_control" />
                            </div>
                            @if($errors->first('ten'))
                            <span class="error text-danger">{{ $errors->first('ten') }}</span>
                            @endif
                            <div class="coupon_code hide">
                                <div class="form_group">
                                    <label>Code</label>
                                    <input name="code" autocomplete='off' class="form_control" />
                                    <span class="generate"><i class="fa fa-random" aria-hidden="true"></i></span>
                                </div>
                                @if($errors->first('code'))
                                <span class="error text-danger">{{ $errors->first('code') }}</span>
                                @endif

                            </div>
                            <div class="form_group">
                                <label>Loại giảm</label>
                                <select name="loaigiam" id="" class="form_control">
                                    <option value="2">Giảm tiền</option>
                                    <option value="1">Giảm %</option>
                                </select>
                            </div>
                            @if($errors->first('loaigiam'))
                            <span class="error text-danger">{{ $errors->first('loaigiam') }}</span>
                            @endif
                            <div class="form_group">
                                <label>Tiền giảm</label>
                                <input type="number" autocomplete='off' name="giamgia" class="form_control" />
                            </div>
                            @if($errors->first('giamgia'))
                            <span class="error text-danger">{{ $errors->first('giamgia') }}</span>
                            @endif
                            <div class="group">
                                <div class="form_group">
                                    <label>Bắt đầu</label>
                                    <input name="ngaybd" type="date" class="form_control" min="<?= date('Y-m-d'); ?>" />
                                    @if($errors->first('ngaybd'))
                                    <span class="error text-danger">{{ $errors->first('ngaybd') }}</span>
                                    @endif
                                </div>
                                <div class="form_group">
                                    <label>Kết thúc</label>
                                    <input name="ngaykt" type="date" class="form_control" min="<?= date('Y-m-d'); ?>" />
                                    @if($errors->first('ngaykt'))
                                    <span class="error text-danger">{{ $errors->first('ngaykt') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form_group">
                            <div class=" img-preview">
                                <label for="img" class="preview">
                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                    <span>Chọn ảnh cần thêm</soan>
                                </label>
                                <input id="img" type="file" name="hinhanh" hidden class="form_control" />
                            </div>
                        </div>

                        <div class="form_group">
                            <label>Mô tả</label>
                            <textarea class="form_control" name="mota" id="" rows="5"></textarea>
                        </div>
                        @if($errors->first('mota'))
                        <span class="error text-danger">{{ $errors->first('mota') }}</span>
                        @endif

                    </div>
                    <div class="col-md-6 product_promotion">
                        <div class="form_group">
                            <label>Áp dụng cho</label>
                            <select id="promotion_l" name="dieukien" class="form_control">
                                <option value="0">Chọn sản phẩm áp dụng.</option>
                                <option value="1">Nhóm sản phẩm</option>
                                <option value="2">Sản phẩm</option>
                            </select>
                            @if($errors->first('dieukien'))
                            <span class="error text-danger">{{ $errors->first('dieukien') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-2 promolist">
                            <h3 class="mt-2">Áp dụng với các sản phẩm</h2>
                                <div class="list-promo">
                                    <div class="list-cate-item">
                                        <p>Không có sản phẩm nào.</p>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="col-12 action aciton_bottom">
                        <button type="submit" class="btn_add secondary">
                            <i class="fa fa-floppy-o" aria-hidden="true"></i>
                            Lưu khuyến mãi
                        </button>
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
            const response = await fetch(`${url}getListData`);
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
            const inputCates = document.querySelectorAll('input[name="id_cates[]"]');
            let cates = Array.from(inputCates).map(item => +item.value);
            let render =
                '<thead><tr><th scope="col"><input class="checked" type="checkbox" /></th><th>STT</th><th>Tên</th></tr></thead><tbody>';
            render += this.category.map((item, index) => {
                let checked = cates.includes(item.id) ? 'checked' : '';
                return (`
                    <tr>
                    <td scope=" col"><input data-id="${item.id}" ${checked} name="checkedAll[]" type="checkbox" /></td>
                    <td>${index + 1}</td>
                    <td style="font-size: 16px; text-transform: uppercase; font-weight: 600;">
                    <img class="img-type" src="${url}uploads/categories/${item.hinhanh}"> ${item.tenloai}</td>
                   </tr>
                `);
            }).join('');
            render += '</tbody>';
            modalBody.innerHTML = render;
            this.handelCheckedAll();
        },
        renderProduct: function() {
            const inputPros = document.querySelectorAll('input[name="id_products[]"]');
            let pros = Array.from(inputPros).map(item => +item.value);
            let render =
                '<thead><tr><th scope="col"><input class="checked" type="checkbox" /></th><th>STT</th><th>Tên</th><th>Loại</th></tr></thead><tbody>';
            render += this.products.map((item, index) => {
                let checked = pros.includes(item.id) ? 'checked' : '';
                return (`
                    <tr>
                    <td scope=" col"><input data-id="${item.id}" ${checked} name="checkedAll[]" type="checkbox" /></td>
                    <td>${index + 1}</td>
                    <td style="font-size: 16px; text-transform: uppercase; font-weight: 600;">
                    <img class="img-type" src="${url}uploads/product/${item.hinhanh}"> ${item.tensp}</td>
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
                            ` <div class="list-cate-item"><h3>+ ${itemCate.tenloai}</h3>
                            <input hidden value=${itemCate.id} name="id_cates[]" />
                            `;
                        initValue += this.products.map(item => {
                            if (item.id_loaisanpham.id_loai === itemCate.id) {
                                return (`
                                        <div class="item_promo">
                                            <img class="img_promo"
                                                src="${url}uploads/product/${item.hinhanh}"">
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
                            ` <div class="list-cate-item"><h3>+ ${itemCate.tenloai}</h3>
                            <input hidden value=${itemCate.id} name="id_cates[]" />
                            `;
                        initValue += list_id.map(id => {
                            let item = this.products.find(item => item.id === +id);
                            if (item.id_loaisanpham.id_loai === itemCate.id) {
                                return (`
                                            <div class="item_promo">
                                                <img class="img_promo"
                                                    src="${url}uploads/product/${item.hinhanh}"">
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