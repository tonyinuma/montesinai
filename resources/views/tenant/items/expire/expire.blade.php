@extends('tenant.layouts.app')

@section('content')
    <tenant-items-expire :type-user="{{json_encode(Auth::user()->type)}}"></tenant-items-expire>
@endsection
