@extends('tenant.layouts.app')

@section('content')

    <tenant-patients-index :type-user="{{ json_encode(auth()->user()->type) }}"></tenant-patients-index>

@endsection
