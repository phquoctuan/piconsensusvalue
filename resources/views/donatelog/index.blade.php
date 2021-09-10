@extends('master')
@section('title', 'Lucky Draw history')
@section('content')
<div class="container">
<a class="pi-button" href="{{url('/')}}">&#60; {{ __('Home')}}</a>
<a class="pi-button" href="{{url('/luckydrawselect')}}">{{ __('Find more...')}}</a>
    <h2>{{ __('Lucky Draw History')}}</h2>
    <div class="row">
        <div class="col-md-12">
            @if (count($items) > 0)
                <section class="items">
                    @include('donatelog.donatelog_item')
                </section>
            @endif
        </div>
        <div class="col-sm-3">
        </div>
    </div>

</div>

@endsection
