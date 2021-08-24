@extends('master')
@section('title', 'Setting')
@section('content')

    <div class="container">
        @if ($settings->isEmpty())
            <p> There is no setting.</p>
        @else
            @foreach ($settings as $setting)
                <div class="setting-item">
                    <div class="setting-attr">{!! $setting->attribute !!}</div>
                    <div class="setting-value">
                        {!! $setting->value !!}
                    </div>
                    <div class = "setting-action align-right">
                        <a href="{{Url('/settings/edit/' . $setting->id)}}" class="btn btn-primary" data-size="xs">Edit</a>
                    </div>
                </div>
            @endforeach
        @endif
        {!! $settings->render() !!}
    </div>

@endsection
