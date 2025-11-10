
@extends('layout3')
@section('title')
    <title>{{ $seo_setting->seo_title }}</title>
@endsection

@section('title')
    <meta name="description" content="{{ $seo_setting->seo_description }}">
@endsection
@section('frontend-content')

@if ($intro_visibility)
    <!--=========================
        BANNER START
    ==========================-->
    <section class="wsus__banner" style="background: url({{ asset($intro_section->home3_image) }});">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-xl-7 col-md-12 col-lg-7">
                    <div class="wsus__banner_text">
                        <h6>{{ $intro_section->title }}</h6>
                        <h1>{{ $intro_section->header_one }} <b>{{ $intro_section->header_two }}</b></h1>
                        <form action="{{ route('services') }}">
                            <ul class="wsus__banner_search d-flex flex-wrap">
                                <li>
                                    <p>{{__('user.I am looking to')}}..</p>
                                    <select name="service_area" class="select_2">
                                        <option value="">{{__('user.Location')}}</option>
                                        @foreach ($service_areas as $service_area)
                                        <option value="{{ $service_area->id }}">{{ $service_area->name }}</option>
                                        @endforeach
                                    </select>
                                </li>
                                <li>
                                    <p>{{__('user.I am looking to')}}..</p>
                                    <select name="category" class="select_2">
                                        <option value="">{{__('user.Find Categories')}}</option>
                                        @foreach ($search_categories as $category)
                                        <option value="{{ $category->slug }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </li>
                                <li class="input_area">
                                    <button type="submit" class="common_btn2">{{__('user.search')}}</button>
                                </li>
                            </ul>
                        </form>
                        @if (count($popular_tag) > 0)
                            <div class="banner_tag d-flex flex-wrap align-items-center mt_50">
                                <span>{{__('user.Popular Searches')}}</span>
                                <ul class="d-flex flex-wrap align-items-center">
                                    @foreach ($popular_tag as $tag)
                                        <li><a href="{{ route('services', ['search' => $tag->value]) }}">{{ $tag->value }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--=========================
        BANNER END
    ==========================-->
@endif


@if ($partner_visbility)
    <!--=========================
        BRAND SLIDER END
    ==========================-->
    <section class="wsus__home3_brand mt_75 xs_mt_45">
        <div class="container">
            <div class="row">
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
        </div>
    </section>
    <!--=========================
        BRAND SLIDER END
    ==========================-->

    @endif

@if ($category_section->visibility)
    <!--=========================
        CATEGORIES START
    ==========================-->
    <section class="wsus__categories mt_90 xs_mt_60">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 m-auto">
                    <div class="wsus__section_heading text-center mb_45">
                        <h2>{{ $category_section->title }}</h2>
                        <p>{{ $category_section->description }}</p>
                    </div>
                </div>
            </div>
            <div class="row category_slider3">
                @foreach ($categories as $category)
                    <div class="col-xl-2">
                        <div class="wsus__single_categories">
                            <div class="wsus__single_cat_img">
                                <img src="{{ asset($category->image) }}" alt="category" class="img-fluid w-100">
                            </div>
                            <span>
                                <img src="{{ asset($category->icon) }}" alt="categories" class="img-fluid w-100">
                            </span>
                            <a href="{{ route('services',['category' => $category->slug]) }}">{{ $category->name }}</a>
                            <p>{{ $category->totalService }}+ {{__('user.Services')}}</p>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>
    <!--=========================
        CATEGORIES END
    ==========================-->
@endif

@if ($featured_service_section->visibility)
    <!--=========================
        FEATURED SERVICES START
    ==========================-->
    <section class="wsus__features_services wsus__features_services_3 mt_100 xs_mt_70 pt_90 xs_pt_60 mb_100 xs_mb_70">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 m-auto">
                    <div class="wsus__section_heading text-center mb_45">
                        <h2>{{ $featured_service_section->title }}</h2>
                        <p>{{ $featured_service_section->description }}</p>
                    </div>
                </div>
            </div>
            <div class="row featured_service_slider3">
                @foreach ($featured_services as $featured_service)
                    <div class="col-xl-4">
                        <div class="wsus__single_services">
                            <div class="wsus__services_img">
                                <img src="{{ asset($featured_service->image) }}" alt="service" class="img-fluid w-100">
                            </div>
                            <div class="wsus__services_text">
                                <ul class="d-flex justify-content-between">
                                    <li><a href="{{ route('services',['category'=> $featured_service->category->slug]) }}">{{ $featured_service->category->name }}</a></li>
                                    <li>{{ $currency_icon->icon }}{{ $featured_service->price }}</li>
                                </ul>
                                <a class="title" href="{{ route('service', $featured_service->slug) }}">{{ $featured_service->name }}</a>
                                <div
                                    class="single_service_footer d-flex flex-wrap justify-content-between align-items-center">
                                    <div class="img_area">
                                        <img src="{{ $featured_service->provider ? asset($featured_service->provider->image) : '' }}" alt="user" class="img-fluid">
                                        <span>{{ $featured_service->provider->name }}</span>
                                    </div>
                                    @php
                                        $reviewQty = $featured_service->totalReview;
                                        $totalReview = $featured_service->averageRating;
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
                                            <span>({{ $featured_service->totalReview }})</span>
                                        </p>
                                    @else
                                        <p>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <span>({{ $featured_service->totalReview }})</span>
                                        </p>
                                    @endif
                                </div>
                                <a class="common_btn" href="{{ route('ready-to-booking', $featured_service->slug) }}">{{__('user.Book now')}}</a>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>
    <!--=========================
        FEATURED SERVICES END
    ==========================-->
    @endif

@if ($coundown_visibility)
    <!--=========================
        COUNTER START
    ==========================-->
    <section class="wsus__counter">
        <div class="container">
            <div class="wsus__counter_3 pt_70 pb_65">
                <div class="row">
                    @foreach ($counters as $counter)
                        <div class="col-xl-3 col-sm-6 col-lg-3">
                            <div class="wsus__single_counter">
                                <span>
                                    <img src="{{ asset($counter->icon) }}" alt="counter" class="img-fluid w-100">
                                </span>
                                <h4 class="counter">{{ $counter->number }}</h4>
                                <p>{{ $counter->title }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!--=========================
        COUNTER END
    ==========================-->
@endif

@if ($popular_service_section->visibility)
    <!--=========================
        POPULAR SERVICES START
    ==========================-->
    <section class="wsus__popular_services pt_90 xs_pt_60 pb_100 xs_pb_70">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 m-auto">
                    <div class="wsus__section_heading text-center mb_20">
                        <h2>{{ $popular_service_section->title }}</h2>
                        <p>{{ $popular_service_section->description }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($popular_services as $popular_service)
                    <div class="col-lg-4 col-md-6">
                        <div class="wsus__single_services">
                            <div class="wsus__services_img">
                                <img src="{{ asset($popular_service->image) }}" alt="service" class="img-fluid w-100">
                            </div>
                            <div class="wsus__services_text">
                                <ul class="d-flex justify-content-between">
                                    <li><a href="{{ route('services',['category' => $popular_service->category->slug]) }}">{{ $popular_service->category->name }}</a></li>
                                    <li>{{ $currency_icon->icon }}{{ $popular_service->price }}</li>
                                </ul>
                                <a class="title" href="{{ route('service', $popular_service->slug) }}">{{ $popular_service->name }}</a>
                                <div
                                    class="single_service_footer d-flex flex-wrap justify-content-between align-items-center">
                                    <div class="img_area">
                                        <img src="{{ $popular_service->provider ? asset($popular_service->provider->image) : '' }}" alt="user" class="img-fluid">
                                        <span>{{ $popular_service->provider->name }}</span>
                                    </div>

                                    @php
                                        $reviewQty = $popular_service->totalReview;
                                        $totalReview = $popular_service->averageRating;
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
                                            <span>({{ $popular_service->totalReview }})</span>
                                        </p>
                                    @else
                                        <p>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <span>({{ $popular_service->totalReview }})</span>
                                        </p>
                                    @endif

                                </div>
                                <a class="common_btn" href="{{ route('ready-to-booking', $featured_service->slug) }}">{{__('user.Book now')}}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--=========================
        POPULAR SERVICES END
    ==========================-->
    @endif

    @if ($work_visbility)
    <!--=========================
        WORK START
    ==========================-->
    <section class="wsus__work_sectiion pt_100 xs_pt_70 pb_100 xs_pb_70"
        style="background: url({{ asset($how_it_work->background) }});">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-xl-6 col-md-9 col-lg-6">
                    <div class="wsus__work_sectiion_text">
                        <h2>{{ $how_it_work->title }}</h2>
                        <p>{{ $how_it_work->description }}</p>
                        <ul>
                            @foreach ($how_it_work->items as $index => $item)
                                <li>
                                    <span>{{ ++$index }}</span>
                                    <h3>{{ $item->title }}</h3>
                                    <p>{{ $item->description }}</p>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-5">
                    <div class="wsus__work_sectiion_img">
                        <img src="{{ asset($how_it_work->foreground) }}" alt="work" class="img-fluid w-100">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--=========================
        WORK END
    ==========================-->
    @endif

    @if ($mobile_app_section_visbility)
    <!--=========================
        APP DOWNLOAD START
    ==========================-->
    <section class="wsus__app_download mt_100 xs_mt_70">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-xl-5 col-md-7">
                    <div class="wsus__app_download_text">
                        <h5>{{ $mobile_app->short_title }}</h5>
                        <h2>{{ $mobile_app->full_title }}</h2>
                        <p>{{ $mobile_app->description }}</p>
                        <ul class="d-flex flex-wrap">
                            <li>
                                <a href="{{ $mobile_app->play_store }}">
                                    <i class="fab fa-google-play"></i>
                                    <span>{{__('user.Available on the')}} <b>{{__('user.Google Play')}}</b></span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ $mobile_app->app_store }}">
                                    <i class="fab fa-apple"></i>
                                    <span>{{__('user.Download on the')}} <b>{{__('user.App Store')}}</b></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-5 col-md-5">
                    <div class="wsus__app_download_img">
                        <img src="{{ asset($mobile_app->home3_app_image) }}" alt="app download" class="img-fluid w-100">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--=========================
        APP DOWNLOAD END
    ==========================-->

    @endif

    @if ($join_as_provider_visibility)

    <!--=========================
        SELLAR JOIN START
    ==========================-->
    <section class="wsus__seller_join mt_100 xs_mt_70">
        <div class="container">
            <div class="wsus__sellar_bg  pt_75 xs_pt_45 pb_80 xs_pb_50"
                style="background: url({{ asset($join_as_a_provider->home3_image) }});">
                <div class="row">
                    <div class="col-lg-8 m-auto">
                        <div class="wsus__seller_join_text text-center">
                            <h3>{{ $join_as_a_provider->title }}</h3>
                            <a href="{{ route('join-as-a-provider') }}">{{ $join_as_a_provider->button_text }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--=========================
        SELLAR JOIN END
    ==========================-->
@endif


@if ($testimonial_section->visibility)
    <!--=========================
        TESTIMONIAL START
    ==========================-->
    <section class="wsus__testimonial pt_90 xs_pt_60 pb_100 xs_pb_70"
        style="background: url({{ asset('frontend/images/testimonial_bg.jpg') }});">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 m-auto">
                    <div class="wsus__section_heading text-center mb_45">
                        <h2>{{ $testimonial_section->title }}</h2>
                        <p>{{ $testimonial_section->description }}</p>
                    </div>
                </div>
            </div>
            <div class="row testi_slider">
                @foreach ($testimonials as $testimonial)
                    <div class="col-xl-6">
                        <div class="wsus__single_testimonial">
                            <p class="review_text">{{ $testimonial->comment }}</p>
                            <div class="wsus__single_testimonial_img">
                                <img src="{{ asset($testimonial->image) }}" alt="clients" class="img-fluid">
                                <p><span>{{ $testimonial->name }}</span> {{ $testimonial->designation }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--=========================
        TESTIMONIAL END
    ==========================-->
    @endif


    @if ($blog_section->visibility)
    <!--=========================
        BLOG START
    ==========================-->
    <section class="wsus__blog mt_85 xs_mt_55">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 m-auto">
                    <div class="wsus__section_heading text-center mb_20">
                        <h2>{{ $blog_section->title }}</h2>
                        <p>{{ $blog_section->description }}</p>
                    </div>
                </div>
            </div>
            <div class="row">

                @foreach ($blogs as $blog)
                    <div class="col-lg-4 col-md-6">
                        <div class="wsus__single_blog">
                            <div class="wsus__single_blog_img">
                                <img src="{{ asset($blog->image) }}" alt="blog" class="img-fluid w-100">
                            </div>
                            <div class="wsus__single_blog_text">
                                <ul class="d-flex flex-wrap">
                                    <li><i class="far fa-user"></i> {{__('user.By Admin')}}</li>
                                    <li><i class="far fa-comment-alt-lines"></i> {{ $blog->total_comment }}{{__('user. Comments')}}</li>
                                </ul>
                                <a class="title" href="{{ route('blog', $blog->slug) }}">{{ $blog->title }}</a>
                                <a class="read_btn" href="{{ route('blog', $blog->slug) }}">{{__('user.Learn More')}} <i class="far fa-long-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--=========================
        BLOG END
    ==========================-->
    @endif


    @if ($subscription_visbility)
    <!--=========================
        SUBSCRIBE START
    ==========================-->
    <section class="wsus__subscribe mt_100 xs_mt_70" style="background: url({{ asset($subscriber->home3_background_image) }});">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6">
                    <div class="wsus__subscribe_text pt_90 xs_pt_60 pb_100 xs_pb_70">
                        <h3>{{ $subscriber->title }}</h3>
                        <p>{{ $subscriber->description }}</p>
                        <form id="subscriberForm">
                            @csrf
                            <input type="email" placeholder="{{__('user.Your Email')}}" name="email">
                            <button id="subscribe_btn" type="submit" class="common_btn">{{__('user.Subscribe')}}</button>
                        </form>
                    </div>
                </div>
                <div class="col-xl-5 col-md-6 d-none d-lg-block">
                    <div class="wsus__subscribe_img">
                        <img src="{{ asset($subscriber->foreground_image) }}" alt="subecribe" class="img-fluid w-100">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--=========================
        SUBSCRIBE END
    ==========================-->

    @endif


    <script>
        (function($) {
            "use strict";
            $(document).ready(function () {
                $("#subscriberForm").on('submit', function(e){
                    e.preventDefault();
                    var isDemo = "{{ env('APP_MODE') }}"
                    if(isDemo == 'DEMO'){
                        toastr.error('This Is Demo Version. You Can Not Change Anything');
                        return;
                    }

                    let loading = "{{__('user.Processing...')}}"

                    $("#subscribe_btn").html(loading);
                    $("#subscribe_btn").attr('disabled',true);

                    $.ajax({
                        type: 'POST',
                        data: $('#subscriberForm').serialize(),
                        url: "{{ route('subscribe-request') }}",
                        success: function (response) {
                            if(response.status == 1){
                                toastr.success(response.message);
                                let subscribe = "{{__('user.Subscribe')}}"
                                $("#subscribe_btn").html(subscribe);
                                $("#subscribe_btn").attr('disabled',false);
                                $("#subscriberForm").trigger("reset");
                            }

                            if(response.status == 0){
                                toastr.error(response.message);
                                let subscribe = "{{__('user.Subscribe')}}"
                                $("#subscribe_btn").html(subscribe);
                                $("#subscribe_btn").attr('disabled',false);
                                $("#subscriberForm").trigger("reset");
                            }
                        },
                        error: function(err) {
                            toastr.error('Something went wrong');
                            let subscribe = "{{__('user.Subscribe')}}"
                                $("#subscribe_btn").html(subscribe);
                                $("#subscribe_btn").attr('disabled',false);
                                $("#subscriberForm").trigger("reset");
                        }
                    });
                })

            });
        })(jQuery);


    </script>
@endsection
