@extends($active_theme)
@section('title')
    <title>{{ $seo_setting->seo_title }}</title>
@endsection

@section('title')
    <meta name="description" content="{{ $seo_setting->seo_description }}">
@endsection

@section('frontend-content')
    <!--=========================
        BREADCRUMB START
    ==========================-->
    <div class="wsus__breadcrumb" style="background: url({{ asset($breadcrumb->image) }});">
        <div class="wsus__breadcrumb_overlay pt_90 xs_pt_60 pb_95 xs_pb_65">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <nav aria-label="breadcrumb">
                            <h1>{{__('user.Service')}}</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{__('user.Service')}}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--=========================
        BREADCRUMB END
    ==========================-->


        <!--=========================
        SERVICES START
    ==========================-->
    <section class="wsus__services_page mt_100 xs_mt_70 mb_100 xs_mb_70">
        <div class="container">
            <form action="{{ route('services') }}" id="search_service_form">
                <div class="row">
                    <div class="col-xl-3 col-sm-6 col-lg-3">
                        <div class="wsus__service_search">
                            <label>{{__('user.Location')}}</label>
                            <select name="service_area" class="select_2 search_service_item">
                                <option value="">{{__('user.Select')}}</option>

                                @if (request()->has('service_area'))
                                    @foreach ($service_areas as $service_area)
                                    <option {{ request()->get('service_area') == $service_area->slug ? 'selected' : '' }} value="{{ $service_area->slug }}">{{ $service_area->name }}</option>
                                    @endforeach
                                @else
                                    @foreach ($service_areas as $service_area)
                                    <option value="{{ $service_area->slug }}">{{ $service_area->name }}</option>
                                    @endforeach
                                @endif



                            </select>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-lg-3">
                        <div class="wsus__service_search">
                            <label>{{__('user.Category')}}</label>
                            <select name="category" class="select_2 search_service_item">
                                <option value="">{{__('user.Select')}}</option>

                                    @if (request()->has('category'))
                                        @foreach ($categories as $category)
                                        <option {{ request()->get('category') == $category->slug ? 'selected' : '' }} value="{{ $category->slug }}">{{ $category->name }}</option>
                                        @endforeach
                                    @else
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->slug }}">{{ $category->name }}</option>
                                        @endforeach
                                    @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-lg-3">
                        <div class="wsus__service_search">
                            <label>{{__('user.Price Range')}}</label>
                            <select name="price_range" class="select_2 search_service_item">
                                <option value="">{{__('user.Select')}}</option>
                                @if (request()->has('price_range'))
                                    <option {{ request()->get('price_range') == 'high_price' ? 'selected' : '' }}  value="high_price">{{__('user.high Price')}}</option>
                                    <option {{ request()->get('price_range') == 'low_price' ? 'selected' : '' }} value="low_price">{{__('user.low Price')}}</option>
                                @else
                                    <option value="low_price">{{__('user.low Price')}}</option>
                                    <option value="high_price">{{__('user.high Price')}}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-lg-3">
                        <div class="wsus__service_search ">
                            <label>{{__('user.Others')}}</label>
                            <select name="others" class="select_2 search_service_item">


                                @if (request()->has('others'))
                                    <option value="">{{__('user.Select')}}</option>
                                    <option {{ request()->get('others') == 'asc' ? 'selected' : '' }} value="asc">{{__('user.Ascending')}}</option>
                                    <option {{ request()->get('others') == 'desc' ? 'selected' : '' }} value="desc">{{__('user.Descending')}}</option>
                                @else
                                    <option value="">{{__('user.Select')}}</option>
                                    <option value="asc">{{__('user.Ascending')}}</option>
                                    <option value="desc">{{__('user.Descending')}}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row">

                @if ($services->count() > 0)
                    @foreach ($services as $service)
                        @if ($active_theme == 'layout2')
                            <div class="col-xl-4 col-md-6 col-lg-4">
                                <div class="wsus__single_services2">
                                    <div class="wsus__services_img2">
                                        <img src="{{ asset($service->image) }}" alt="service" class="img-fluid w-100">
                                        <a class="category" href="{{ route('services',['category'=> $service->category->slug]) }}">{{ $service->category->name }}</a>
                                    </div>
                                    <div class="wsus__services_text2">
                                        <img src="{{ $service->provider ? asset($service->provider->image) : '' }}" alt="user" class="img-fluid">
                                        <ul class="d-flex justify-content-between">
                                            <li>{{ $service->provider->name }}</li>

                                            @php
                                                $reviewQty = $service->totalReview;
                                                $totalReview = $service->averageRating;
                                                if ($reviewQty > 0) {
                                                    $average = $totalReview;
                                                    $intAverage = intval($average);
                                                    $nextValue = $intAverage + 1;
                                                    $reviewPoint = $intAverage;
                                                    $halfReview=false;
                                                    if($intAverage < $average && $average < $nextValue){
                                                        $reviewPoint= $intAverage + 0.5;
                                                        $halfReview=true;
                                                    }
                                                }
                                            @endphp

                                            <li>
                                                @if ($reviewQty > 0)
                                                    <p>
                                                        @for ($i = 1; $i <=5; $i++)
                                                            @if ($i <= $reviewPoint)
                                                                <i class="fas fa-star"></i>
                                                            @elseif ($i> $reviewPoint )
                                                                @if ($halfReview==true)
                                                                <i class="fas fa-star-half-alt"></i>
                                                                    @php
                                                                        $halfReview=false
                                                                    @endphp
                                                                @else
                                                                <i class="far fa-star"></i>
                                                                @endif
                                                            @endif
                                                        @endfor
                                                        <span>({{ $service->totalReview }})</span>
                                                    </p>
                                                @else
                                                    <p>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <span>({{ $service->totalReview }})</span>
                                                    </p>
                                                @endif
                                            </li>
                                        </ul>
                                        <a class="title" href="{{ route('service', $service->slug) }}">{{ $service->name }}</a>
                                        <div
                                            class="single_service_footer2 d-flex flex-wrap justify-content-between align-items-center">
                                            <span>{{ $currency_icon->icon }}{{ $service->price }}</span>
                                            <a class="common_btn2" href="{{ route('ready-to-booking', $service->slug) }}">{{__('user.Book now')}}</a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-xl-4 col-md-6 col-lg-4">
                                <div class="wsus__single_services">
                                    <div class="wsus__services_img">
                                        <img src="{{ asset($service->image) }}" alt="service" class="img-fluid w-100">
                                    </div>
                                    <div class="wsus__services_text">
                                        <ul class="d-flex justify-content-between">
                                            <li><a href="{{ route('services',['category'=> $service->category->slug]) }}">{{ $service->category->name }}</a></li>
                                            <li>{{ $currency_icon->icon }}{{ $service->price }}</li>
                                        </ul>
                                        <a class="title" href="{{ route('service', $service->slug) }}">{{ $service->name }}</a>
                                        <div
                                            class="single_service_footer d-flex flex-wrap justify-content-between align-items-center">
                                            <div class="img_area">
                                                <img src="{{ $service->provider ? asset($service->provider->image) : '' }}" alt="user" class="img-fluid">
                                                <span>{{ $service->provider->name }}</span>
                                            </div>

                                            @php
                                                $reviewQty = $service->totalReview;
                                                $totalReview = $service->averageRating;
                                                if ($reviewQty > 0) {
                                                    $average = $totalReview;
                                                    $intAverage = intval($average);
                                                    $nextValue = $intAverage + 1;
                                                    $reviewPoint = $intAverage;
                                                    $halfReview=false;
                                                    if($intAverage < $average && $average < $nextValue){
                                                        $reviewPoint= $intAverage + 0.5;
                                                        $halfReview=true;
                                                    }
                                                }
                                            @endphp

                                            @if ($reviewQty > 0)
                                                <p>
                                                    @for ($i = 1; $i <=5; $i++)
                                                        @if ($i <= $reviewPoint)
                                                            <i class="fas fa-star"></i>
                                                        @elseif ($i> $reviewPoint )
                                                            @if ($halfReview==true)
                                                            <i class="fas fa-star-half-alt"></i>
                                                                @php
                                                                    $halfReview=false
                                                                @endphp
                                                            @else
                                                            <i class="far fa-star"></i>
                                                            @endif
                                                        @endif
                                                    @endfor
                                                    <span>({{ $service->totalReview }})</span>
                                                </p>
                                            @else
                                                <p>
                                                    <i class="far fa-star"></i>
                                                    <i class="far fa-star"></i>
                                                    <i class="far fa-star"></i>
                                                    <i class="far fa-star"></i>
                                                    <i class="far fa-star"></i>
                                                    <span>({{ $service->totalReview }})</span>
                                                </p>
                                            @endif
                                        </div>
                                        <a class="common_btn" href="{{ route('ready-to-booking', $service->slug) }}">{{__('user.Book now')}}</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    {{ $services->links('custom_pagination') }}
                @else
                    <div class="col-12 text-center text-danger">
                        <h3>{{__('user.Service Not Found')}}</h3>
                    </div>
                @endif
            </div>

            @if ($partner_visbility)
            <div class="row mt_75 xs_mt_45">
                <div class="col-12">
                    <div class="wsus__brand_list">
                        <div class="row justify-content-center">
                            @foreach ($partners as $partner)
                                <div class="col-xl-2 col-sm-6 col-md-4 col-lg-3">
                                    <div class="wsus__single_brand">
                                        <a href="{{ $partner->link ? $partner->link : 'javascript:;' }}">
                                            <img src="{{ asset($partner->logo) }}" alt="brand" class="img-fluid w-100">
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
    <!--=========================
        SERVICES END
    ==========================-->




<script>
    (function($) {
        "use strict";
        $(document).ready(function () {
            $(".search_service_item").on("change",function(){
                $("#search_service_form").submit();
            })
        });
    })(jQuery);



</script>
@endsection
