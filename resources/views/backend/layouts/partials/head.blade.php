<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>@yield('title') | {{ get_settings('system_name') ? get_settings('system_name') : 'Project Alpha' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <link rel="stylesheet" href="{{ asset('backend/assets/css/font_source_sans3.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/overlayscrollbars.min.css') }}">
    <link rel="stylesheet" href="{{asset('backend/assets/css/bootstrap-icons.min.css')}}">
    <link rel="stylesheet" href={{ asset('backend/assets/css/customGredient.css') }}>
    <link rel="stylesheet" href={{ asset('backend/assets/css/adminlte.css') }}>
    @stack('styleforIconPicker')
    <link rel="stylesheet" href={{ asset('backend/assets/css/fontawesome.min.css') }}>
    <link rel="stylesheet" href="{{ asset('backend/assets/css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/parsley.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/custom-animate.css') }}">
    <link rel='stylesheet' href="{{asset('backend/assets/css/uicons-regular-rounded.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('style')
</head>
