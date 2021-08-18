<html>
<head>
    <title> @yield('title') </title>
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    {{-- <link rel="stylesheet" href="https://cdn.usebootstrap.com/bootstrap/4.6.0/css/bootstrap.min.css"> --}}


    <link rel="stylesheet" href="/css/ladda.min.css">
    <link href="{{asset('css/app.css') }}" rel="stylesheet" />

    {{-- <script src="//code.jquery.com/jquery-1.11.3.min.js"></script> --}}
    <script src="{{asset('js/jquery-1.11.3.min.js') }}"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    {{-- <script src="https://cdn.usebootstrap.com/bootstrap/4.6.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.usebootstrap.com/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script> --}}
    <script src="{{asset('js/app.js') }}"></script>

    <script src="https://sdk.minepi.com/pi-sdk.js"></script>

</head>
<body>
    {{-- @include('sweet::alert') --}}
    @include('shared.navbar')

@yield('content')

<script src="/js/spin.min.js"></script>
<script src="/js/ladda.min.js"></script>
<script src="/js/custom_script.js"></script>

<script>


</script>
    <div class="footer align-center">
            <div>
                Thank you for using this application.
            </div>
            <div>
                For more information, please contact @phquoctuan on Telegram.
            </div>
    </div>
</body>
</html>
