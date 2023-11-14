@extends('question::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('question.name') !!}</p>
@endsection
