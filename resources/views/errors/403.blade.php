@extends('layouts/errormaster')

@section('title', 'School Management System | 404 error')

@section('content')

@section('content')
    <div class="middle-box text-center animated fadeInDown">
        <h1>403</h1>
        <h3 class="font-bold">Forbidden</h3>

        <div class="error-desc">
            You do not have permission to access the page you requested. 
            Please contact your system administrator.
            <br>
            <br>
            <a href="{{ url('/') }}" class="btn btn-primary">Back to Home</a>
        </div>
    </div>
@endsection