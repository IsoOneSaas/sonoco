<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ISO-ONE :: Sonoco') }}</title>

        <!-- Fonts -->

		<link href="{{ url('assets/images/favicon.ico') }}" rel="shortcut icon">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
		<style>
			#back { 
			  background: url({{ url('assets/images/background.jpg') }}) no-repeat center center fixed; 
			  -webkit-background-size: cover;
			  -moz-background-size: cover;
			  -o-background-size: cover;
			  background-size: cover;
			}		
		</style>
    </head>
    <body class="font-sans text-gray-3 antialiased">
        <div id="back" class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    <x-application-logo class="w-40 h-40 fill-current text-gray-500" />
                </a>
            </div>
			<div>Versión 4.0</div>
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
