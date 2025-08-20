@include('backend.layouts.partials.head')
{{--<body class="layout-fixed sidebar-expand-lg sidebar-mini sidebar-collapse bg-body-tertiary">--}}
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        @include('backend.layouts.partials.navbar')
        @include('backend.layouts.partials.sidebar')


        <main class="app-main">
            @if (View::hasSection('page_name'))
                @yield('page_name')
            @endif

            <div class="app-content">
                <div class="container-fluid">

                    @yield('content')

                </div>
            </div>
        </main>
        @include('backend.layouts.partials.footer')

    </div>

    @if(isset($modal))
        <div id="modal_remote" class="modal fade border-top-success rounded-top-0" data-backdrop="static" role="dialog">
            <div class="modal-dialog modal-{{ $modal }} modal-dialog-centered">
                <div class="modal-content">

                </div>
            </div>
        </div>
    @endif

    <div id="search_modal" class="modal fade border-top-success rounded-top-0" data-backdrop="static" role="dialog">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-contents">
                <div class="modal-header">
                    <h5 class="modal-title">Search For Route</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        &times;
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search menu..." />
                        </div>

                        <div class="col-md-12 form-group" id="resultsContainer">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('backend.components.scripts')
    <script>
        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @elseif(session('error'))
            Swal.fire({
                title: 'Permission Denied!',
                html: '{{ session("error") }}',
                icon: 'error',
            });
        @elseif(session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif
    </script>
    @stack('script')
</body>
</html>
