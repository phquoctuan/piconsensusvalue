<div id="load" style="position: relative;">
    <table id="propose-history-table" style="width:100%; overflow: auto;" class="table table-striped">
        <thead>
            <tr>
                <th class="ssmall-hide">Id</th>
                <th>{{__('From Date')}}</th>
                <th>{{__('To Date')}}</th>
                <th>{{__('Count')}}</th>
                <th>{{__('Total Donate')}}</th>
                <th>{{__('Draw Date')}}</th>
                <th>{{__('Reward')}}(π)</th>
                @if($lucky2_enable == 1 || $lucky3_enable == 1)
                    <th>{{__('Lucky Id')}}(1)</th>
                    <th>{{__('Lucky Pioneer')}}(1)</th>
                    <th>{{__('Paid Out')}}(1)</th>
                    <th>Txid(1)</th>
                    @if($lucky2_enable == 1)
                        <th>{{__('Lucky Id')}}(2)</th>
                        <th>{{__('Lucky Pioneer')}}(2)</th>
                        <th>{{__('Paid Out')}}(2)</th>
                        <th>Txid(2)</th>
                    @endif
                    @if($lucky3_enable == 1)
                        <th>{{__('Lucky Id')}}(3)</th>
                        <th>{{__('Lucky Pioneer')}}(3)</th>
                        <th>{{__('Paid Out')}}(3)</th>
                        <th>Txid(3)</th>
                    @endif
                @else
                    <th>{{__('Lucky Id')}}</th>
                    <th>{{__('Lucky Pioneer')}}</th>
                    <th>{{__('Paid Out')}}</th>
                    <th>Txid</th>
                @endif
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
                @if($lucky2_enable == 1 || $lucky3_enable == 1)
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

                    @if($lucky2_enable == 1)
                        <td class="">
                            <span title="{{$item->drawed_id2}}">{{$item->drawed_id2}}</span>
                        </td>
                        <td>
                            <span title={{$item->drawed_username2}}>{{\Illuminate\Support\Str::limit($item->drawed_username2, 20)}}</span>
                        </td>
                        <td>
                            <span title="{{$item->paid2}}">{{($item->paid2 == 1) ? "Yes" : "No" }}</span>
                        </td>
                        <td>
                            <span title={{$item->txid2}}>{{\Illuminate\Support\Str::limit($item->txid2, 100)}}</span>
                        </td>
                    @endif
                    @if($lucky3_enable == 1)
                        <td class="">
                            <span title="{{$item->drawed_id3}}">{{$item->drawed_id3}}</span>
                        </td>
                        <td>
                            <span title={{$item->drawed_username3}}>{{\Illuminate\Support\Str::limit($item->drawed_username3, 20)}}</span>
                        </td>
                        <td>
                            <span title="{{$item->paid3}}">{{($item->paid3 == 1) ? "Yes" : "No" }}</span>
                        </td>
                        <td>
                            <span title={{$item->txid3}}>{{\Illuminate\Support\Str::limit($item->txid3, 100)}}</span>
                        </td>
                    @endif
                @else
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
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="float-right">
{{ $items->links() }}
</div>
{{-- {{ $items->onEachSide(5)->links() }} --}}
