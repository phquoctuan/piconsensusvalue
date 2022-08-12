@extends('master')
@section('title', 'Edit statictis')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>  {{ $curstatictis->label }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('statictis.index') }}" title="Go back"> <i class="fas fa-backward "></i> </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Label:</strong>
                {{ $curstatictis->label }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>From:</strong>
                {{ $curstatictis->from }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>To:</strong>
                {{ $curstatictis->to }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Total:</strong>
                {{ $curstatictis->total }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Date Created:</strong>
                {{ date_format($curstatictis->created_at, 'jS M Y') }}
            </div>
        </div>
    </div>
</div>
@endsection
