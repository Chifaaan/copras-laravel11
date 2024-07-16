@extends('layouts.app')

@section('title', 'Hasil Perhitungan')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Hasil Perhitungan</h3>
            <a href="{{ route('history') }}" class="btn btn-primary float-right">Lihat History</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Alternatif</th>
                        <th>Nilai Qi</th>
                        <th>Ranking</th>
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
        </div>
    </div>
</div>
@endsection
