<!DOCTYPE html>
<html lang="es" class="dark">
    <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <link href="dist/images/logo.svg" rel="shortcut icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Icewall admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
        <meta name="keywords" content="admin template, Icewall Admin Template, dashboard template, flat admin template, responsive admin template, web app">
        <meta name="author" content="LEFT4CODE">
        <title>Error Page - ISO-ONE</title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="{{ url('assets/css/app.css') }}" />
        <style>
            .logo-image {
                position: absolute;
                right: 0px;
                bottom: 0px; 
                width: 100px;
                margin: 1em;               
            }
        </style>
        <!-- END: CSS Assets-->
    </head>
    <!-- END: Head -->
    <body class="main">
        <div class="container">
            <!-- BEGIN: Error Page -->
            <div class="error-page flex flex-col lg:flex-row items-center justify-center h-screen text-center lg:text-left">
                <div class="-intro-x lg:mr-20">
                    <img alt="Midone - HTML Admin Template" class="h-48 lg:h-auto" src="{{ url('assets/images/error-illustration.svg') }}">
                </div>
                <div class="text-white mt-10 lg:mt-0">
                    <div class="intro-x text-8xl font-medium">404</div>
                    <div class="intro-x text-xl lg:text-3xl font-medium mt-5">Oops. Esta página ha desaparecido.</div>
                    <div class="intro-x text-lg mt-3">Es posible que haya escrito mal la dirección o que la página se haya movido.</div>
                    <!-- <button class="intro-x btn py-3 px-4 text-white border-white dark:border-darkmode-400 dark:text-slate-200 mt-10">Back to Home</button> -->
                </div>
            </div>
            <!-- END: Error Page -->
        </div>
        <img alt="ISO-ONE Logo" class="logo-image" src="{{ url('assets/images/logo.jpg') }}">
        <!-- BEGIN: JS Assets-->
        <script src="{{ url('assets/js/app.js') }}"></script>
        <!-- END: JS Assets-->
    </body>
</html>