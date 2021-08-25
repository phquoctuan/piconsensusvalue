<div id="load" style="position: relative;">
    <table id="propose-history-table" style="width:100%; overflow: auto;" class="table table-striped">
        <thead>
            <tr>
                <th class="ssmall-hide">Id</th>
                <th>From Date</th>
                <th>To Date</th>
                <th>Count</th>
                <th>Total Donate</th>
                <th>Draw Date</th>
                <th>Reward(π)</th>
                <th>Lucky Id</th>
                <th>Lucky Pioneer</th>
                <th>Paid Out</th>
                <th>Txid</th>
            </tr>
        </thead>
        <tbody>
        @foreach($items as $item)
            <tr class="proposal-row">
                <td class="ssmall-hide">
                    <span title={{$item->id}}>{{$item->id}}</span>
                    {{-- <a href="{{ action('ArticleController@show', [$item->id]) }}">{{$item->title }}</a> --}}
                </td>
                <td class="">
                    <span title="{{$item->from_date}}">{{$item->from_date->format('Y/m/d')}}</span>
                </td>
                <td class="">
                    <span title="{{$item->to_date}}">{{$item->to_date->format('Y/m/d')}}</span>
                </td>
                <td class="">
                    <span title="{{$item->count_donate}}">{{$item->count_donate}}</span>
                </td>
                <td class="">
                    <span title="{{$item->total_donate}}">{{$item->total_donate}}</span>
                </td>
                <td class="">
                    <span title="{{$item->draw_date}}">{{$item->draw_date->format('Y/m/d')}}</span>
                </td>
                <td class="">
                    <span title="{{$item->reward}}">{{$item->reward}}</span>
                </td>
                <td class="">
                    <span title="{{$item->drawed_id}}">{{$item->drawed_id}}</span>
                </td>
                <td>
                    <span title={{$item->drawed_username}}>{{\Illuminate\Support\Str::limit($item->drawed_username, 20)}}</span>
                </td>
                <td>
                    <span title="{{$item->paid}}">{{($item->paid == 1) ? "Yes" : "No" }}</span>
                </td>
                <td>
                    <span title={{$item->txid}}>{{\Illuminate\Support\Str::limit($item->txid, 100)}}</span>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="float-right">
{{ $items->links() }}
</div>
{{-- {{ $items->onEachSide(5)->links() }} --}}
