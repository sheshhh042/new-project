<!-- resources/views/profile.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Profile Page</h2>
    <p>Name: {{ auth()->user()->name }}</p>
    <p>Email: {{ auth()->user()->email }}</p>
    <a href="{{ route('profile.settings') }}">Edit Profile</a>
</div>
@endsection
