@extends('master')
@section('title', 'Lucky draw result')
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

    @if (session('success'))
    <script>
        $(document).ready(function() {
            swal("Successful !", "{{ session('success') }}", 'success');
        });
    </script>
    @endif

    @if($post == null)
        <div>
            Post item not found.
        </div>
    @else
        <form id="form_post" class="md-separate" method="POST" action="{{url('/posts')}}">
            {!! csrf_field() !!}
            <input type="hidden" id="id" name="id" value="{{$post["id"]}}">
            <strong class="">Post Id: {{$post["id"]}}</strong>
            <div class="form-fields">
                <label class="" for="name">Title:</label>
                <div class="col-md-12 post-field">
                    <input type="text" id="title" name="title" value="{{$post["title"]}}">
                </div>
            </div>
            <div class="form-fields">
                <label class="" for="content">Content:</label>
                <div class="col-md-12 post-field">
                    <textarea id="content" name="content" rows="5">{{$post["content"]}}</textarea>
                </div>
            </div>
            <div class="form-fields">
                <label class="" for="fromdate">From date:</label>
                <div class="col-md-12 post-field">
                    <input type="text" id="fromdate" name="fromdate" class = "input-datetime" style="width: 165px;" value='{{date('Y-m-d H:i', strtotime($post["fromdate"]))}}'/>
                </div>
            </div>
            <div class="form-fields">
                <label class="" for="todate">To date:</label>
                <div class="col-md-12 post-field">
                    <input type="text" id="todate" name="todate" class = "input-datetime" style="width: 165px;" value='{{date('Y-m-d H:i', strtotime($post["todate"]))}}'/>
                </div>
            </div>
            <div class="form-fields">
                <label class="" for="status">Status:</label>
                <div class="col-md-12 post-field">
                    <input type="number" id="status" name="status" value="{{$post["status"]}}"></input>
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
