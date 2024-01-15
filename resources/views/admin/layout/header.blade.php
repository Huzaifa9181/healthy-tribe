<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{generalSetting()->title ?? ''}} | @yield('title')</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{url('public/admin/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{url('public/admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{url('public/admin/dist/css/adminlte.min.css')}}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{url('public/admin/plugins/summernote/summernote-bs4.min.css')}}">
  <!-- CodeMirror -->
  <link rel="stylesheet" href="{{url('public/admin/plugins/codemirror/codemirror.css')}}">
  <link rel="stylesheet" href="{{url('public/admin/plugins/codemirror/theme/monokai.css')}}">

  {{-- favicon --}}
  <link rel="apple-touch-icon" sizes="180x180" href="{{url('public/180x180.png')}}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{url('public/32x32.png')}}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{url('public/16x16.png')}}">
  <link rel="mask-icon" href="{{url('public/16x16.png')}}" color="#5bbad5">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">
</head>