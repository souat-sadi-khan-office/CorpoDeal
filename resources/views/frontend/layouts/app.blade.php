@php
   $main_categories = App\Models\Category::getCategoriesWithChildren();
@endphp
<!DOCTYPE html>
<html lang="en">
   @include('frontend.layouts.partials.head')
   <body>
      @if (Request::is('/'))
         <input type="hidden" id="isHomePage" value="1">
      @else
         <input type="hidden" id="isHomePage" value="0">
      @endif

      @include('frontend.layouts.partials.preloader')

      <header class="header_wrap fixed-top header_with_topbar ">
         @include('frontend.layouts.partials.topbar')

         @include('frontend.layouts.partials.topnav')

         @include('frontend.layouts.partials.navbar')
      </header>

      @stack('breadcrumb')

      @yield('content')

      @include('frontend.layouts.partials.footer')

      @include('frontend.layouts.partials.footerlinks')

      <div class="overlay-loader"></div>

      <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
         <div class="offcanvas-header border-bottom">
            <div class="text-start">
               <h5 id="offcanvasRightLabel" class="mb-0 fs-4">Shop Cart</h5>
            </div>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
         </div>
         <div class="offcanvas-body">
            <ul class="list-group list-group-flush cart-content"></ul>
         </div>
         <div class="offcanvas-footer">
            <div class="footer">
               <div class="promotion-code"></div>

               <div class="total">
                  <div class="title">Sub-Total</div>
                  <div class="amount">SG 0</div>
               </div>

               <div class="total">
                  <div class="title">Total</div>
                  <div class="amount">SG 0</div>
               </div>

               <div class="checkout-btn">
                  <a title="Go to cart page" href="{{ route('cart') }}">
                     <button type="button" class="btn submit">
                        Go To Cart
                     </button>
                  </a>
               </div>
            </div>
         </div>
      </div>

      <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasSearch" aria-labelledby="offcanvasRightLabel">
         <div class="offcanvas-header border-bottom">
            <div class="text-start">
               <h5 id="offcanvasRightLabel" class="mb-0 fs-4">Search</h5>
            </div>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
         </div>
         <div class="offcanvas-body">
            <form class="mb-3" action="{{ route('search') }}" method="GET">
               <div style="display: flex; align-items: center;">
                  <input
                     class="form-control mobile-search"
                     style="flex: 1; padding: 10px; font-size: 12px;"
                     autocomplete="off"
                     placeholder="Search"
                     required
                     id="mobile-search"
                     name="search"
                     type="text">
                  <button
                     type="submit"
                     class="btn btn-fill-out"
                     style="padding: 7px 10px; margin-left: 5px;margin-top:10px; cursor: pointer;">
                     <i class="linearicons-magnifier" style="font-size:12px;margin-right:0px;"></i>
                  </button>
               </div>
               <audio id="notificationAudio" src="{{ asset('notifications/recording.mp3') }}"></audio>
            </form>

            <div class="typed-search-box">
               <div class="searching-preloader d-none">
                  <div class="search-preloader">
                     <div class="lds-ellipsis">
                        <span></span>
                        <span></span>
                        <span></span>
                     </div>
                  </div>
               </div>
               <div class="search-nothing d-none p-3 text-center fs-16">

               </div>
               <div class="search-content text-left">
                  <div class="">

                  </div>
                  <div class="">

                  </div>
               </div>
            </div>

         </div>
      </div>

      <div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="false" style="display: none;">
         <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
               <div class="modal-body p-6">
                  <div class="d-flex justify-content-between align-items-start">
                     <div>
                        <h5 class="mb-1" id="locationModalLabel">Choose your Delivery Location</h5>
                        <p class="mb-0 small">Select your address and we will specify the offer you area.</p>
                     </div>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  <!-- <div class="my-5">
                     <input type="search" class="form-control" placeholder="Search your area">
                  </div> -->

                  <!-- <div class="d-flex justify-content-between align-items-center my-5">
                     <h6 class="mb-0">Select Location</h6>

                     <span class="location-loader" style="display: none;">
                        <i class="fas fa-spinner fa-spin" style="font-size: 15px;"></i>
                     </span>
                  </div> -->

                  <div class="mt-2">
                     <div data-simplebar="init" style="max-height: 240px" class="">
                        <div class="simplebar-wrapper" style="margin: 0px;overflow-y:scroll">
                           <div class="simplebar-content" style="padding: 0px;">
                              <div class="list-group list-group-flush">
                                 @php
                                    $countries = App\Models\Country::where('status', 1)->orderBy('name', 'ASC')->get();
                                 @endphp
                                 @foreach ($countries as $country)
                                    <a style="padding-left: 10px;" href="javascript:;" data-id="{{ $country->id }}" class="change-global-method list-group-item {{ session()->get('user_country') ? (session()->get('user_country') == $country->name ? 'active' : '') : '' }}">
                                       <i class="fas fa-map-marker-alt"></i>
                                       <span>{{ $country->name }}</span>
                                    </a>
                                 @endforeach
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

      @if (!session()->has('user_city_selected'))
         @php
            $userCountry = session()->get('user_country');
            $exists = App\Models\Country::where('name', session()->get('user_country'))->first();
         @endphp
         @if (!$exists)
            <script>
               $(document).ready(function() {
                  $('#locationModal').modal('show');
               });
            </script>
         @endif
      @endif
   </body>
</html>
