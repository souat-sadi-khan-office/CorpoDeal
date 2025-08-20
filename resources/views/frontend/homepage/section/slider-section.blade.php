<div class="col-md-12">
    <div class="section pb-0">
        <div class="row">
            <div class="col-12">
                <div class="heading_tab_header">
                    <div class="heading_s2">
                        <h4>Exclusive Products</h4>
                    </div>
                    <div class="tab-style1">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#tabmenubar" aria-expanded="false">
                            <span class="fas fa-bars"></span>
                        </button>
                        <ul class="nav nav-tabs justify-content-center justify-content-md-end" id="tabmenubar" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="arrival-tab" data-bs-toggle="tab" href="#arrival" role="tab" aria-controls="arrival" aria-selected="true">
                                    New Arrival
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="sellers-tab" data-bs-toggle="tab" href="#sellers" role="tab" aria-controls="sellers" aria-selected="false">
                                    Best Sellers
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="featured-tab" data-bs-toggle="tab" href="#featured" role="tab" aria-controls="featured" aria-selected="false">
                                    Featured
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="special-tab" data-bs-toggle="tab" href="#special" role="tab" aria-controls="special" aria-selected="false">
                                    Special Offer
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="tab_slider">
                    <div class="tab-pane fade show active" id="arrival" role="tabpanel" aria-labelledby="arrival-tab">
                        <div class="new-arrival-products carousel_slider owl-carousel owl-theme" data-loop="true" data-nav="false" data-dots="false" data-margin="20" data-autoplay="true" data-responsive='{"0":{"items": "1"}, "481":{"items": "2"}, "768":{"items": "4"}, "991":{"items": "5"}}'>
                            @foreach ($newProducts as $product)
                                <div class="item">
                                    @include('frontend.components.product_main', ['listing' => 'section_wise'])
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane exclusive-products fade" id="sellers" role="tabpanel"></div>
                    <div class="tab-pane exclusive-products fade" id="featured" role="tabpanel"></div>
                    <div class="tab-pane exclusive-products fade" id="special" role="tabpanel"></div>
                </div>
            </div>
        </div>
    </div>
</div>