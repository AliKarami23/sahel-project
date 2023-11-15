@extends('role::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('role.name') !!}</p>
@endsection
