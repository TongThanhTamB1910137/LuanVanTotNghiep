@extends('templates.admins.layout')
@section('content')
<div class="container-fluid coupon form_ql">
    <div class="card_1">
        <h3 class="card-title">Phí vận chuyển</h3>
        <div class="action">
            <a href="" class="btn_add primary" id="addFeeShip">
                <i class=" fa fa-plus-circle" aria-hidden="true"></i>
                Thêm khu vực vận chuyển
            </a>

        </div>
        @if($feeShip && count($feeShip))
        @foreach($feeShip as $key => $value)
        <div class="price-province {{($key === 0) ? 'showFee' : ''}}">
            <div class="title">
                <i class="fa fa-chevron-down" aria-hidden="true"></i> {{$value->Province->province_name}}
            </div>
            <div class="province_content form-submit ">
                <a href="{{route('del.feeprovince', $value->Province->province_code)}}" class="btn_add danger"
                    style="display: inline-flex;" id="addFeeShip">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                    Xoá khu vực
                </a>
                <div class="form_group">
                    <label>Phí vận chuyển</label>
                    <input autocomplete="off" type="number" value="{{$value->feeship}}" name="feeship"
                        data-id_pro="{{$value->province_id}}" class="form_control" />
                </div>
                <div class="price-disrict">
                    <div class="heading">
                        <h5>Phí vận chuyển cho Quận / Huyện.</h5>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Quận / Huyện</th>
                                <th>Phí vận chuyển</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($value->District as $val)
                            <tr>
                                <td scope=" col">

                                    @if($val->FeeShip)
                                    <input type="checkbox" hidden value="{{$val->FeeShip->feeship}}" name="checkfee"
                                        data-id_dis="{{$val->district_code}}"
                                        {{$val->trangthai === 1 ? 'checked' : ''}} />
                                    @else

                                    <input type="checkbox" hidden value="{{$value->feeship}}" name="checkfee"
                                        data-id_dis="{{$val->district_code}}" checked />

                                    @endif
                                </td>
                                <td><span class='nowrap'>{{$val->district_name}}</span></td>
                                <td>
                                    @if($val->FeeShip)
                                    <input type="number" placeholder="Giá mặc định cho khu vực."
                                        value="{{$val->FeeShip->feeship}}" name="feeship"
                                        data-id_dis="{{$val->district_code}}" class="form-control" />
                                    @else

                                    <input type="number" placeholder="Giá mặc định cho khu vực."
                                        value="{{$value->feeship}}" name="feeship" data-id_dis="{{$val->district_code}}"
                                        class="form-control" />

                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('get.ward', $val->district_code)}} " class="btn btn-primary mgr-5">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @endforeach
        @endif

    </div>
</div>
<div class="modal_fee">
    <div class="modal_fee-body">
        <form action="{{route('post.province')}}" method="post">
            @csrf
            <div class="modal_fee-title">
                <h4>Thêm khu vực vận chuyển</h4>
            </div>
            <div class="modal_fee-content">
                <select name="province_code" id="" class="province form-control">
                    @if($province && count($province))
                    @foreach($province as $value)
                    <option value="{{$value->province_code}}">{{$value->province_name}}</option>
                    @endforeach
                    @endif
                </select>

                <input type="number" placeholder="Giá mặc định cho khu vực." value=10000 name="feeship"
                    class="form-control" />
            </div>
            <div class="modal_fee-action">
                <button class="btn danger closeFee">Huỷ</button>
                <button type="submit" class="btn primary">Lưu</button>
            </div>
        </form>
    </div>
</div>


<script>
const btnShow = document.querySelector('#addFeeShip');
const modalFee = document.querySelector('.modal_fee');
const closeFee = document.querySelector('.modal_fee .closeFee');
const toggle = document.querySelectorAll('.price-province .title');

const changeFeeship = document.querySelectorAll('input[name="feeship"]');
const checkFeeship = document.querySelectorAll('input[name="checkfee"]');



toggle.forEach(item => {
    item.addEventListener('click', (e) => {
        let parent = e.target.parentElement;
        parent.classList.toggle('showFee');
    })
})

btnShow.addEventListener('click', (e) => {
    e.preventDefault();
    modalFee.classList.add('showFee');
})

closeFee.addEventListener('click', (e) => {
    e.preventDefault();
    modalFee.classList.remove('showFee');
})

checkFeeship.forEach(check => {
    check.addEventListener('change', (e) => {
        console.log(e.target.value);
    })
})

changeFeeship.forEach(item => {
    item.addEventListener('blur', (e) => {
        const dis_id = e.target.dataset.id_dis;
        const pro_id = e.target.dataset.id_pro;
        const ward_id = e.target.dataset.id_ward;
        const value = e.target.value;
        const url = "{{ asset('/')}}";
        if (value) {
            (async () => {
                const response = await fetch(
                    `${url}changefeeship`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]')
                                .getAttribute('content'),
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            id_pro: pro_id,
                            id_dis: dis_id,
                            'ward': ward_id,
                            feeship: value
                        })
                    });
                if (response && response.status === 200) {
                    const check = await response.json();
                    if (check) {
                        e.target.value = check;
                        toastr.options.timeOut = 100;
                        toastr.info('Đã cập nhật.');
                    }
                } else {
                    alert('laasy du lieu that bai !!!')
                }
            })();
        }
    })
})
</script>
@stop