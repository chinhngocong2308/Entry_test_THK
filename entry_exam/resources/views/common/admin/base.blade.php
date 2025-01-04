<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>THK Holdings Vietman Hanoi</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "地域を選択してください",
                allowClear: true,
                width: "220px"
            });
        });
    </script>
    @vite('resources/css/admin/base.css')
    @vite('resources/scss/admin/base.scss')
    @vite('resources/scss/admin/side-menu.scss')
    @yield('custom_css')
</head>

<body>
    <div class="container">
        <x-admin.side-menu />
        <div class="page-wrapper">
            @yield('main_contents')
        </div>
    </div>

    @yield('page_js')
</body>
</html>

