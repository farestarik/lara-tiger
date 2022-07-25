@extends('errors::minimal')


@section('title', $exception->getMessage() ?: __('site.contact_support'))
@section('code', '500')
@section('message', $exception->getMessage() ?: __('site.contact_support'))
