
<!-- Required JS -->
    <script src="{{ asset('frontend/assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/jquery-ui.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/select2.min.js') }}"></script>

    <script src="{{ asset('backend/assets/js/parsley.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/toastr.min.js') }}"></script>

    {{-- @if (request()->routeIs('home')) --}}
     <script src="{{ asset('frontend/assets/owlcarousel/js/owl.carousel.min.js') }}"></script>
     <script src="{{ asset('frontend/assets/js/magnific-popup.min.js') }}"></script>
     <!-- waypoints min js  -->
     <script src="{{ asset('frontend/assets/js/waypoints.min.js') }}"></script>
     <!-- parallax js  -->
     <script src="{{ asset('frontend/assets/js/parallax.js') }}"></script>
     <!-- countdown js  -->
     <script src="{{ asset('frontend/assets/js/jquery.countdown.min.js') }}"></script>
     <!-- imagesloaded js -->
     <script src="{{ asset('frontend/assets/js/imagesloaded.pkgd.min.js') }}"></script>
     <!-- isotope min js -->
     <script src="{{ asset('frontend/assets/js/isotope.min.js') }}"></script>
     <!-- jquery.dd.min js -->
     <script src="{{ asset('frontend/assets/js/jquery.dd.min.js') }}"></script>
     <!-- slick js -->
     <script src="{{ asset('frontend/assets/js/slick.min.js') }}"></script>
     <!-- elevatezoom js -->
     <script src="{{ asset('frontend/assets/js/jquery.elevatezoom.js') }}"></script>
     {{-- <script src="{{asset('frontend/assets/js/events-disable.js')}}"></script> --}}
    {{-- @endif     --}}
    <!-- Optional JS -->
    <script>
        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @elseif(session('error'))
            toastr.error("{{ session('error') }}");
        @elseif(session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif
    </script>

<script src="{{ asset('frontend/assets/js/scripts.js') }}"></script>
    @stack('scripts')
