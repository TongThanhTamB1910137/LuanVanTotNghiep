@extends('templates.admins.layout')
@section('content')
<div class="container-fluid coupon form_ql">
    <div class="card_1">
        <h3 class="card-title">Phí vận chuyển</h3>
        <div class="action">
            <a href="{{ route('get.shipping')}}" class="btn_add primary">
                <i class=" fa fa-sign-out" aria-hidden="true"></i>
                Quay lại
            </a>

        </div>
        @if($district)
        <div class="price-province showFee">
            <div class="title">
                <i class="fa fa-chevron-down" aria-hidden="true"></i> {{$district->district_name}}
            </div>
            <div class="province_content form-submit">
                <div class="form_group">
                    <label>Phí vận chuyển</label>
                    <input autocomplete="off" type="number"
                        value="{{ $district->Feeship ? $district->Feeship->feeship : $district->ProvinceFee->feeship }}"
                        data-id_dis="{{$district->district_code}}" name="feeship" class="form_control" />
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
                            @foreach($district->Ward as $val)
                            <tr>
                                <td scope=" col"><input hidden type="checkbox" /></>
                                <td><span class='nowrap'>{{$val->ward_name}}</span></td>
                                <td>
                                    @if($val->FeeShip)
                                    <input type="number" placeholder="Giá mặc định cho khu vực."
                                        value="{{ $val->Feeship->feeship }}" name="feeship"
                                        data-id_ward="{{$val->ward_code}}" class="form-control" />
                                    @else
                                    <input type="number" placeholder="Giá mặc định cho khu vực."
                                        value="{{ $district->Feeship ? $district->Feeship->feeship : $district->ProvinceFee->feeship }}"
                                        name="feeship" data-id_ward="{{$val->ward_code}}" class="form-control" />

                                    @endif

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @endif

    </div>
</div>



<script>
const btnShow = document.querySelector('#addFeeShip');
const modalFee = document.querySelector('.modal_fee');
const closeFee = document.querySelector('.modal_fee .closeFee');

const toggle = document.querySelectorAll('.price-province .title');

const changeFeeship = document.querySelectorAll('input[name="feeship"]');


toggle.forEach(item => {
    item.addEventListener('click', (e) => {
        let parent = e.target.parentElement;
        parent.classList.toggle('showFee');
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