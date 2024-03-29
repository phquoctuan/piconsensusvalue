@inject('PostAlert', 'App\Http\Controllers\PostsController')
@extends('master')
@section('title', 'Home')
@section('content')

    <div class="container">
        {{$PostAlert::AlertLastActivePost()}}
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
            <div id="currentvalue_panel" class="">
                <div class="text-center" id="pi-value_label">{{__("Current")}} <span class="pi-sign">π</span> {{__("Value")}}</div>
                <div class="text-center" id="pi-value">
                    1π =
                    <div class="current-pivalue" id="current-pivalue">{{$current_value}}</div>
                    <div id="currentpivalue" style="display: none;">{{$current_value}}</div>
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
                        <div class="text-center">{{__("Your Proposal")}}</div>
                    </div>
                    <div class="panel-body">
                        <div class="text-center" id="pi-proposal">
                            <label id="ready_state" class="login_error">{{__("Open in Pi Browser Announcement")}}</label>
                        </div>
                        <div class="text-center" id="pi-proposal">
                            <div class="form-group">
                                <label id="onepi" class="control-label col-xs-2 col-md-2 col-md-offset-1" >1π =</label>
                                <input type="number" id="proposal-value" min="0" step="any" class="col-xs-8 col-md-7" placeholder="{{__("type here...")}}" />
                                <label id="dollar-sign" class="control-label col-xs-2 col-md-2">$</label>
                            </div>
                        </div>
                        <div class= "donate">
                            <label id="donate_label" class="">{{__("To propose you will have to donate:")}}</label>
                            <label id="donate_value" class="">{{number_format(0.00001, 5)}}</label>
                            <label id="donate_sign" class="">π</label>
                            <span id= "donate_hint" class="fa fa-question-circle" data-toggle="tooltip" data-original-title="{{__("Donate amount = abs(...")}}" ></span>
                        </div>
                        <div class="col-md-6 col-md-offset-3 text-center">
                            <button type="submit" id ="btn-propose" class="btn btn-primary ladda-button" data-style="expand-right"
                                data-size="m" data-color="green" action="{{ url('proposal/propose') }}">
                                {{-- <i class="fa fa-btn fa-sign-in"></i>  --}}
                                {{ __('Propose') }}
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
                        <div class="text-center">{{ __('This month') }}</div>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-6" >
                            <div class="row-item">
                                <div class="col-md-6 float-left">
                                    <div class="key">{{ __('From date:') }}</div>
                                </div>
                                <div class="col-md-6 float-right">
                                    <div class="value">{{date('d-M-Y', strtotime($this_month_donate["from_date"]))}}</div>
                                </div>
                            </div>
                            <div class="row-item">
                                <div class="col-md-6 float-left">
                                    <div class="key">{{ __('To date:') }}</div>
                                </div>
                                <div class="col-md-6 float-right">
                                    <div class="value">{{date('d-M-Y', strtotime($this_month_donate["to_date"]))}}</div>
                                </div>
                            </div>
                            <div class="row-item">
                                <div class="col-md-6 float-left">
                                    <div class="key">{{ __('Proposals:')}}</div>
                                </div>
                                <div class="col-md-6 float-right">
                                    <div id="thismonth_count_donate" class="value">{{$this_month_donate["count_donate"]}}</div>
                                </div>
                            </div>
                            <div class="row-item">
                                <div class="col-md-6 float-left">
                                    <div class="key">{{ __('Total donation:')}}</div>
                                </div>
                                <div class="col-md-6 float-right">
                                    <div id="thismonth_total_donate" class="value">{{number_format($this_month_donate["total_donate"], 5)}} π</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row-item">
                                <div class="col-md-6 float-left">
                                    <div class="key">{{ __('Proposal Id from:')}}</div>
                                </div>
                                <div class="col-md-6 float-right">
                                    <div class="value">{{$this_month_donate["id_from"]}}</div>
                                </div>
                            </div>
                            <div class="row-item">
                                <div class="col-md-6 float-left">
                                    <div class="key">{{ __('Proposal Id to:')}}</div>
                                </div>
                                <div class="col-md-6 float-right">
                                    <div id="thismonth_id_to" class="value">{{$this_month_donate["id_to"]}}</div>
                                </div>
                            </div>
                            <div class="row-item">
                                <div class="col-md-6 float-left">
                                    <div class="key">{{ __('Award')}}:</div>
                                </div>
                                <div class="col-md-6 float-right">
                                    <div id="thismonth_reward" class="value">{{number_format($this_month_donate["reward"],5)}} π</div>
                                </div>
                            </div>
                            <div class="row-item">

                                <div class="col-md-6 float-left">
                                    <div class="key">{{ __('Draw date*:')}}</div>
                                    {{-- <div class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-original-title="intended" ></div> --}}
                                </div>
                                <div class="col-md-6 float-right">
                                    @if($this_month_donate["draw_date"] != null)
                                    <div class="value">{{date('d-M-Y', strtotime($this_month_donate["draw_date"]))}}</div>
                                    @endif
                                </div>
                            </div>

                        </div>
                        @if($this_month_donate["fixed_drawdate"] == 1 && $this_month_donate["drawed_id"] == NULL)
                            <div class="this-month-em">
                                (*) {{ __('The draw will take place at')}} <span>{{date('Y-M-d H:i', strtotime($this_month_donate["draw_date"]))}}</span> - GMT
                                <br>
                                {{ __('Link to join:')}}
                                @if($this_month_donate["live_drawlink"] == NULL || $this_month_donate["live_drawlink"] == "")
                                {{ __('we will announce as soon as possible.')}}
                                @else
                                    <a href="{{$this_month_donate["live_drawlink"]}}" target="_blank">{{$this_month_donate["live_drawlink"]}}</a>
                                @endif
                            </div>
                        @else
                            <em class="this-month-em">{{ __('(*): Draw date is intended date...')}}</em>
                        @endif

                        @if($this_month_donate["fixed_drawdate"] == 1 && $this_month_donate["drawed_id"] == NULL)
                            <div class="countdown-item">
                                @include('shared.thismonthcountdown')
                            </div>
                        @endif
                        <br>
                        <div id="last-month-reward">
                            @if($last_month_donate["count_donate"] == 0 )
                                <li>{{ __('Last month reward:')}} <strong>{{ __('no data')}}</strong></li>
                            @else
                                @if($last_month_donate["reward2"] > 0 || $last_month_donate["reward3"] > 0)
                                    <ul>
                                        <li>{{ __('Last month reward:')}} <strong>{{$last_month_donate["reward"]}} π</strong></li>
                                        @if ($last_month_donate["drawed_username"] != null )
                                            <li>{{ __('Lucky person 1:')}} <strong>{{$last_month_donate["drawed_username"]}}</strong> {{ __('with proposal id :')}} <strong>{{$last_month_donate["drawed_id"]}}</strong></li>
                                            @if($last_month_donate["drawed_id2"] != NULL && $last_month_donate["drawed_username2"] != null && $last_month_donate["reward2"] > 0)
                                                <li>{{ __('Lucky person 2:')}} <strong>{{$last_month_donate["drawed_username2"]}}</strong> {{ __('with proposal id :')}} <strong>{{$last_month_donate["drawed_id2"]}}</strong></li>
                                            @endif
                                            @if($last_month_donate["drawed_id3"] != NULL && $last_month_donate["drawed_username3"] != null && $last_month_donate["reward3"] > 0)
                                                <li>{{ __('Lucky person 3:')}} <strong>{{$last_month_donate["drawed_username3"]}}</strong> {{ __('with proposal id :')}} <strong>{{$last_month_donate["drawed_id3"]}}</strong></li>
                                            @endif

                                        @else
                                            <li>{{ __('Lucky person is: no draw yet.')}}</li>
                                            @if($last_month_donate["fixed_drawdate"] == 1 && $last_month_donate["drawed_id"] == NULL)
                                                <div class="draw-info">
                                                    {{__('The draw will take place at')}} <span>{{date('Y-M-d H:i', strtotime($last_month_donate["draw_date"]))}}</span> - GMT
                                                    <br>
                                                    {{ __('Link to join:')}} <a href="{{$last_month_donate["live_drawlink"]}}" target="_blank">{{$last_month_donate["live_drawlink"]}}</a>
                                                </div>
                                            @endif
                                        @endif
                                        @if ($last_month_donate["paid"] == 1 )
                                            <li>{{ __('The reward has been transferred.')}}</li>
                                            {{-- <li>Txid: {{$last_month_donate["txid"]}}.</li> --}}
                                        @endif
                                    </ul>
                                @else
                                    <ul>
                                        <li>{{ __('Last month reward:')}} <strong>{{$last_month_donate["reward"]}} π</strong></li>
                                        @if ($last_month_donate["drawed_username"] != null )
                                            <li>{{ __('Lucky person is:')}}  <strong>{{$last_month_donate["drawed_username"]}}</strong> {{ __('with proposal id :')}} <strong>{{$last_month_donate["drawed_id"]}}</strong></li>
                                        @else
                                            <li>{{ __('Lucky person is: no draw yet.')}}</li>
                                            @if($last_month_donate["fixed_drawdate"] == 1 && $last_month_donate["drawed_id"] == NULL)
                                                <div class="draw-info">
                                                    {{__('The draw will take place at')}} <span>{{date('Y-M-d H:i', strtotime($last_month_donate["draw_date"]))}}</span> - GMT
                                                    <br>
                                                    {{ __('Link to join:')}} <a href="{{$last_month_donate["live_drawlink"]}}" target="_blank">{{$last_month_donate["live_drawlink"]}}</a>
                                                </div>
                                            @endif
                                        @endif
                                        @if ($last_month_donate["paid"] == 1 )
                                            <li>{{ __('The reward has been transferred.')}}</li>
                                        @endif
                                    </ul>
                                @endif
                                {{ __('Please DM on telegram')}}
                            @endif
                            @if($last_month_donate["count_donate"] > 0 && $last_month_donate["fixed_drawdate"] == 1 && $last_month_donate["drawed_id"] == NULL && $last_month_donate["drawed_username"] == NULL)
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

                @include('charts.statictis')
                @yield('statictis-chart')
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="text-center">{{ __('Proposal history')}}</div>
                    </div>
                    <div class="total-proposal-info">
                        <div class="info-item-wrap">
                            <div class="info-item col-xs-12 col-sm-6 col-md-4">
                                {{ __('Total proposal:')}} <strong id="total_propose">{{$current_pi_value['total_propose']}}</strong>
                            </div>
                            <div class="info-item col-xs-12 col-sm-6 col-md-4">
                                {{ __('Total donation:')}} <strong id="sum_donate">{{number_format($current_pi_value['sum_donate'],5)}} π</strong>
                            </div>
                            <div class="info-item col-xs-12 col-sm-6 col-md-4">
                                {{ __('Min proposal:')}} <strong id="atl_proposal">{{number_format($current_pi_value['atl_propose'],0)}}</strong>
                            </div>
                            <div class="info-item col-xs-12 col-sm-6 col-md-4">
                                {{ __('Max proposal:')}} <strong id="ath_proposal">{{number_format($current_pi_value['ath_propose'],0)}}</strong>
                            </div>
                            <div class="info-item col-xs-12 col-sm-6 col-md-4">
                                {{ __('ATL π Value:')}} <strong id="atl_value">{{number_format($current_pi_value['atl_value'],2)}}</strong>
                            </div>
                            <div class="info-item col-xs-12 col-sm-6 col-md-4">
                                {{ __('ATH π Value:')}} <strong id="ath_value">{{number_format($current_pi_value['ath_value'],2)}}</strong>
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
            <a class="pi-button" href="{{url('/drawhistory')}}">{{ __('Lucky draw history')}}</a>
        </div>
    </div>

    <script>
        function CalculateDonateAmount(proposalvalue, currentvalue) {
            var donatePi = 0.00001;
            if(proposalvalue == null || proposalvalue == "" || currentvalue == null)
            {
                return donatePi;
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
            if(donatePi < 0.00001)
            {
                donatePi = 0.00001;
            }
            retval = donatePi.toFixed(5);
            return retval;
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
                            $('#current-pivalue').html(response.current_value_str);
                            if(response.current_value == null){
                                $('#currentpivalue').html('');
                            }
                            else{
                                $('#currentpivalue').html(response.current_value.toFixed(5));
                            }
                            $('#thismonth_count_donate').html(response.thismonth_count_donate);
                            $('#thismonth_total_donate').html(response.thismonth_total_donate.toFixed(5) + " π");
                            $('#thismonth_id_to').html(response.thismonth_id_to);
                            $('#thismonth_reward').html(response.thismonth_reward.toFixed(5) + " π");
                            $('#total_propose').html(response.total_propose);
                            $('#sum_donate').html(response.sum_donate.toFixed(5) + " π");
                            if(response.atl_propose == null){
                                $('#atl_proposal').html('');
                            }
                            else{
                                $('#atl_proposal').html(response.atl_propose.toFixed(0));
                            }
                            if(response.ath_propose == null){
                                $('#ath_proposal').html('');
                            }
                            else{
                                $('#ath_proposal').html(response.ath_propose.toFixed(0));
                            }
                            if(response.atl_value == null){
                                $('#atl_value').html('');
                            }
                            else{
                                $('#atl_value').html(response.atl_value.toFixed(2));
                            }
                            if(response.ath_value == null){
                                $('#ath_value').html('');
                            }
                            else{
                                $('#ath_value').html(response.ath_value.toFixed(2));
                            }
                            $('#donate_value').html(CalculateDonateAmount($('#proposal-value').val(), response.current_value));
                        }
                    },error:function(err){
                        console.log("Error get current value");
                    }
                })
            }, 5000);

            $('#proposal-value').change(function (e) {
                $('#donate_value').html(CalculateDonateAmount($('#proposal-value').val(), $('#currentpivalue').text()));
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
                @if($this_month_diff > 0)
                    var countdown = document.getElementById("thismonth-tiles"); // get tag element

                    getCountdown();

                    setInterval(function () { getCountdown(); }, 1000);

                    function getCountdown(){

                        // find the amount of "seconds" between now and target
                        // var current_date = new Date().getTime();
                        if(this_left > 0) {
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
                    }

                    function pad(n) {
                        return (n < 10 ? '0' : '') + n;
                    }
                @endif
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
                @if($last_month_diff > 0)

                    getCountdown();

                    setInterval(function () { getCountdown(); }, 1000);
                    function getCountdown(){

                        // find the amount of "seconds" between now and target
                        // var current_date = new Date().getTime();
                        if(last_left > 0) {
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
                    }

                    function pad(n) {
                        return (n < 10 ? '0' : '') + n;
                    }
                @endif
            })
        </script>
    @endif
@endsection
