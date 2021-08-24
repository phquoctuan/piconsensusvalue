@extends('master')
@section('title', 'Lucky draw result')
@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery.datetimepicker.css') }}"/>
<script src="{{asset('js/jquery.datetimepicker.full.js') }}"></script>

<div class="container">
    <a class="pi-button" href="{{url('/luckydrawselect')}}">&#60; Select Period</a>
    <div class="row">
        <div id="luckydraw-title" class="page-title">LUCKY DRAW</div>
        <div class="col-md-10 col-md-offset-1">
            <div id="selected-time" class="align-center">
            {{$donatelog['select_month']}} - {{$donatelog['select_year']}}
            </div>
            @if($donatelog['has_donatelog'] == 1)
                <form id="form_donatelog" class="md-separate">
                    {!! csrf_field() !!}
                    <input id="donatelog_id" type="hidden" value="{{$donatelog['id']}}">
                    <div class="row-item">
                        <div class="col-md-6 col-sm-12 row-block" >
                            <div class="col-md-6  col-sm-6 float-left">
                                <div class="key">From date:</div>
                            </div>
                            <div class="col-md-6  col-sm-6 float-right">
                                <div class="value">{{date('d-M-Y', strtotime($donatelog["from_date"]))}}</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 row-block">
                            <div class="col-md-6 col-sm-6 float-left">
                                <div class="key">To date:</div>
                            </div>
                            <div class="col-md-6 col-sm-6 float-right">
                                <div class="value">{{date('d-M-Y', strtotime($donatelog["to_date"]))}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row-item">
                        <div class="col-md-6 col-sm-12 row-block" >
                            <div class="col-md-6  col-sm-6 float-left">
                                <div class="key">Proposals:</div>
                            </div>
                            <div class="col-md-6  col-sm-6 float-right">
                                <div class="value">{{$donatelog["count_donate"]}}</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 row-block">
                            <div class="col-md-6 col-sm-6 float-left">
                                <div class="key">Total donation:</div>
                            </div>
                            <div class="col-md-6 col-sm-6 float-right">
                                <div class="value">{{$donatelog["total_donate"]}} π</div>
                            </div>
                        </div>
                    </div>
                    <div class="row-item">
                        <div class="col-md-6 col-sm-12 row-block" >
                            <div class="col-md-6  col-sm-6 float-left">
                                <div class="key">From Id:</div>
                            </div>
                            <div class="col-md-6  col-sm-6 float-right">
                                <div class="value">{{$donatelog["id_from"]}}</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 row-block">
                            <div class="col-md-6 col-sm-6 float-left">
                                <div class="key">To Id:</div>
                            </div>
                            <div class="col-md-6 col-sm-6 float-right">
                                <div class="value">{{$donatelog["id_to"]}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row-item">
                        <div class="col-md-6 col-sm-12 row-block" >
                            <div class="col-md-6  col-sm-6 float-left">
                                <div class="key">Award:</div>
                            </div>
                            <div class="col-md-6  col-sm-6 float-right">
                                <div class="value">{{$donatelog["reward"]}} π</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 row-block">
                            <div class="col-md-6 col-sm-6 float-left">
                                <div class="key">Draw date(GMT):</div>
                            </div>
                            <div class="col-md-6 col-sm-6 float-right">
                                <input type="text" id="draw_date" style="width: 165px;" value='{{date('Y-m-d H:i', strtotime($donatelog["draw_date"]))}}'/>
                            </div>
                        </div>
                    </div>
                    <div class="row-item">
                        <div class="col-md-6 col-sm-12 row-block" >
                            <div class="col-md-6  col-sm-6 float-left">
                                <div class="key">Fix draw date:</div>
                            </div>
                            <div class="col-md-6  col-sm-6 float-right">
                                <input type="checkbox" id="fixed_drawdate" name="fixed_drawdate" @if($donatelog["fixed_drawdate"]==1) checked @endif>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 row-block">
                            <div class="col-md-3 col-sm-3 col-xs-3 float-left">
                                <div class="key">Live link:</div>
                            </div>
                            <div class="col-md-9 col-sm-9 col-xs-9 float-right">
                                <input type="text" id="live_drawlink" name="live_drawlink" value="{{$donatelog["live_drawlink"]}}" style="width: 100%; line-height: 0.9;">
                            </div>
                        </div>
                    </div>
                    <div class="row-item">
                        <div class="col-md-6 col-sm-12 row-block" >
                            <div class="col-md-6  col-sm-6 float-left">
                                <div class="key">Lucky number:</div>
                            </div>
                            <div class="col-md-6  col-sm-6 float-right">
                                <input id="lucky_number" type="number" min="0" step="1" style="width: 100px;" value={{$donatelog["drawed_id"]}} />
                                <button type="button" id="load-username" class="btn btn-primary" style="line-height: 0.9; font-size: 12px;" data-size="xs">Load...</button>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 row-block">
                            <div class="col-md-6 col-sm-6 float-left">
                                <div class="key">Lucky pioneer:</div>
                            </div>
                            <div class="col-md-6 col-sm-6 float-right">
                                <strong>
                                <div id="drawed_username" class="value">{{$donatelog["drawed_username"]}}</div>
                                </strong>
                            </div>
                        </div>
                    </div>
                    <div class="row-item">
                        <div class="col-md-6 col-sm-12 row-block" >
                            <div class="col-md-6  col-sm-6 float-left">
                                <div class="key">Paid:</div>
                            </div>
                            <div class="col-md-6  col-sm-6 float-right">
                                <input type="checkbox" id="paid" name="paid" @if($donatelog["paid"]==1) checked @endif>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 row-block">
                            <div class="col-md-3 col-sm-3 col-xs-3 float-left">
                                <div class="key">Txid:</div>
                            </div>
                            <div class="col-md-9 col-sm-9 col-xs-9 float-right">
                                <input type="text" id="txid" name="txid" value="{{$donatelog["txid"]}}" style="width: 100%; line-height: 0.9;">
                            </div>
                        </div>
                    </div>

                    <div class="align-center md-separate">
                        <div>
                            <label for="pwd">Password to save:</label>
                            <input type="password" id="pwd" name="pwd">
                        </div>
                        <input type="submit" id="save-donatelog" class="btn btn-primary ladda-button sm-separate"  data-color="green" value="Save">
                    </div>
                </form>
            @else
                <div class="alert-warning alert-dismissible show align-center md-separate text-danger">
                    <strong>No data in this period.</strong>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Readmore script -->
<script>
    $(document).ready(function() {
        $('#draw_date').datetimepicker({
                        format:'Y-m-d H:i',
                        formatTime:'H:i',
                        // formatDate:'Y/m/d',
                    });

        ///////////
        var btnLoadUserNane = $('#load-username');
        btnLoadUserNane.click(function(e) {
            e.preventDefault();
            var ld = Ladda.create(document.querySelector('#load-username'));
            ld.start();
            $.ajax({
                    cache: false,
                    url: "/donatelog/getuserbyproposalid",
                    type: "GET",
                    data: {
                        "proposal_id": $('#lucky_number').val()
                    },
                    dataType: "json",
                })
                .done(function(response) { //success
                    if (response.success == "OK") {
                        $('#drawed_username').html(response.data.username);
                    } else {
                        swal("Oops!", response.message, 'error');
                    }
                })
                .fail(function(response) { //error
                    if (response.message) {
                        swal("Fail!", response.message, 'error');
                    } else
                    if (response.responseJSON) {
                        swal("Fail!", response.responseJSON.message, 'error');
                    } else {
                        swal("Fail!", "unknow error, please try again.", 'error');
                    }

                })
            ld.stop();
            ld.remove();
            btnLoadUserNane.removeClass("ladda-button");
        });
        ///////////
        var btnPropose = $('#save-donatelog');
        btnPropose.click(function(e) {
            e.preventDefault();
            var ld = Ladda.create(document.querySelector('#save-donatelog'));
            ld.start();
            $.ajax({
                    cache: false,
                    url: "api/donatelog/saveluckydraw",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "donatelog_id": $('#donatelog_id').val(),
                        // "draw_date": $('#draw_date').datetimepicker('getValue'),
                        "draw_date": $('#draw_date').val(),
                        "drawed_id": $('#lucky_number').val(),
                        "drawed_username": $('#drawed_username').html(),
                        "paid": $('#paid').is(":checked"),
                        "txid": $('#txid').val(),
                        "pwd": $('#pwd').val(),
                        "fixed_drawdate": $('#fixed_drawdate').is(":checked"),
                        "live_drawlink": $('#live_drawlink').val(),
                    },
                    dataType: "json",
                })
                .done(function(response) { //success
                    if (response.success == "OK") {
                        swal("Successful", response.message, 'success');
                    } else {
                        swal("Oops!", response.message, 'error');
                    }
                })
                .fail(function(response) { //error
                    if (response.message) {
                        swal("Fail!", response.message, 'error');
                    } else
                    if (response.responseJSON) {
                        swal("Fail!", response.responseJSON.message, 'error');
                    } else {
                        swal("Fail!", "unknow error, please try again.", 'error');
                    }

                })
            ld.stop();
            ld.remove();
        });
    });
</script>
@endsection

