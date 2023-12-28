<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{$title}}</title>

    {{-- Bootstrap --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.2/css/bootstrap.min.css">

    {{-- Fontawesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Custome CSS --}}
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/sidebar.css">
</head>

<body class="bg-light">
    <div class="page-wrapper chiller-theme">
        <x-headerNav :title="$title" />

        <x-sidebar />

        <main class="page-content">
            {{$slot}}
        </main>

        <x-footer />
    </div>


    {{-- Bootstrap --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

    {{-- JQuery --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    {{-- Custome JS --}}
    <script src="/js/app.js"></script>
    <script src="/js/sidebar.js"></script>
    {{$script??false}}
</body>

</html>
