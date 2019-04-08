@extends('layouts.app')

@section('content')
    <div class="jumbotron text-center">
        <h1>{{$title}}</h1>
        <p>This is my first time trying laravel so I have no idea what I am doing</p>
        <p> <a class="btn btn-primary btn-lg" href="/login" role="button">Login</a>
            <a class="btn btn-primary btn-lg" href="/register" role="button">Register</a>
        </p>
    </div>
@endsection