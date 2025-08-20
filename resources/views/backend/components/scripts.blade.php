<script src="{{ asset('backend/assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/color_mode.js') }}"></script>
<script src="{{ asset('backend/assets/js/overlayscrollbars.browser.es6.min.js') }}" ></script>
<script src="{{ asset('backend/assets/js/toastr.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/parsley.min.js') }}" ></script>
<script src="{{ asset('backend/assets/js/select2.min.js') }}" ></script>
<script src="{{asset('backend/assets/js/popper.min.js')}}" ></script>
<script src="{{asset('backend/assets/js/bootstrap.min.js')}}" ></script>
<script src="{{ asset('backend/assets/js/adminlte.js') }}"></script>
<script src="{{asset('backend/assets/js/sweetalert2@11.js')}}"></script>
<script src="{{ asset('backend/assets/js/main.js') }}"></script>
<script src="{{asset('backend/assets/js/pusher.min.js')}}"></script>
@if (session('notification'))
    <script>
        toastr["{{ session('notification')['type'] }}"]("{{ session('notification')['message'] }}");
    </script>
@endif

<script>
    const pusher = new Pusher('4091efd3d849c99939e4', {
        cluster: 'ap1',
        encrypted: true,
    });

    const channel = pusher.subscribe('orders');

    channel.bind('order-created', function(data) {
        // console.log('New Order Created:', data.order);

        // Play the notification sound
        var audio = new Audio("{{ asset('notifications/order.mp3') }}");
        audio.play();

        {{--Swal.fire({--}}
        {{--    title: 'New Order Created',--}}
        {{--    text: `Order ID: ${data.order.unique_id.toUpperCase()}`,--}}
        {{--    icon: 'info',--}}
        {{--    showCancelButton: true,--}}
        {{--    confirmButtonText: 'View',--}}
        {{--    cancelButtonText: 'Close'--}}
        {{--}).then((result) => {--}}
        {{--    if (result.isConfirmed) {--}}
        {{--        window.location.href = "{{ route('admin.order.index') }}";--}}
        {{--    }--}}
        {{--});--}}

        // Display a toastr notification
        toastr.info(`Order ID: ${data.order.unique_id.toUpperCase()}`, 'New Order Created', {
            timeOut: 5000,
            closeButton: true,
            progressBar: true,
            onclick: function() {
                window.location.href = "{{ route('admin.order.index') }}";
            }
        });
    });
</script>


<script>
    $(document).ready(function () {
        fetchNotifications();

        function fetchNotifications() {
            $.ajax({
                url: "{{ route('admin.notifications') }}",
                type: 'GET',
                dataType: 'json',
                success: function (notifications) {
                    const notificationList = $('#notificationList');
                    notificationList.empty();

                    let unreadCount = 0;

                    if (notifications.length === 0) {
                        notificationList.append('<li class="dropdown-item text-muted">No new notifications</li>');
                    } else {
                        notifications.forEach(notification => {
                            let isUnread = !notification.admin_read_at;
                            let isSystem = !notification.user_id;

                            if (isUnread) unreadCount++;

                            let notificationItem = `
                                <li class="dropdown-item d-flex justify-content-between align-items-center ${isUnread && !isSystem ? 'bg-light ' : (isUnread && isSystem?'bg-danger text-white':'')}">
                                    <div>
                                        <strong>${notification.message}</strong><br>
                                        <small class="text-muted">${new Date(notification.created_at).toLocaleString()}</small>
                                    </div>
                                    ${notification.go_to_link ? `<a href="${notification.go_to_link}" class="go-to-link btn btn-sm ${isUnread ? (isSystem?'btn-warning':'btn-dark') : 'btn-outline-secondary'}" data-id="${notification.id}">View</a>` : ''}
                                </li>
                            `;
                            notificationList.append(notificationItem);
                        });
                    }

                    // Update badge count
                    $('#notificationBadge').text(unreadCount > 0 ? unreadCount : '');
                }
            });
        }

        // Mark all as read
        $('#markAllAsRead').on('click', function () {
            $.ajax({
                url: "{{ route('admin.notification.read-all') }}", // Named route
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function () {
                    fetchNotifications();
                }
            });
        });

        // Mark single notification as read when clicking "View"
        $(document).on('click', '.go-to-link', function (e) {
            e.preventDefault();
            let notificationId = $(this).data('id');

            $.ajax({
                url: "{{ route('admin.notification.read', ':id') }}".replace(':id', notificationId),                 type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function () {
                    // fetchNotifications();
                    window.location.href = $(e.target).attr('href');
                }
            });
        });
    });
</script>


<script>
    // Function to update the clock
    function updateClock() {
        const clockElement = document.getElementById('clock');

        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

        const options = {
            timeZone: timezone,
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true,
        };

        const formatter = new Intl.DateTimeFormat('en-US', options);
        const currentTime = formatter.format(new Date());

        clockElement.textContent = `(${timezone}): ${currentTime}`;
    }

    setInterval(updateClock, 1000);

    updateClock();

</script>

<script>
    const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
    const Default = {
        scrollbarTheme: "os-theme-light",
        scrollbarAutoHide: "leave",
        scrollbarClickScroll: true,
    };
    document.addEventListener("DOMContentLoaded", function() {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (
            sidebarWrapper &&
            typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== "undefined"
        ) {
            OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                scrollbars: {
                    theme: Default.scrollbarTheme,
                    autoHide: Default.scrollbarAutoHide,
                    clickScroll: Default.scrollbarClickScroll,
                },
            });
        }
    });

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    toastr.options = {
        "positionClass": "{{ get_settings('system_notification_format') }} ",
        "preventDuplicates": true
    };

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
