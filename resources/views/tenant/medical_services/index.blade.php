@extends('tenant.layouts.app')

@section('content')

    <tenant-medical_services-index :type-user="{{ json_encode(auth()->user()->type) }}"></tenant-medical_services-index>

@endsection
