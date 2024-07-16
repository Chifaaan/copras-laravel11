<!-- resources/views/history.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>History</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Timestamp</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($calculations as $index => $calculation)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $calculation->created_at }}</td>
                    <td>
                        <form action="{{ route('destroy', $calculation->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                        <a href="{{ route('recalculate', $calculation->id) }}" class="btn btn-primary">Recalculate</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
