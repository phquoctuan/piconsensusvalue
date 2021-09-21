@extends('master')
@section('title', 'Lucky draw result')
@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery.datetimepicker.css') }}"/>
<script src="{{asset('js/jquery.datetimepicker.full.js') }}"></script>

<div class="container">
    <a class="pi-button" href="{{url('/luckydrawselect')}}">&#60; {{ __('Select Period')}}</a>
    <div class="row">
        <div id="luckydraw-title" class="page-title">{{ __('LUCKY DRAW')}}</div>
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
                                <div class="key">{{ __('From date:')}}</div>
                            </div>
                            <div class="col-md-6  col-sm-6 float-right">
                                <div class="value">{{date('d-M-Y', strtotime($donatelog["from_date"]))}}</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 row-block">
                            <div class="col-md-6 col-sm-6 float-left">
                                <div class="key">{{ __('To date:')}}</div>
                            </div>
                            <div class="col-md-6 col-sm-6 float-right">
                                <div class="value">{{date('d-M-Y', strtotime($donatelog["to_date"]))}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row-item">
                        <div class="col-md-6 col-sm-12 row-block" >
                            <div class="col-md-6  col-sm-6 float-left">
                                <div class="key">{{ __('Proposals:')}}</div>
                            </div>
                            <div class="col-md-6  col-sm-6 float-right">
                                <div class="value">{{$donatelog["count_donate"]}}</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 row-block">
                            <div class="col-md-6 col-sm-6 float-left">
                                <div class="key">{{ __('Total donation:')}}</div>
                            </div>
                            <div class="col-md-6 col-sm-6 float-right">
                                <div class="value">{{$donatelog["total_donate"]}} π</div>
                            </div>
                        </div>
                    </div>
                    <div class="row-item">
                        <div class="col-md-6 col-sm-12 row-block" >
                            <div class="col-md-6  col-sm-6 float-left">
                                <div class="key">{{ __('From Id:')}}</div>
                            </div>
                            <div class="col-md-6  col-sm-6 float-right">
                                <div class="value">{{$donatelog["id_from"]}}</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 row-block">
                            <div class="col-md-6 col-sm-6 float-left">
                                <div class="key">{{ __('To Id:')}}</div>
                            </div>
                            <div class="col-md-6 col-sm-6 float-right">
                                <div class="value">{{$donatelog["id_to"]}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row-item">
                        <div class="col-md-6 col-sm-12 row-block">
                            <div class="col-md-6 col-sm-6 float-left">
                                <div class="key">{{ __('Draw date')}}(GMT):</div>
                            </div>
                            <div class="col-md-6 col-sm-6 float-right">
                                <input type="text" id="draw_date" class="input_datetime" style="width: 165px;" value='{{$donatelog["draw_date"] != null ? date('Y-m-d H:i', strtotime($donatelog["draw_date"])) : ''}}'/>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 row-block" >
                            <div class="col-md-6  col-sm-6 float-left">
                                <div class="key">{{ __('Fix draw date')}}:</div>
                            </div>
                            <div class="col-md-6  col-sm-6 float-right">
                                <input type="checkbox" id="fixed_drawdate" name="fixed_drawdate" @if($donatelog["fixed_drawdate"]==1) checked @endif>
                            </div>
                        </div>
                    </div>
                    <div class="row-item">
                        <div class="col-md-12 col-sm-12 row-block">
                            <div class="col-md-3 col-sm-3 col-xs-3 float-left">
                                <div class="key">{{ __('Live link')}}:</div>
                            </div>
                            <div class="col-md-9 col-sm-9 col-xs-9 float-right">
                                <input type="text" id="live_drawlink" name="live_drawlink" value="{{$donatelog["live_drawlink"]}}" style="width: 100%; line-height: 0.9;">
                            </div>
                        </div>
                    </div>
                    @if($lucky2_enable == 1 || $lucky3_enable == 1)
                        <div class="luckyresult_item">
                            <div class="row-item">
                                <div class="col-md-12 col-sm-12 row-block" >
                                    <div class="col-md-6  col-sm-6 float-left">
                                        <div class="key">{{ __('Award')}} 1:</div>
                                    </div>
                                    <div class="col-md-6  col-sm-6 float-right">
                                        <input type="number" id="reward" name="reward" class="value" value="{{$donatelog["reward"]}}" style="width: 90%; line-height: 0.9;"> <span>π</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row-item">
                                <div class="col-md-6 col-sm-12 row-block" >
                                    <div class="col-md-6  col-sm-6 float-left">
                                        <div class="key">{{ __('Lucky number')}} 1:</div>
                                    </div>
                                    <div class="col-md-6  col-sm-6 float-right">
                                        <input id="lucky_number" type="number" min="0" step="1" style="width: 100px;" value={{$donatelog["drawed_id"]}} />
                                        <button type="button" id="load-username" class="btn btn-primary" style="line-height: 0.9; font-size: 12px;" data-size="xs">Load...</button>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 row-block">
                                    <div class="col-md-6 col-sm-6 float-left">
                                        <div class="key">{{ __('Lucky Pioneer')}} 1:</div>
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
                                        <div class="key">{{ __('Paid Out')}} 1:</div>
                                    </div>
                                    <div class="col-md-6  col-sm-6 float-right">
                                        <input type="checkbox" id="paid" name="paid" @if($donatelog["paid"]==1) checked @endif>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-9 row-block">
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <div class="key">{{ __('Tx Fee')}} 1:</div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <input type="number" id="fee" name="fee" value="{{$donatelog["fee"]}}" style="width: 100%; line-height: 0.9;">
                                    </div>
                                    <div class="col-md-1 col-sm-1 col-xs-1">
                                        π
                                    </div>
                                </div>
                            </div>
                            <div class="row-item">
                                <div class="col-md-12 col-sm-12 row-block">
                                    <div class="col-md-3 col-sm-3 col-xs-3 float-left">
                                        <div class="key">Txid 1:</div>
                                    </div>
                                    <div class="col-md-9 col-sm-9 col-xs-9 float-right">
                                        <input type="text" id="txid" name="txid" value="{{$donatelog["txid"]}}" style="width: 100%; line-height: 0.9;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($lucky2_enable == 1)
                            <div class="luckyresult_item">
                                <div class="row-item">
                                    <div class="col-md-12 col-sm-12 row-block" >
                                        <div class="col-md-6  col-sm-6 float-left">
                                            <div class="key">{{ __('Award')}} 2:</div>
                                        </div>
                                        <div class="col-md-6  col-sm-6 float-right">
                                            <input type="number" id="reward2" name="reward2" value="{{$donatelog["reward2"]}}" style="width: 90%; line-height: 0.9;"> <span> π</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-item">
                                    <div class="col-md-6 col-sm-12 row-block" >
                                        <div class="col-md-6  col-sm-6 float-left">
                                            <div class="key">{{ __('Lucky number')}} 2:</div>
                                        </div>
                                        <div class="col-md-6  col-sm-6 float-right">
                                            <input id="lucky_number2" type="number" min="0" step="1" style="width: 100px;" value={{$donatelog["drawed_id2"]}} />
                                            <button type="button" id="load-username2" class="btn btn-primary" style="line-height: 0.9; font-size: 12px;" data-size="xs">Load...</button>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 row-block">
                                        <div class="col-md-6 col-sm-6 float-left">
                                            <div class="key">{{ __('Lucky Pioneer')}} 2:</div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 float-right">
                                            <strong>
                                            <div id="drawed_username2" class="value">{{$donatelog["drawed_username2"]}}</div>
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-item">
                                    <div class="col-md-6 col-sm-12 row-block" >
                                        <div class="col-md-6  col-sm-6 float-left">
                                            <div class="key">{{ __('Paid Out')}} 2:</div>
                                        </div>
                                        <div class="col-md-6  col-sm-6 float-right">
                                            <input type="checkbox" id="paid2" name="paid2" @if($donatelog["paid2"]==1) checked @endif>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 row-block">
                                        <div class="col-md-4 col-sm-4 col-xs-4">
                                            <div class="key">{{ __('Tx Fee')}} 2:</div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <input type="number" id="fee2" name="fee2" value="{{$donatelog["fee2"]}}" style="width: 100%; line-height: 0.9;">
                                        </div>
                                        <div class="col-md-1 col-sm-1 col-xs-1">
                                            π
                                        </div>
                                    </div>
                                </div>
                                <div class="row-item">
                                    <div class="col-md-12 col-sm-12 row-block">
                                        <div class="col-md-3 col-sm-3 col-xs-3 float-left">
                                            <div class="key">Txid 2:</div>
                                        </div>
                                        <div class="col-md-9 col-sm-9 col-xs-9 float-right">
                                            <input type="text" id="txid2" name="txid2" value="{{$donatelog["txid2"]}}" style="width: 100%; line-height: 0.9;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($lucky3_enable == 1)
                            <div class="luckyresult_item">
                                <div class="row-item">
                                    <div class="col-md-12 col-sm-12 row-block" >
                                        <div class="col-md-6  col-sm-6 float-left">
                                            <div class="key">{{ __('Award')}} 3:</div>
                                        </div>
                                        <div class="col-md-6  col-sm-6 float-right">
                                            <input type="number" id="reward3" name="reward3" value="{{$donatelog["reward3"]}}" style="width: 90%; line-height: 0.9;"><span> π</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-item">
                                    <div class="col-md-6 col-sm-12 row-block" >
                                        <div class="col-md-6  col-sm-6 float-left">
                                            <div class="key">{{ __('Lucky number')}} 3:</div>
                                        </div>
                                        <div class="col-md-6  col-sm-6 float-right">
                                            <input id="lucky_number3" type="number" min="0" step="1" style="width: 100px;" value={{$donatelog["drawed_id3"]}} />
                                            <button type="button" id="load-username3" class="btn btn-primary" style="line-height: 0.9; font-size: 12px;" data-size="xs">Load...</button>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 row-block">
                                        <div class="col-md-6 col-sm-6 float-left">
                                            <div class="key">{{ __('Lucky Pioneer')}} 3:</div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 float-right">
                                            <strong>
                                            <div id="drawed_username3" class="value">{{$donatelog["drawed_username3"]}}</div>
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-item">
                                    <div class="col-md-6 col-sm-12 row-block" >
                                        <div class="col-md-6  col-sm-6 float-left">
                                            <div class="key">{{ __('Paid Out')}} 3:</div>
                                        </div>
                                        <div class="col-md-6  col-sm-6 float-right">
                                            <input type="checkbox" id="paid3" name="paid3" @if($donatelog["paid3"]==1) checked @endif>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 row-block">
                                        <div class="col-md-4 col-sm-4 col-xs-4">
                                            <div class="key">{{ __('Tx Fee')}} 3:</div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <input type="number" id="fee3" name="fee3" value="{{$donatelog["fee3"]}}" style="width: 100%; line-height: 0.9;">
                                        </div>
                                        <div class="col-md-1 col-sm-1 col-xs-1">
                                            π
                                        </div>
                                    </div>
                                </div>
                                <div class="row-item">
                                    <div class="col-md-12 col-sm-12 row-block">
                                        <div class="col-md-3 col-sm-3 col-xs-3 float-left">
                                            <div class="key">Txid 3:</div>
                                        </div>
                                        <div class="col-md-9 col-sm-9 col-xs-9 float-right">
                                            <input type="text" id="txid3" name="txid3" value="{{$donatelog["txid3"]}}" style="width: 100%; line-height: 0.9;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="row-item">
                            <div class="col-md-12 col-sm-12 row-block" >
                                <div class="col-md-6  col-sm-6 float-left">
                                    <div class="key">{{ __('Award')}}:</div>
                                </div>
                                <div class="col-md-6  col-sm-6 float-right">
                                    <input type="number" id="reward" name="reward" value="{{$donatelog["reward"]}}" style="width: 90%; line-height: 0.9;"> <span> π</span>
                                </div>
                            </div>
                        </div>
                        <div class="row-item">
                            <div class="col-md-6 col-sm-12 row-block" >
                                <div class="col-md-6  col-sm-6 float-left">
                                    <div class="key">{{ __('Lucky number')}}:</div>
                                </div>
                                <div class="col-md-6  col-sm-6 float-right">
                                    <input id="lucky_number" type="number" min="0" step="1" style="width: 100px;" value={{$donatelog["drawed_id"]}} />
                                    <button type="button" id="load-username" class="btn btn-primary" style="line-height: 0.9; font-size: 12px;" data-size="xs">Load...</button>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 row-block">
                                <div class="col-md-6 col-sm-6 float-left">
                                    <div class="key">{{ __('Lucky Pioneer')}}:</div>
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
                                    <div class="key">{{ __('Paid Out')}}:</div>
                                </div>
                                <div class="col-md-6  col-sm-6 float-right">
                                    <input type="checkbox" id="paid" name="paid" @if($donatelog["paid"]==1) checked @endif>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-9 row-block">
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <div class="key">{{ __('Tx Fee')}}:</div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <input type="number" id="fee" name="fee" value="{{$donatelog["fee"]}}" style="width: 100%; line-height: 0.9;">
                                </div>
                                <div class="col-md-1 col-sm-1 col-xs-1">
                                    π
                                </div>
                            </div>
                        </div>
                        <div class="row-item">
                            <div class="col-md-12 col-sm-12 row-block">
                                <div class="col-md-3 col-sm-3 col-xs-3 float-left">
                                    <div class="key">Txid:</div>
                                </div>
                                <div class="col-md-9 col-sm-9 col-xs-9 float-right">
                                    <input type="text" id="txid" name="txid" value="{{$donatelog["txid"]}}" style="width: 100%; line-height: 0.9;">
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="align-center md-separate">
                        <div>
                            <label for="pwd">{{ __('Password to save')}}:</label>
                            <input type="password" id="pwd" name="pwd">
                        </div>
                        <input type="submit" id="update-donatelog" class="btn btn-primary ladda-button sm-separate"  data-color="blue" value="{{ __('Update Data')}}">
                        <input type="submit" id="save-donatelog" class="btn btn-primary ladda-button sm-separate"  data-color="green" value="{{ __('Save')}}">
                    </div>
                    <input type="hidden" id ="lucky2_enable" value="{{$lucky2_enable}}">
                    <input type="hidden" id ="lucky3_enable" value="{{$lucky3_enable}}">
                </form>
            @else
                <div class="alert-warning alert-dismissible show align-center md-separate text-danger">
                    <strong>{{ __('No data in this period.')}}</strong>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Readmore script -->
<script>
    $(document).ready(function() {
        $('.input_datetime').datetimepicker({
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
                        $('#drawed_username').html("");
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
        ////////////--------------------
        var btnLoadUserNane2 = $('#load-username2');
        btnLoadUserNane2.click(function(e) {
            e.preventDefault();
            var ld = Ladda.create(document.querySelector('#load-username2'));
            ld.start();
            $.ajax({
                    cache: false,
                    url: "/donatelog/getuserbyproposalid",
                    type: "GET",
                    data: {
                        "proposal_id": $('#lucky_number2').val()
                    },
                    dataType: "json",
                })
                .done(function(response) { //success
                    if (response.success == "OK") {
                        $('#drawed_username2').html(response.data.username);
                    } else {
                        $('#drawed_username2').html("");
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
            btnLoadUserNane2.removeClass("ladda-button");
        });
        ///////////---------------------
        var btnLoadUserNane3 = $('#load-username3');
        btnLoadUserNane3.click(function(e) {
            e.preventDefault();
            var ld = Ladda.create(document.querySelector('#load-username3'));
            ld.start();
            $.ajax({
                    cache: false,
                    url: "/donatelog/getuserbyproposalid",
                    type: "GET",
                    data: {
                        "proposal_id": $('#lucky_number3').val()
                    },
                    dataType: "json",
                })
                .done(function(response) { //success
                    if (response.success == "OK") {
                        $('#drawed_username3').html(response.data.username);
                    } else {
                        $('#drawed_username3').html("");
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
            btnLoadUserNane3.removeClass("ladda-button");
        });
        ///////////---------------------
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
                        "reward": $('#reward').val(),
                        "drawed_id": $('#lucky_number').val(),
                        "drawed_username": $('#drawed_username').html(),
                        "paid": $('#paid').is(":checked"),
                        "txid": $('#txid').val(),
                        "pwd": $('#pwd').val(),
                        "fixed_drawdate": $('#fixed_drawdate').is(":checked"),
                        "live_drawlink": $('#live_drawlink').val(),
                        "fee": $('#fee').val(),
                        @if($lucky2_enable == 1)
                            "lucky2_enable": $('#lucky2_enable').val(),
                            "reward2": $('#reward2').val(),
                            "drawed_id2": $('#lucky_number2').val(),
                            "drawed_username2": $('#drawed_username2').html(),
                            "paid2": $('#paid2').is(":checked"),
                            "txid2": $('#txid2').val(),
                            "fee2": $('#fee2').val(),
                        @endif
                        @if($lucky3_enable == 1)
                            "lucky3_enable": $('#lucky3_enable').val(),
                            "reward3": $('#reward3').val(),
                            "drawed_id3": $('#lucky_number3').val(),
                            "drawed_username3": $('#drawed_username3').html(),
                            "paid3": $('#paid3').is(":checked"),
                            "txid3": $('#txid3').val(),
                            "fee3": $('#fee3').val(),
                        @endif
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

        ///////////---------------------
        var btnUpdate = $('#update-donatelog');
        btnUpdate.click(function(e) {
            e.preventDefault();
            var ld = Ladda.create(document.querySelector('#update-donatelog'));
            ld.start();

            $.ajax({
                    cache: false,
                    url: "api/donatelog/updateluckydraw",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "donatelog_id": $('#donatelog_id').val(),
                        "pwd": $('#pwd').val(),
                    },
                    dataType: "json",
                })
                .done(function(response) { //success
                    if (response.success == "OK") {
                        location.reload();
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

