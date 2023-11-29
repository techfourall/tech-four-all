<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('styles.css') }}">

    {{--<script src="/javascripts/jquery-3.6.0.min.js" type="text/javascript"></script>--}}

</head>

{{--<body class="hold-transition skin-blue sidebar-mini">--}}
<body class="sidebar-mini layout-fixed layout-navbar-fixed">



<div class="wrapper">
    @include('common.status')
    @include('layouts.main-nav-header')
    {{--@include('layouts.main-side-bar')--}}
    <div class="content-wrapper-guest">
        @yield('content')

    </div>

    @include('layouts.main-footer')
    {{--@include('dashboard.layouts.main-footer')--}}


</div>

@include('layouts.main-scripts')

</body>

</html>

{{-- TO USE THIS LAYOUT--}}
{{--@extends('layouts.main')--}}

{{--@section('title', 'Home Page')--}}

{{--@section('content')--}}
    {{--<div class="container form-wrapper">--}}
        {{--<div class="col-md-12">--}}
            {{--<span>Hello Baba</span>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--@endsection--}}
