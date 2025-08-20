@extends('backend.layouts.app')
@section('title', 'Categories')
@section('page_name', 'Categories')
@section('breadcrumb', 'Categories')
@push('style')
<link href="{{asset('backend/assets/css/fontawesome-iconpicker.min.css')}}" rel="stylesheet">
@endpush
@section('content')




@endsection
@push('script')
    <script src="{{asset('backend/assets/js/fontawesome-iconpicker.js')}}"></script>
   
    {{-- <script src="{{asset('backend/assets/js/dataTables.js')}}"></script>
    <script src="{{asset('backend/assets/js/dataTables.bootstrap5.js')}}"></script> --}}
@endpush