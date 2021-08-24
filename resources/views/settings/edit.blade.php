@extends('master')
@section('title', 'Setting')
@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery.datetimepicker.css') }}"/>
<script src="{{asset('js/jquery.datetimepicker.full.js') }}"></script>
<div class="container">
    <a class="pi-button" href="{{url('/settings')}}">&#60; settings</a>
    @if (session('alert'))
        <script>
            $(document).ready(function() {
                swal("Oops!", "{{ session('alert') }}", 'error');
            });
        </script>
    @endif
    @if (session('success'))
    <script>
        $(document).ready(function() {
            swal("Successful !", "{{ session('success') }}", 'success');
        });
    </script>
    @endif

    @if($setting == null)
        <div>
            setting item not found.
        </div>
    @else
        <form id="form_setting" class="md-separate" method="POST" action="{{url('/settings/update')}}">
            {!! csrf_field() !!}
            <input type="hidden" id="id" name="id" value="{{$setting["id"]}}">
            <strong class="">Setting Id: {{$setting["id"]}}</strong>
            <div class="form-fields">
                <label class="" for="attribute">Attribute:</label>
                <div class="col-md-12 setting-field">
                    <input type="text" id="attribute" name="attribute" value="{{old('attribute', $setting["attribute"])}}">
                </div>
            </div>
            <div class="form-fields">
                <label class="" for="value">Value:</label>
                <div class="col-md-12 setting-field">
                    <input type="text" id="value" name="value" value="{{old('value', $setting["value"])}}">
                </div>
            </div>


            <div class="align-center md-separate">
                <div>
                    <label for="pwd">Password to save:</label>
                    <input type="password" id="pwd" name="pwd">
                </div>
                <input type="submit" id="save" class="btn btn-primary ladda-button sm-separate"  data-color="green" value="Save">
            </div>
        </form>
    @endif
</div>
@endsection
