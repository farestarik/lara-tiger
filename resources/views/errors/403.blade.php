@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))


<script>
    let dashboardRoute = "{{url('/')}}";
    setTimeout(function(){ window.location.href = dashboardRoute; }, 2000);
 </script>
