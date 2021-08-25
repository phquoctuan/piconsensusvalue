@inject('PostAlert', 'App\Http\Controllers\PostsController')
@extends('master')
@section('title', 'Home')
@section('content')

    <div class="container">
        {{$PostAlert::AlertLastActivePost()}}
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
            <div id="currentvalue_panel" class="">
                <div class="text-center" id="pi-value_label">Current <span class="pi-sign">π</span> Value</div>
                <div class="text-center" id="pi-value">
                    1π =
                    <div class="current-pivalue" id="current-pivalue">{{$current_value}}</div>
                    <div id="dollar-sign">$
                    </div>
                </div>
            </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="text-center">Your Proposal</div>
                    </div>
                    <div class="panel-body">
                        <div class="text-center" id="pi-proposal">
                            <label id="ready_state" class="login_error">Open this page in Pi Browser to enable proposal</label>
                        </div>
                        <div class="text-center" id="pi-proposal">
                            <div class="form-group">
                                <label id="onepi" class="control-label col-xs-2 col-md-2 col-md-offset-1" >1π =</label>
                                <input type="number" id="proposal-value" min="0" step="any" class="col-xs-8 col-md-7" placeholder="type here..." />
                                <label id="dollar-sign" class="control-label col-xs-2 col-md-2">$</label>
                            </div>
                        </div>
                        <div class= "donate">
                            <label id="donate_label" class="">To propose you will have to donate:</label>
                            <label id="donate_value" class="">{{number_format(0.0000001,7)}}</label>
                            <label id="donate_sign" class="">π</label>
                            <span id= "donate_hint" class="fa fa-question-circle" data-toggle="tooltip" data-original-title="Donate amount = abs(propose - current) x 10% in dollar, and will be convert to Pi in current value." ></span>
                        </div>
                        <div class="col-md-6 col-md-offset-3 text-center">
                            <button type="submit" id ="btn-propose" class="btn btn-primary ladda-button" data-style="expand-right"
                                data-size="m" data-color="green" action="{{ url('proposal/propose') }}">
                                {{-- <i class="fa fa-btn fa-sign-in"></i>  --}}
                                Propose
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="text-center">This month</div>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-6" >
                            <div class="row-item">
                                <div class="col-md-6 float-left">
                                    <div class="key">From date:</div>
                                </div>
                                <div class="col-md-6 float-right">
                                    <div class="value">{{date('d-M-Y', strtotime($this_month_donate["from_date"]))}}</div>
                                </div>
                            </div>
                            <div class="row-item">
                                <div class="col-md-6 float-left">
                                    <div class="key">To date:</div>
                                </div>
                                <div class="col-md-6 float-right">
                                    <div class="value">{{date('d-M-Y', strtotime($this_month_donate["to_date"]))}}</div>
                                </div>
                            </div>
                            <div class="row-item">
                                <div class="col-md-6 float-left">
                                    <div class="key">Proposals:</div>
                                </div>
                                <div class="col-md-6 float-right">
                                    <div class="value">{{$this_month_donate["count_donate"]}}</div>
                                </div>
                            </div>
                            <div class="row-item">
                                <div class="col-md-6 float-left">
                                    <div class="key">Total donation:</div>
                                </div>
                                <div class="col-md-6 float-right">
                                    <div class="value">{{$this_month_donate["total_donate"]}} π</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row-item">
                                <div class="col-md-6 float-left">
                                    <div class="key">Proposal Id from:</div>
                                </div>
                                <div class="col-md-6 float-right">
                                    <div class="value">{{$this_month_donate["id_from"]}}</div>
                                </div>
                            </div>
                            <div class="row-item">
                                <div class="col-md-6 float-left">
                                    <div class="key">Proposal Id to:</div>
                                </div>
                                <div class="col-md-6 float-right">
                                    <div class="value">{{$this_month_donate["id_to"]}}</div>
                                </div>
                            </div>
                            <div class="row-item">
                                <div class="col-md-6 float-left">
                                    <div class="key">Award:</div>
                                </div>
                                <div class="col-md-6 float-right">
                                    <div class="value">{{$this_month_donate["reward"]}} π</div>
                                </div>
                            </div>
                            <div class="row-item">

                                <div class="col-md-6 float-left">
                                    <div class="key">Draw date*:</div>
                                    {{-- <div class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-original-title="intended" ></div> --}}
                                </div>
                                <div class="col-md-6 float-right">
                                    <div class="value">{{date('d-M-Y', strtotime($this_month_donate["draw_date"]))}}</div>
                                </div>
                            </div>

                        </div>
                        @if($this_month_donate["fixed_drawdate"] == 1 && $this_month_donate["drawed_id"] == NULL)
                            <div class="this-month-em">
                                (*)
                                The draw will take place at <span>{{date('Y-M-d H:i', strtotime($this_month_donate["draw_date"]))}}</span> - GMT
                                <br>
                                Link to join: <a href="{{$this_month_donate["live_drawlink"]}}" target="_blank">{{$this_month_donate["live_drawlink"]}}</a>

                            </div>
                        @else
                            <em class="this-month-em">(*): Draw date is intended date. We will announce the specific date and time along with zoom link for everyone involved. </em>
                        @endif

                        @if($this_month_donate["fixed_drawdate"] == 1 && $this_month_donate["drawed_id"] == NULL)
                            <div class="countdown-item">
                                @include('shared.thismonthcountdown')
                            </div>
                        @endif
                        <br>
                        <div id="last-month-reward">
                            <ul>
                                @if($last_month_donate["count_donate"] == 0 )
                                    <li>Last month reward: <strong>no data</strong></li>
                                @else
                                    <li>Last month reward: <strong>{{$last_month_donate["reward"]}} π</strong></li>
                                    @if ($last_month_donate["drawed_username"] != null )
                                        <li>Lucky person is: <strong>{{$last_month_donate["drawed_username"]}}</strong> with proposal id : <strong>{{$last_month_donate["drawed_id"]}}</strong></li>
                                        <li>Please DM on telegram to @phquoctuan to claim your reward.</li>
                                    @else
                                        <li>Lucky person is: no draw yet.</li>
                                        @if($last_month_donate["fixed_drawdate"] == 1 && $last_month_donate["drawed_id"] == NULL)
                                            <div class="draw-info">
                                                The draw will take place at <span>{{date('Y-M-d H:i', strtotime($last_month_donate["draw_date"]))}}</span> - GMT
                                                <br>
                                                Link to join: <a href="{{$last_month_donate["live_drawlink"]}}" target="_blank">{{$last_month_donate["live_drawlink"]}}</a>
                                            </div>
                                        @endif
                                    @endif
                                    @if ($last_month_donate["paid"] == 1 )
                                        <li>The reward has been transferred.</li>
                                        <li>Txid: {{$last_month_donate["txid"]}}.</li>
                                    @endif

                                @endif
                            </ul>
                            @if($last_month_donate["fixed_drawdate"] == 1 && $last_month_donate["drawed_id"] == NULL)
                                <div class="countdown-item">
                                    @include('shared.lastmonthcountdown')
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="text-center">Proposal history</div>
                    </div>
                    <div class="total-proposal-info">
                        <div class="info-item-wrap">
                            <div class="info-item">
                                Total proposal: <strong>{{$current_pi_value['total_propose']}}</strong>
                            </div>
                            <div class="info-item">
                                Total donation: <strong>{{$current_pi_value['sum_donate']}} π</strong>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        @include('proposal.ajax_proposal_item')
                    </div>
                </div>
            </div>
        </div>
        <div class="">
            <a class="pi-button" href="{{url('/drawhistory')}}">Lucky Draw history</a>
        </div>
    </div>

    <script>
        function CalculateDonateAmount(proposalvalue, currentvalue) {
            var donatePi = 0.0000001;
            if(proposalvalue == null || proposalvalue == "" || currentvalue == null)
            {
                return donatePi.toFixed(7);
            }
            var diff = Math.abs(proposalvalue - currentvalue);
            if(diff != 0) {
                if (currentvalue == 0){
                    donatePi = diff/(10 * proposalvalue);
                }
                else{
                    donatePi = diff/(10 * currentvalue);
                }
            }
            else{
                if (currentvalue == 0){
                    donatePi = 0.1;
                }
                else{
                    if(currentvalue < 10){
                        donatePi = 0.1;
                    }
                    else{
                        donatePi = 1/currentvalue;
                    }
                }
            }
            if(donatePi < 0.0000001)
            {
                donatePi = 0.0000001;
            }

            return donatePi.toFixed(7);
        }
        // $(function() {
        //     $.ajaxSetup({
        //         headers: {
        //         'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        //         }
        //     });
        // });
        $(document).ready(function() {
            // setTimeout(2000);
            $('#example').tooltip();

            $('[data-toggle="tooltip"]').tooltip({
                placement : 'top'
            });
            setInterval(function(){
                $.ajax({
                    url:'{{url("api/proposal/current") }}',
                    type:'GET',
                    dataType:'json',
                    success:function(response){
                        if(response.current_value || response.current_value == 0){
                            $('#current-pivalue').html(response.current_value.toFixed(7));
                            $('#donate_value').html(CalculateDonateAmount($('#proposal-value').val(), response.current_value));
                        }
                    },error:function(err){

                    }
                })
            }, 1000);

            $('#proposal-value').change(function (e) {
                $('#donate_value').html(CalculateDonateAmount($('#proposal-value').val(), $('#current-pivalue').text()));
            })

        })
    </script>

    {{-- ///////this month countdown////// --}}
    @if($this_month_donate["fixed_drawdate"] == 1 && $this_month_donate["drawed_id"] == NULL)
        <script>
            $(document).ready(function() {
                // var target_date = new Date().getTime() + (1000*3600*48); // set the countdown date
                var this_left = {!! $this_month_diff !!};//(target_date - current_date) / 1000;
                var days, hours, minutes, seconds; // variables for time units

                var countdown = document.getElementById("thismonth-tiles"); // get tag element

                getCountdown();

                setInterval(function () { getCountdown(); }, 1000);

                function getCountdown(){

                    // find the amount of "seconds" between now and target
                    // var current_date = new Date().getTime();
                    this_left = this_left - 1;
                    seconds_left = this_left;
                    days = pad( parseInt(seconds_left / 86400) );
                    seconds_left = seconds_left % 86400;

                    hours = pad( parseInt(seconds_left / 3600) );
                    seconds_left = seconds_left % 3600;

                    minutes = pad( parseInt(seconds_left / 60) );
                    seconds = pad( parseInt( seconds_left % 60 ) );

                    // format countdown string + set tag value
                    countdown.innerHTML = "<span>" + days + "</span><span>" + hours + "</span><span>" + minutes + "</span><span>" + seconds + "</span>";
                }

                function pad(n) {
                    return (n < 10 ? '0' : '') + n;
                }
            })
        </script>
    @endif
    {{-- ///////last month countdown////// --}}
    @if($last_month_donate["fixed_drawdate"] == 1 && $last_month_donate["drawed_id"] == NULL)
        <script>
            $(document).ready(function() {
                // var target_date = new Date().getTime() + (1000*3600*48); // set the countdown date
                var last_left = {!! $last_month_diff !!};//(target_date - current_date) / 1000;
                var days, hours, minutes, seconds; // variables for time units

                var countdown = document.getElementById("lastmonth-tiles"); // get tag element

                getCountdown();

                setInterval(function () { getCountdown(); }, 1000);

                function getCountdown(){

                    // find the amount of "seconds" between now and target
                    // var current_date = new Date().getTime();
                    last_left = last_left - 1;
                    seconds_left = last_left;
                    days = pad( parseInt(seconds_left / 86400) );
                    seconds_left = seconds_left % 86400;

                    hours = pad( parseInt(seconds_left / 3600) );
                    seconds_left = seconds_left % 3600;

                    minutes = pad( parseInt(seconds_left / 60) );
                    seconds = pad( parseInt( seconds_left % 60 ) );

                    // format countdown string + set tag value
                    countdown.innerHTML = "<span>" + days + "</span><span>" + hours + "</span><span>" + minutes + "</span><span>" + seconds + "</span>";
                }

                function pad(n) {
                    return (n < 10 ? '0' : '') + n;
                }
            })
        </script>
    @endif
@endsection
