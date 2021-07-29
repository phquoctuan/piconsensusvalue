@extends('master')
@section('title', 'Home')

@section('content')

    <div class="container">
        <div class="text-center">
        <img src="{{ asset('images/under_construction.jpg') }}" style="width: 150;">
        </div>

        {{-- <div class="content">
            <div class="title">Home Page</div>
            @if (!Auth::check())
                <div class="quote">Our Home page!</div>
            @else
                <div class="quote">You are now logged in!</div>
            @endif
        </div> --}}
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="text-center">Current <span class="pi-sign">π</span> Value</div>
                    </div>
                    <div class="panel-body">
                        <div class="text-center" id="pi-value">
                            1π =
                            <div class="current-pivalue" id="current-pivalue">{{$current_value}}</div>
                            <div id="dollar-sign">$
                            </div>
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
                            <div class="form-group">
                                <label id="onepi" class="control-label col-xs-4 col-md-3 col-md-offset-1" >1π =</label>
                                <input type="number" id="proposal-value" min="0" step="any" class="col-xs-6" placeholder="type here..." />
                                <label id="dollar-sign" class="control-label col-xs-2">$</label>
                            </div>
                        </div>
                        <div class= "donate">
                            <label id="donate_label" class="">To propse you will have to donate:</label>
                            <label id="donate_value" class="">{{number_format(0,7)}}</label>
                            <label id="donate_sign" class="">π</label>
                            <span id= "donate_hint" class="fa fa-question-circle" data-toggle="tooltip" data-original-title="Donate value = abs(propose - current) x 10% in dollar, and will be convert to Pi in current value." ></span>
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
            <div class="col-md-5 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="text-center">Propose history</div>
                    </div>
                    <div class="panel-body">

                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="text-center">Donate history</div>
                    </div>
                    <div class="panel-body">

                    </div>
                </div>
            </div>
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
            }, 3000);

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
