@extends('tenant.layouts.app')

@section('content')

    <tenant-medical_departments-index :type-user="{{ json_encode(auth()->user()->type) }}"></tenant-medical_departments-index>

@endsection
