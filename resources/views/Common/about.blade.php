@extends('master')
@section('title', 'About Pi Value')
@section('content')
<div class="container">
    <div id="about-header">
        <img id="about-logo" src="{{ asset('images/logo.png') }}" alt="Logo">
        <div id="about-title" class="page-title">Pi Value</div>
    </div>
    <div id="about-slogan" class="">“Pi Value is a survey application for Pi coin consensus value”</div>
    <div id="about-info">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <div class="section">
                <div class="readmore-title">What is 'Pi Value' app</div>
                <div class="readmore">
                    <p>Pi/USD pair value is the most debate topic that I see in almost Pi group on social network.
                    So I think that all pioneers need an app that allow them to propose their desire value of Pi.
                    </p>
                    <p>Of course, the real value of Pi coin is not decided by this app.
                        We can consider this as a survey of pi value for reference purposes only.</p>
                    <strong>“Pi Value is a survey application for Pi coin consensus value”</strong>
                    <span class="readmore-link"></span>   
                </div> 
            </div>
            <div class="section">
                <div class="readmore-title">Feature description</div>
                <div class="readmore">
                    <p><strong>1</strong>. Any pioneer can suggest a desired price of Pi coin against USD.</p>
                    <p><strong>2</strong>. The system will update in real time the consensus price equal to the average price proposed by all pioneers.</p>
                    <p><strong>3</strong>. A pioneer can propose a desired price multiple times. To avoid spam, each time propose a new price, the pioneer must donate an amount of Pi equal to 10% of the proposed price difference from the current price.</p>
                    <p><strong>4</strong>. All history of proposal and donation will be public to everyone.</p>
                    <p><strong>5</strong>. All Pi donations will be accumulated and will be used for charity later.</p>
                    <p><strong>6</strong>. To encourage the proposal of pi value. Each month there will be one time lucky draw. The lucky winner will receive a reward of 10% of the total donation Pi in that month. The lucky number will be draw on <a href='www.random.org'>www.random.org</a>.</p>
                    <span class="readmore-link"></span>
                </div> 
            </div> 

            <div class="section">
                <div class="readmore-title">Proposal rule</div>
                <div class="readmore">
                    <p><strong>1</strong>. Pioneer must open this app (<a href='https://pivalue.trieulai.com'>pivalue.trieulai.com</a>) in Pi Browser to authenticate as a pioneer.</p>
                    <p><strong>2</strong>. Pioneer can suggest any desired price of Pi but must be greater or equal zero.</p>
                    <p><strong>3</strong>. Each time propose value of Pi/USD, pioneer agree to donate an amount of Pi equal to 10% of the proposed price difference from the current price (absolute value).</p>
                    <div style="background-color: lavender; padding: 5px; margin: 10px;">
                        <em>Example:</em>
                        <p>Current value Pi/USD = 10 (1π = 10$)</p>
                        <p>You propose desire value is 30 (1π = 20$)</p>
                        <p>&#8594 difference from the current price: 30 - 10 = 20.</p>
                        <p>&#8594 10% of difference: 20*10% = 2 (USD).</p>
                        <p>&#8594 convert to π base on current value: 2/10 = 0.2π.</p>
                        <p>&#8658 to propose, you must donate 0.2π.</p>
                    </div>
                    <p><strong>4</strong>. Once the proposal has been completed, this donate fee will not be refunded for any reason.</p>
                    <p><strong>5</strong>. Each time you make proposal you will receive an proposal id, this id is used to participate in the lucky draw at the end of the month.</p>
                    <span class="readmore-link"></span>
                </div> 
            </div>
            <div class="section">
                <div class="readmore-title">Lucky draw each month</div>
                <div class="readmore">
                    <p><strong>1</strong>. Each month there will be one time lucky draw. The lucky winner will receive a reward of 10% of the total donation Pi in that month.</p>
                    <p><strong>2</strong>. Currently, we will use <a href='https://www.random.org' target="_blank">www.random.org</a> to pick a random lucky number based on proposal id.</p>
                    <p><strong>3</strong>. Lucky pioneer will be announce on homepage for a month. Lucky pioneer must contact @phquoctuan on Telegram to inform your wallet address to receive award.</p>
                    <p><strong>4</strong>. The lucky draw is scheduled to be the 1st of the successive month. The specific date and time will be announced on the homepage, we will also provide a zoom link for everyone to participate.</p>
                    <span class="readmore-link"></span>
                </div> 
            </div> 
            <div class="section">
                <div class="readmore-title">Donate and Charity</div>
                <div class="readmore">
                    <p><strong>1</strong>. All donations data will be public and accessible from this app.</p>
                    <p><strong>2</strong>. Donations after deducting rewards and transaction fees, all will be used for charity.</p>
                    <p><strong>3</strong>. Contributions will be given to charities like <a href='https://www.wfp.org/' target="_blank">World Food Programme</a> or <a href='https://www.savethechildren.org/' target="_blank">Save The Children</a>.</p>
                    <p><strong>4</strong>. We will consult the pioneers community when giving charity but the admin (@phquoctuan) will make the final decision.</p>
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

