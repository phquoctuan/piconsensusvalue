@extends('master')
@section('title', 'Stactictis Index')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Statictis Index </h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('statictis.create') }}" title="Create a project"> <i class="fas fa-plus-circle"></i>
                    </a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered table-responsive-lg">
        <tr>
            <th>Id</th>
            <th>Label</th>
            <th>From</th>
            <th>To</th>
            <th>Total</th>
            <th>Date Created</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($projects as $project)
            <tr>
                <td>{{ $project->id }}</td>
                <td>{{ $project->label }}</td>
                <td>{{ $project->from }}</td>
                <td>{{ $project->to }}</td>
                <td>{{ $project->total }}</td>
                <td>{{ date_format($project->created_at, 'jS M Y') }}</td>
                <td>
                    <form action="{{ route('statictis.destroy', $project->id) }}" method="POST">

                        <a href="{{ route('statictis.show', $project->id) }}" title="show">
                            <i class="fas fa-eye text-success  fa-lg"></i>
                        </a>

                        <a href="{{ route('statictis.edit', $project->id) }}">
                            <i class="fas fa-edit  fa-lg"></i>
                        </a>
                        @csrf
                        @method('DELETE')

                        <button type="submit" title="delete" style="border: none; background-color:transparent;">
                            <i class="fas fa-trash fa-lg text-danger"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

    {!! $projects->links() !!}
    <div class="row">
        <a href="{{Url('computestatic')}}" class="btn btn-primary" data-size="xs">Recompute Statictis</a>
    </div>
</div>
@endsection
