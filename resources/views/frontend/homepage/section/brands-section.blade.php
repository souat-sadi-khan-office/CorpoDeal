<div class="col-md-12">
    <section class="section pb-0">
        <div class="row">
            <div class="col-md-12">
                <div class="heading_tab_header">
                    <div class="heading_s2">
                        <h4>Top Brands</h4>
                    </div>
                    <div class="view_all">
                        <a href="{{ route('brands') }}" class="text_default"><span>View All</span></a>
                    </div>
                </div>
            </div>
            <div class="col-12" style="height: 125px !important;">
                <div class="brand-carousel carousel_slider owl-carousel owl-theme dot_style1" data-dots="false" data-nav="false" data-margin="30" data-loop="true" data-autoplay="true" data-responsive='{"0":{"items": "2"}, "480":{"items": "3"}, "767":{"items": "3"}, "991":{"items": "4"}, "1199":{"items": "5"}}'>
                    @foreach ($brands as $brand)
                        <div class="item">
                            <div class="cl_logo">
                                <a href="{{ $brand['slug'] }}">
                                    @if (isset($brand['logo']))
                                        <img class="brand-image" src="{{ isset($brand['logo']) ? asset($brand['logo']) : asset('pictures/placeholder.jpg') }}" alt="{{ $brand['name'] }}"/>
                                    @else
                                        <div class="brand-name">
                                            {{ $brand['name'] }}
                                        </div>
                                    @endif
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>