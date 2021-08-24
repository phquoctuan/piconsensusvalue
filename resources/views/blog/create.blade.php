@extends('master')
@section('title', 'Create post')
@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery.datetimepicker.css') }}"/>
<script src="{{asset('js/jquery.datetimepicker.full.js') }}"></script>
<div class="container">
    <a class="pi-button" href="{{url('/posts')}}">&#60; Posts</a>
    @if (session('alert'))
        <script>
            $(document).ready(function() {
                swal("Oops!", "{{ session('alert') }}", 'error');
            });
        </script>
    @endif
        <form id="form_post" class="md-separate" method="POST" action="{{url('/posts/create')}}">
            {!! csrf_field() !!}
            <div class="form-fields">
                <label class="" for="name">Title:</label>
                <div class="col-md-12 post-field">
                    <input type="text" id="title" name="title" value="{{old('title')}}">
                </div>
            </div>
            <div class="form-fields">
                <label class="" for="content">Content:</label>
                <div class="col-md-12 post-field">
                    <textarea id="content" name="content" rows="5">{{old("content")}}</textarea>
                </div>
            </div>
            <div class="form-fields">
                <label class="" for="fromdate">From date:</label>
                <div class="col-md-12 post-field">
                    <input type="text" id="fromdate" name="fromdate" class = "input-datetime" style="width: 165px;" value='{{old("fromdate")}}'/>
                </div>
            </div>
            <div class="form-fields">
                <label class="" for="todate">To date:</label>
                <div class="col-md-12 post-field">
                    <input type="text" id="todate" name="todate" class = "input-datetime" style="width: 165px;" value='{{old("todate")}}'/>
                </div>
            </div>
            <div class="form-fields">
                <label class="" for="status">Status:</label>
                <div class="col-md-12 post-field">
                    <input type="number" id="status" name="status" value="{{old("status")}}"></input>
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
</div>

<script>
    $(document).ready(function() {
        $('.input-datetime').datetimepicker({
                        format:'Y-m-d H:i',
                        formatTime:'H:i',
                        formatDate:'Y-m-d',
                    });
    });
</script>
@endsection
