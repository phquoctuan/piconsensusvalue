@extends('shared.language')

@section('drop-lang')
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
        <img src="{{ asset('images/' . App::getLocale() . '.png') }}" alt="{{ __('VI')}}" style="border: solid 1px lightgray;">
        <span class="caret"></span>
    </a>
    <ul class="dropdown-menu" role="menu">
        @if (!App::isLocale('vi'))
            <li><a href="{{ route('lang',['lang' => 'vi']) }}">
                <img src="{{ asset('images/vi.png') }}" alt="{{ __('VI')}}">
                {{ __('VI')}}
                </a>
            </li>
        @endif
        @if (!App::isLocale('en'))
            <li><a href="{{ route('lang',['lang' => 'en' ]) }}">
                <img src="{{ asset('images/en.png') }}" alt="{{ __('EN')}}">
                {{ __('EN')}}
                </a>
            </li>
        @endif
        @if (!App::isLocale('jp'))
        <li><a href="{{ route('lang',['lang' => 'jp' ]) }}">
            <img src="{{ asset('images/jp.png') }}" alt="{{ __('JP')}}">
            {{ __('JP')}}
            </a>
        </li>
        @endif
        @if (!App::isLocale('cn'))
        <li><a href="{{ route('lang',['lang' => 'cn' ]) }}">
            <img src="{{ asset('images/cn.png') }}" alt="{{ __('CN')}}">
            {{ __('CN')}}
            </a>
        </li>
        @endif
    </ul>
@endsection
@section('dropdown-lang')
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
            <img src="{{ asset('images/' . App::getLocale() . '.png') }}" alt="{{ __('VI')}}" style="border: solid 1px lightgray;">
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            @if (!App::isLocale('vi'))
                <li><a href="{{ route('lang',['lang' => 'vi']) }}">
                    <img src="{{ asset('images/vi.png') }}" alt="{{ __('VI')}}">
                    {{ __('VI')}}
                    </a>
                </li>
            @endif
            @if (!App::isLocale('en'))
                <li><a href="{{ route('lang',['lang' => 'en' ]) }}">
                    <img src="{{ asset('images/en.png') }}" alt="{{ __('EN')}}">
                    {{ __('EN')}}
                    </a>
                </li>
            @endif
            @if (!App::isLocale('jp'))
                <li><a href="{{ route('lang',['lang' => 'jp' ]) }}">
                    <img src="{{ asset('images/jp.png') }}" alt="{{ __('JP')}}">
                    {{ __('JP')}}
                    </a>
                </li>
            @endif
            @if (!App::isLocale('cn'))
                <li><a href="{{ route('lang',['lang' => 'cn' ]) }}">
                    <img src="{{ asset('images/cn.png') }}" alt="{{ __('CN')}}">
                    {{ __('CN')}}
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endsection
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
