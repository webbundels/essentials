<!DOCTYPE html>
<html ng-app="app">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>
            {{ config('app.name') }} - {{ $section === 'Changelog' ? $section : 'Documentatie'}}
        </title>

        <!-- Styles -->
        <style>
            {!! file_get_contents(base_path('vendor/webbundels/essentials/resources/assets/css/app.css')) !!}
            {!! file_get_contents(base_path('vendor/webbundels/essentials/resources/assets/css/'.$section.'.css')) !!}
        </style>
        <link href="//cdn.quilljs.com/1.3.6/quill.bubble.css" rel="stylesheet">
        <link href="//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,400,300,600,700,800" rel="stylesheet">
    </head>

    <body id="@yield('body_id')">
        @yield('content')
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
        @yield('scripts')
        <script type="text/javascript">
            {!! file_get_contents(base_path('vendor/webbundels/essentials/resources/assets/js/app.js')) !!}
        </script>
    </body>
</html>
