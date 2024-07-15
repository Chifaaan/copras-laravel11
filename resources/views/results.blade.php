@extends('layouts.app')

@section('content')
<h1>Hasil Perhitungan</h1>
<table class="table">
    <thead>
        <tr>
            <th>Alternatif</th>
            <th>Qi</th>
            <th>Rank</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($results as $result)
        <tr>
            <td>{{ $result['alternative'] }}</td>
            <td>{{ $result['qi'] }}</td>
            <td>{{ $result['rank'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<a href="{{ route('history') }}" class="btn btn-secondary">History</a>
<a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
@endsection
