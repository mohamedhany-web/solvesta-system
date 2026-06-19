@extends('layouts.app')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@section('content')
@if(!empty($is_admin_dashboard))
    @include('dashboard.partials.admin')
                                @else
    @include('dashboard.partials.role-default')
@endif
@endsection
