@extends('tenant.layouts.app')

@section('content')

    <tenant-doctors-index :type-user="{{ json_encode(auth()->user()->type) }}"></tenant-doctors-index>

@endsection
