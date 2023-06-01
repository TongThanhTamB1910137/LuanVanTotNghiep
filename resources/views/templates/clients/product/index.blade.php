@extends('templates.clients.frontend')
@section('content')


<section class="bg-products">
    <div class="container">

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="filter_search_opt">
                    <a href="javascript:void(0);" onclick="openFilterSearch()"><i class="ti-reload"></i></a>
                </div>
            </div>
        </div>

        <div class="row">

            <!-- Single Product -->
            <div class="col-lg-3 col-md-12">
                <div class="search-sidebar sm-sidebar" id="filter_search" style="left:0;">
                    <div class="search-sidebar_header">
                        <h4 class="ssh_heading">Close Filter</h4>
                        <button onclick="closeFilterSearch()" class="w3-bar-item w3-button w3-large"><i
                                class="ti-close"></i></button>
                    </div>
                    <div class="search-sidebar-body">

                        <!-- Single Option -->
                        <div class="single_search_boxed">
                            <div class="widget-boxed-header">
                                <h3 class="ml-4 mt-4">Danh mục</h3>
                            </div>
                            <div class="widget-boxed-body">
                                <div class="side-list no-border">
                                    <div class="filter-card" id="shop-categories">

                                        <!-- Single Filter Card -->
                                        @if($danhmuc)
                                        @foreach($danhmuc as $value)
                                        <div class="single_filter_card filter_card_{{$value->id}}">
                                            <h5 class="cate_homeproduct">
                                                <img src="{{ asset('uploads/categories/'.$value->hinhanh)}}" />
                                                <a href="#{{$value->id}}">{{$value->tenloai}}</a>
                                            </h5>
                                            <!-- 														
														<div class="collapse" id="shoes" data-parent="#shop-categories">
															<div class="card-body">
																<div class="inner_widget_link">
																	<ul>
																		<li><a href="#">Pumps & high Heals<span>112</span></a></li>
																		<li><a href="#">Sandels<span>82</span></a></li>
																		<li><a href="#">Sneakers<span>56</span></a></li>
																		<li><a href="#">Boots<span>101</span></a></li>
																		<li><a href="#">Casual Shoes<span>212</span></a></li>
																		<li><a href="#">Flats Sandel<span>92</span></a></li>
																	</ul>
																</div>
															</div>
														</div> -->
                                        </div>
                                        @endforeach
                                        @endif


                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

            <div class="col-lg-9 col-md-12">

                <!-- Banner -->
                @if($banner && isset($banner))
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="min_banner mb-5">
                            <img src="{{ asset('uploads/slide/'. $banner->hinhanh) }}" class="img-fluid rounded"
                                alt="" />
                        </div>
                    </div>
                </div>
                @endif
                <div class="row filter">
                    <div class="col-lg-12 col-md-12">
                        <div class="toolbar toolbar-products">
                            <div class="toolbar-sorter sorter">
                                <span class="sorter-label" for="sorter">Tìm kiếm</span>
                                <input type="text" class="form-control search" placeholder="Tìm kiếm..." readonly>
                            </div>

                            <!-- <div class="modes">
								<a class="modes-mode mode-grid" title="Grid" href="#"><i class="ti-layout-grid3"></i></a>
								<a class="modes-mode mode-list" title="Grid" href="#"><i class="ti-view-list"></i></a>
							</div>

							<div class="field limiter">
								<label class="label" for="limiter">
									<span>Show</span>
								</label>
								<div class="control">
									<select id="limiter" data-role="limiter" class="limiter-options">
										<option value="5">5</option>
										<option value="10" selected="selected">10</option>
										<option value="15">15</option>
										<option value="20">20</option>
										<option value="25">25</option>
									</select>
								</div>
							</div> -->
                        </div>
                    </div>
                </div>

                <!-- Shorter Toolbar -->
                @if($danhmuc)
                @foreach($danhmuc as $key => $value)
                <div class="row scroll-t-20 item-products" id="{{$value->id}}">
                    <div class="col-lg-12 col-md-12">
                        <div class="toolbar toolbar-products">
                            <div class="toolbar-sorter sorter">
                                <label class="sorter-label" for="sorter">#{{$key + 1}}. {{ $value->tenloai}}</label>
                            </div>
                        </div>
                    </div>
                    @if($product)
                    @foreach($product as $val)

                    @if($value->id == $val->id_loaisanpham)
                    <!-- Single Item -->
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="item">
                            <div class="woo_product_grid">
                                @if(count($val->Coupon) > 0)
                                <span class="woo_offer_sell">
                                    -
                                    {{currency_format($val->Coupon[0]->giamgia, (+$val->Coupon[0]->loaigiam === 2) ? 'đ' : '%')}}</span>
                                @endif
                                <div class="woo_product_thumb">
                                    <img src="{{ asset('uploads/product/'.$val->hinhanh)}}" class="img-fluid" alt="" />
                                </div>
                                <div class="woo_product_caption center">
                                    <div class="woo_title">
                                        <h4 class="woo_pro_title"><a
                                                href="{{route('detail', $val->slug)}}">{{$val->tensp}}</a></h4>
                                    </div>
                                    <div class="woo_price ">
                                        <h6>
                                            @if(count($val->Coupon) > 0)
                                            <?php
                                            $price = 0;
                                            if (+$val->Coupon[0]->loaigiam === 2) {
                                                $price = $val->giaban - $val->Coupon[0]->giamgia;
                                            } else {
                                                $price = $val->giaban - ($val->giaban * $val->Coupon[0]->giamgia / 100);
                                            }
                                            ?>
                                            {{currency_format($price)}}
                                            <span class="less_price">
                                                {{currency_format($val->giaban)}}
                                            </span>
                                            @else
                                            {{currency_format($val->giaban)}}
                                            @endif
                                        </h6>
                                        <a href="javascript:" class="btn-plus quickView" data-id="{{$val->id}}"><i
                                                class="fa fa-plus-circle" aria-hidden="true"></i></a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                    @endif


                </div>
                <!-- row -->
                <!-- row -->
                @endforeach
                @endif

            </div>

        </div>
    </div>
</section>
<div class="modal fade" id="form-search" tabindex="-1" role="dialog" aria-labelledby="add-payment" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" id="view-product">
            <span class="mod-close" data-dismiss="modal" aria-hidden="true"><i class="fas fa-times"></i></span>
            <span class="header-search">Tìm kiếm</span>
            <div class="modal-body">
                <div class="row align-items-center">

                    <div class="input-search ol-lg-12 col-md-12 col-sm-12">
                        <i class="fa fa-search" aria-hidden="true"></i>
                        <input type="text" class="form-control" id="keyword_search"
                            placeholder="Tìm tên sản phẩm mà bạn quan tâm">
                    </div>
                    <div class="row align-items-center list-search col-12">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop