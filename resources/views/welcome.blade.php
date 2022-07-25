<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">


        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
		<link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
		<link href="{{ asset('/css/head.css') }}" rel="stylesheet">
		<link href="{{ asset('/css/signin.css') }}" rel="stylesheet">
	</head>
	<body class="text-center">
		<main class="form-signin w-100 m-auto">
			@if (Route::has('login'))
				<div>
					@auth
					<a class="btn btn-primary" href="{{ url('/home') }}">{{ __('Home') }}</a>
			@else
					<a class="btn btn-primary" href="{{ route('login') }}">{{ __('Log In') }}</a>
					@endauth
				</div>
			@endif
		</main>
	</body>
</html>
