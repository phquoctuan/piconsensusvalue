@extends('master')
@section('title', 'About Pi Value')
@section('content')
<div class="container">
    <div id="about-header">
        <img id="about-logo" src="{{ asset('images/logo.png') }}" alt="Logo">
        <div id="about-title" class="page-title">Pi Value</div>
    </div>
    <div id="about-slogan" class="">“{{__('Pi Value is a survey application ...')}}”</div>
    <div id="about-info">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <div class="section">
                <div class="readmore-title">{{__("What is 'Pi Value' app")}}</div>
                <div class="readmore">
                    <p>{{__("Pi/USD pair value is the most debate...")}}
                    </p>
                    <p>{{__("Of course, the real value of Pi coin is...")}}</p>
                    <strong>“{{__('Pi Value is a survey application ...')}}”</strong>
                    <span class="readmore-link"></span>
                </div>
            </div>
            <div class="section">
                <div class="readmore-title">{{__('Feature description')}}</div>
                <div class="readmore">
                    <p><strong>1</strong>. {{__('Feature description... line 1')}}</p>
                    <p><strong>2</strong>. {{__('Feature description... line 2')}}</p>
                    <p><strong>3</strong>. {{__('Feature description... line 3')}}</p>
                    <p><strong>4</strong>. {{__('Feature description... line 4')}}</p>
                    <p><strong>5</strong>. {{__('Feature description... line 5')}}</p>
                    <p><strong>6</strong>. {{__('Feature description... line 6')}} <a href='http://www.random.org' target="_blank">www.random.org</a>.</p>
                    <span class="readmore-link"></span>
                </div>
            </div>

            <div class="section">
                <div class="readmore-title">{{__('Proposal rule')}}</div>
                <div class="readmore">
                    <p><strong>1</strong>. {{__('Proposal rule... line 1.1')}} (<a href='https://pivalue.live'>pivalue.live</a>) {{__('Proposal rule... line 1.2')}}</p>
                    <p><strong>2</strong>. {{__('Proposal rule... line 2')}}</p>
                    <p><strong>3</strong>. {{__('Proposal rule... line 3')}}</p>
                    <p><strong>4</strong>. {{__('Proposal rule... line 4')}}</p>
                    <p><strong>5</strong>. {{__('Proposal rule... line 5')}}</p>
                    <div style="background-color: lavender; padding: 5px; margin: 10px;">
                        <em>{{__('Example')}}:</em>
                        <p>{{__('Example... line 1')}}</p>
                        <p>&#8594 {{__('Example... line 2')}}</p>
                        <p>&#8594 {{__('Example... line 3')}}</p>
                        <p>&#8594 {{__('Example... line 4')}}</p>
                        <p>&#8594 {{__('Example... line 5')}}</p>
                        <p>&#8658 {{__('Example... line 6')}}</p>
                    </div>
                    <p><strong>6</strong>. {{__('Proposal rule... line 6')}}</p>
                    <p><strong>7</strong>. {{__('Proposal rule... line 7')}}</p>
                    <span class="readmore-link"></span>
                </div>
            </div>
            <div class="section">
                <div class="readmore-title">{{__('Lucky draw each month')}}</div>
                <div class="readmore">
                    <p><strong>1</strong>. {{__('Lucky draw each month... line 1')}}</p>
                    <p><strong>2</strong>. {{__('Lucky draw each month... line 2.1')}} <a href='https://www.random.org' target="_blank">www.random.org</a> {{__('Lucky draw each month... line 2.2')}}</p>
                    <p><strong>3</strong>. {{__('Lucky draw each month... line 3')}}</p>
                    <p><strong>4</strong>. {{__('Lucky draw each month... line 4')}}</p>
                    <span class="readmore-link"></span>
                </div>
            </div>
            <div class="section">
                <div class="readmore-title">{{__('Donate and Charity')}}</div>
                <div class="readmore">
                    <p><strong>1</strong>. {{__('Donate and Charity... line 1')}}</p>
                    <p><strong>2</strong>. {{__('Donate and Charity... line 2')}}</p>
                    <p><strong>3</strong>. {{__('Donate and Charity... line 3')}} <a href='https://www.wfp.org/' target="_blank">World Food Programme</a> or <a href='https://www.savethechildren.org/' target="_blank">Save The Children</a>.</p>
                    <p><strong>4</strong>. {{__('Donate and Charity... line 4')}}</p>
                    <span class="readmore-link"></span>
                </div>
            </div>

        </div>
    </div>
    </div>
</div>

<!-- Readmore script -->
<script>
    $(document).ready(function() {
        $(".readmore-link").click( function(e) {
            // record if our text is expanded
            var isExpanded =  $(e.target).hasClass("expand");

            //close all open paragraphs
            // $(".readmore.expand").removeClass("expand");
            // $(".readmore-link.expand").removeClass("expand");

            if (isExpanded){
                $( e.target ).parent( ".readmore" ).removeClass( "expand" );
                $(e.target).removeClass("expand");
            }
            else{
                // if target wasn't expand, then expand it
                $( e.target ).parent( ".readmore" ).addClass( "expand" );
                $(e.target).addClass("expand");
            }
        });
    });
</script>
@endsection

