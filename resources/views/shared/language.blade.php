<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">

            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <span class="dropdown navbar-right drop-lang" id = "nav-header-lang">
                @yield('drop-lang')
            </span>
            <a class="navbar-brand" href="/"><span style="font-family: serif; font-size: 35;">π</span> value</a>
        </div>

        <!-- Navbar Right -->
        <div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="/about">{{ __('About')}}</a></li>
                {{-- <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Account <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li class=''><a href="/users/register" class="disabled-link">Register</a></li>
                        <li><a href="/users/login" >Login</a></li>
                    </ul>
                </li> --}}
            </ul>

        </div>
        <span class="dropdown navbar-right drop-lang" id = "nav-lang">
            @yield('drop-lang')
        </span>
    </div>
</nav>

{{-- bootstrap 4 --}}
{{-- <nav class="navbar navbar-expand-md bg-dark navbar-dark">
    <!-- Brand -->
    <a class="navbar-brand" href="#">Navbar</a>

    <!-- Toggler/collapsibe Button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar links -->
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="#">About</a>
        </li>

      </ul>
    </div>
  </nav> --}}
