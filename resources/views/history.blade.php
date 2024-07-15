@extends('layouts.app')

@section('content')
<h1>History Perhitungan</h1>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($calculations as $calculation)
            <tr>
                <td>{{ $calculation->id }}</td>
                <td>{{ $calculation->created_at }}</td>
                <td>
                    <form action="{{ route('history.destroy', $calculation->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                    <form action="{{ route('history.recalculate', $calculation->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-success">Calculate</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
