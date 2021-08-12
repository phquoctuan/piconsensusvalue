@extends('master')
@section('title', 'Home')
@section('content')

    <div class="container">
        {{-- <div class="text-center">
        <img src="{{ asset('images/under_construction.jpg') }}" style="width: 150;">
        </div> --}}

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
                            <label id="donate_label" class="">To propse you will have to donate:</label>
                            <label id="donate_value" class="">{{number_format(0,7)}}</label>
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
                                    <div class="key">Total proposals:</div>
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
                                    <div class="key">Donation ID from:</div>
                                </div>
                                <div class="col-md-6 float-right">
                                    <div class="value">{{$this_month_donate["id_from"]}}</div>
                                </div>
                            </div>
                            <div class="row-item">
                                <div class="col-md-6 float-left">
                                    <div class="key">Donation ID to:</div>
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

                        <em class="this-month-em">(*): Draw date is intended date. We will announce the specific date and time along with zoom link for everyone involved. </em>
                        <br>
                        <div id="last-month-reward">
                            <ul>
                                @if($last_month_donate["count_donate"] == 0 )
                                    <li>Last month reward: <strong>no data</strong></li>
                                @else
                                    <li>Last month reward: <strong>{{$last_month_donate["reward"]}} π</strong></li>
                                    @if ($last_month_donate["drawed_username"] != null )
                                        <li>Lucky person is: <strong>{{$last_month_donate["drawed_username"]}}</strong> with donation id : <strong>{{$last_month_donate["drawed_id"]}}</strong></li>
                                    @else
                                        <li>Lucky person is: no draw yet.</li>
                                    @endif
                                    @if ($last_month_donate["paid"] == 1 )
                                        <li>The reward has been transferred.</li>
                                        <li>Txid: {{$last_month_donate["txid"]}}.</li>
                                    @else
                                        <li>Please DM on telegram to @phquoctuan to claim your reward.</li>
                                    @endif
                                @endif
                            </ul>
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
                                Total donation: <strong>{{$current_pi_value['sum_donate']}}π</strong>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        @include('proposal.ajax_proposal_item')
                    </div>
                </div>
            </div>
        </div>
        <div class="align-right">
            <a class="btn btn-info" href="{{url('/drawhistory')}}">Lucky Draw history</a>
        </div>
    </div>
    <div class="footer align-cemter">
        <div>
            Thank you for using this application.
        </div>
        <div>
            For more information, please contact @phquoctuan on Telegram.
        </div>
    </div>
    <script>
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
                    url:'{{ url("proposal/current") }}',
                    type:'GET',
                    dataType:'json',
                    success:function(response){
                        if(response.current_value || response.current_value == 0){
                            $('#current-pivalue').html(response.current_value.toFixed(7));
                            var donatePi = 0;
                            // var propose = $('#proposal-value');
                            if($('#proposal-value').val()){
                                var diff = Math.abs($('#proposal-value').val() - response.current_value);
                                if(diff != 0) {
                                    if (response.current_value == 0){
                                        var donatePi = diff/(10 * $('#proposal-value').val())
                                    }
                                    else{
                                        var donatePi = diff/(10 * response.current_value)
                                    }
                                }
                            }
                            $('#donate_value').html(donatePi.toFixed(7));
                        }
                    },error:function(err){

                    }
                })
            }, 5000);

            $('#proposal-value').change(function (e) {
                var donatePi = 0;
                if($('#proposal-value').val() && $('#current-pivalue').text()){
                    var diff = Math.abs($('#proposal-value').val() - $('#current-pivalue').text());
                    if(diff != 0) {
                        var donatePi = diff/(10 * $('#current-pivalue').text())
                    }
                }
                $('#donate_value').html(donatePi.toFixed(7));
            })

        })
    </script>
@endsection
