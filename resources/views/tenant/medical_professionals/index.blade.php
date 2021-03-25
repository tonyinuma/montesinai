@extends('tenant.layouts.app')

@section('content')

    <tenant-medical_professionals-index :type-user="{{ json_encode(auth()->user()->type) }}"></tenant-medical_professionals-index>

@endsection
