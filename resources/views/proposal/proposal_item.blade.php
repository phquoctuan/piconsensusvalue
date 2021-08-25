<div id="load" style="position: relative;">
    <table id="propose-history-table" style="width:100%; overflow: auto;" class="table table-striped">
        <thead>
            <tr>
                <th class="ssmall-hide">Id</th>
                <th>Time</th>
                <th>Pioneer</th>
                <th>π/$</th>
                <th>Donate</th>
                <th class="small-hide">Txid</th>
            </tr>
        </thead>
        <tbody>
        @foreach($items as $item)
            <tr class="proposal-row">
                <td class="ssmall-hide">
                    <span title={{$item->id}}>{{$item->id}}</span>
                    {{-- <a href="{{ action('ArticleController@show', [$item->id]) }}">{{$item->title }}</a> --}}
                </td>
                <td class="datetime-width">
                    <span title="{{$item->created_at}}">{{$item->created_at->diffForHumans()}}</span>
                </td>
                <td>
                    <span title={{$item->username}}>{{\Illuminate\Support\Str::limit($item->username, 20)}}</span>
                </td>
                <td class="align-right">
                    <span title="{{$item->propose}}">{{$item->propose}}</span>
                </td>
                <td class="align-right">
                    <span title="{{$item->donate}}">{{$item->donate}}</span>
                </td>
                <td class="small-hide">
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
