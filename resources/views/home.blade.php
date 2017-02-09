@extends('layouts/app')

@section('title')
    Welcome
@endsection

@section('content')
    Hello {{ Auth::user()->name }} ({{ Auth::user()->provider->display_name }})
@endsection
