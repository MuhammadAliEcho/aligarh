@extends('layouts/errormaster')

@section('title', 'School Management System | 404 error')

@section('content')

    <div class="middle-box text-center animated fadeInDown">
        <h1>404</h1>
        <h3 class="font-bold">Page Not Found</h3>

        <div class="error-desc">
The page you requested could not be found, either contact your webmaster or try again. Use your browsers Back button to navigate to the page you have prevously come from
Or you could just press this neat little button:
            <br>
            <br>
                <a href="{{ URL('/') }}" class="btn btn-primary"> Back </a>
        </div>
    </div>
@endsection
