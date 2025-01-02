
@php
$banner = App\Models\Banner::orderBy('banner_title','ASC')->limit(3)->get();
@endphp

<section class="banners mb-25">
    <div class="container">
        <div class="row">
            @foreach($banner as $item)
            <div class="col-lg-4 col-md-6">
                <div class="banner-img wow animate__animated animate__fadeInUp" data-wow-delay="0">
                    <img src="{{ asset( $item->banner_image ) }}" alt="" />
                    <div class="banner-text">
                        <h4>
                            {!! implode('<br>', array_map(fn($chunk) => implode(' ', $chunk), array_chunk(explode(' ', $item->banner_title), 3))) !!}
                        </h4>
                        <a href="shop-grid-right.html" class="btn btn-xs">Shop Now <i class="fi-rs-arrow-small-right"></i></a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>