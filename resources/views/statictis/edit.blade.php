@extends('master')
@section('title', 'Edit statictis')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit statictis</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('statictis.index') }}" title="Go back"> <i class="fas fa-backward "></i> </a>
            </div>
        </div>
        @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('statictis.update', $curstatictis->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Label:</strong>
                        <input type="text" name="label" value="{{ $curstatictis->label }}" class="form-control" placeholder="column label">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>From:</strong>
                        <input type="number" id="from" name="from" min="0" class="form-control" value="{{ $curstatictis->from}}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>To:</strong>
                        <input type="number" id="to" name="to" min="0" class="form-control" value="{{ $curstatictis->to}}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Total:</strong>
                        <input type="number" id="total" name="total" class="form-control" value="{{ $curstatictis->total}}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>

        </form>



    </div>


</div>
@endsection
