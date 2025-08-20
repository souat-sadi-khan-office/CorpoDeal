<div class="col-12">
    @if (get_settings('show_star_banner'))
        <section class="section pb-0">
            <div class="bg-light d-lg-flex justify-content-between align-items-center py-6 py-lg-3 px-8 text-center text-lg-start rounded">
                <div class="d-lg-flex align-items-center">
                    <img src="{{ get_settings('star_image') ? asset(get_settings('star_image')) : asset('frontend/assets/images/about-icons-1.svg') }}" alt="" class="img-fluid">
                    <div class="ms-lg-4">
                        <h1 class="fs-2 mb-1">{{ get_settings('star_header') }}</h1>
                        <span>
                            {{ get_settings('star_content') }}
                        </span>
                    </div>
                </div>
                <div class="mt-3 mt-lg-0">
                    <a target="{{ get_settings('star_url_open_in_another_tab') == 1 ? '_blank' : '_self' }}" href="{{ get_settings('star_button_url') }}" class="btn btn-fill-out">
                        {{ get_settings('star_button_text') }}
                    </a>
                </div>
            </div>
        </section>
    @endif
    

    @if (isset($midBanners) && count($midBanners) > 0)
        <section class="mt-8 mb-0">
            <div class="table-responsive-xl pb-6 pb-xl-0">
                <div class="row flex-nowrap">
                    @foreach ($midBanners['mid'] as $banner)
                        <div class="col-12 col-xl-4 col-lg-6">
                            <div class="p-8 mb-3 rounded" style="background: url('{{ asset($banner->image) }}') no-repeat; background-size: cover">
                                <div>
                                    <h3 class="mb-0 fw-bold  text-white ">
                                        {!! nl2br($banner->header_title) !!}
                                    </h3>
                                    <div class="mt-4 mb-5 fs-5 text-white ">
                                        {!! nl2br($banner->name) !!}
                                    </div>
                                    <a href="{{ $banner->link }}" class="btn btn-light">Shop Now</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>

