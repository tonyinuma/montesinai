@extends('tenant.layouts.app')

@section('content')

    <tenant-appointments-index :type-user="{{ json_encode(auth()->user()->type) }}"></tenant-appointments-index>

@endsection
