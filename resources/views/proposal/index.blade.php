@extends('master')
@section('title', 'Proposal history')
@section('content')
<div class="container">
    <h2>Proposal History</h2>
    <div class="row">
        <div class="col-md-12">
            @if (count($items) > 0)
                <section class="items">
                    @include('proposal.proposal_item')
                </section>
            @endif
        </div>
        <div class="col-sm-3">
        </div>
    </div>
</div>
@endsection
