<html>
<head>
    <title> @yield('title') </title>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
    <meta name="description" content="Pi Value, survey application for Pi coin consensus value ">
    <meta name="keywords" content="pi network, pi value, pi coin, pi/usd, value of pi, pi donation, pi charty, pi social, crypto, pi app">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    {{-- <link rel="stylesheet" href="https://cdn.usebootstrap.com/bootstrap/4.6.0/css/bootstrap.min.css"> --}}


    <link rel="stylesheet" href="/css/ladda.min.css">
    <link href="{{asset('css/app.css') }}" rel="stylesheet" />
    <link href="{{asset('css/countdown.css') }}" rel="stylesheet" />
    {{-- <script src="//code.jquery.com/jquery-1.11.3.min.js"></script> --}}
    <script src="{{asset('js/jquery-1.11.3.min.js') }}"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    {{-- <script src="https://cdn.usebootstrap.com/bootstrap/4.6.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.usebootstrap.com/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script> --}}
    <script src="{{asset('js/app.js') }}"></script>
    <script src="https://sdk.minepi.com/pi-sdk.js"></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-37S01781JT"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-37S01781JT');
    </script>
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
                {{ __('Thank you for using this application.')}}
            </div>
            <div>
                {{ __('For more information, please...')}}
            </div>
            <div>
                Pi Value Telegram group: <a href="https://t.me/livepivalue" target="_blank" style="color: aqua;">t.me/livepivalue</a>
            </div>
            <div>
                Pi Value on YouTube: <a href="https://www.youtube.com/channel/UCEtfHRvBYjduAjZBOPBrSzA" target="_blank" style="color: aqua;">youtube.com/channel/UCE...</a>
            </div>
    </div>
</body>
</html>
