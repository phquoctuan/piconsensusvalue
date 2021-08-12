@extends('master')
@section('title', 'Lucky Draw history')
@section('content')
<div class="container">
<a class="btn btn-info" href="{{url('/')}}">&#60; Home</a>
    <h2>Lucky Draw History</h2>
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
