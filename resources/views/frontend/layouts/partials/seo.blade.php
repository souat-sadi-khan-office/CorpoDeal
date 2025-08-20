
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<link rel="shortcut icon" type="image/x-icon" href="{{ get_settings('system_favicon') ? asset(get_settings('system_favicon')) : asset('pictures/default-favicon.png') }}">
	<link rel="apple-touch-icon" href="{{ get_settings('system_favicon') ? asset(get_settings('system_favicon')) : asset('pictures/default-favicon.png') }}">

    <title>{{ isset($title) ? $title : get_settings('system_name') }}</title>
