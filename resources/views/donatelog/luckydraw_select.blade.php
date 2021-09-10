@extends('master')
@section('title', 'Lucky draw result')
@section('content')
<div class="container">
    <div class="row">
        <div id="luckydraw-title" class="page-title">{{ __('LUCKY DRAW')}}</div>
        <div class="col-md-10 col-md-offset-1">
            <div id="select-time" class="align-center">
                <form method="POST" action="{{url("/luckydrawresult")}}">
                    {{-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> --}}
                    {!! csrf_field() !!}
                    <?php
                        $year_start  = 2021;
                        $year_end = date('Y'); // current Year
                        $user_selected_year = $year_end; // user date of birth year

                        echo '<select id="select-year" name="select_year" class="input-control">'."\n";
                        for ($i_year = $year_start; $i_year <= $year_end; $i_year++) {
                            $selected = ($user_selected_year == $i_year ? ' selected' : '');
                            echo '<option value="'.$i_year.'"'.$selected.'>'.$i_year.'</option>'."\n";
                        }
                        echo '</select>'."\n";

                        $selected_month = date('m'); //current month
                        echo '<select id="select-month" name="select_month" class="input-control">'."\n";
                        for ($i_month = 1; $i_month <= 12; $i_month++) {
                            $selected = ($selected_month == $i_month ? ' selected' : '');
                            echo '<option value="'.$i_month.'"'.$selected.'>'. date('F', mktime(0,0,0,$i_month)).'</option>'."\n";
                        }
                        echo '</select>'."\n";
                    ?>
                    <div class="md-separate">
                        <button type="submit" id="btn-OK" class="btn btn-primary ladda-button" data-style="expand-right" data-size="s" data-color="green">{{__("Load ...")}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Readmore script -->
<script>
    $(document).ready(function() {

    });
</script>
@endsection

