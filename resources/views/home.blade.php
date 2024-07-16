@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="container-fluid">
    <form action="{{ route('calculate') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Kriteria</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Kriteria</th>
                            <th>Bobot</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 1; $i <= 5; $i++)
                        <tr>
                            <td><input type="text" name="kriteria{{ $i }}" class="form-control" required></td>
                            <td><input type="number" name="bobot{{ $i }}" step="0.01" class="form-control" required></td>
                            <td>
                                <select name="type{{ $i }}" class="form-control" required>
                                    <option value="benefit">Benefit</option>
                                    <option value="cost">Cost</option>
                                </select>
                            </td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Alternatif</h3>
                <button type="button" id="addRow" class="btn btn-primary float-right">Tambah Alternatif</button>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="alternatifTable">
                    <thead>
                        <tr>
                            <th>Nama Alternatif</th>
                            @for ($i = 1; $i <= 5; $i++)
                                <th>Bobot Kriteria {{ $i }}</th>
                            @endfor
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="nama_alternatif1" class="form-control" required></td>
                            @for ($j = 1; $j <= 5; $j++)
                                <td><input type="number" name="alt1_krit{{ $j }}" step="0.01" class="form-control" required></td>
                            @endfor
                            <td><button type="button" class="btn btn-danger removeRow">Hapus</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-success">Hitung</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let rowCount = 1;

    document.getElementById('addRow').addEventListener('click', function () {
        rowCount++;
        const row = `<tr>
            <td><input type="text" name="nama_alternatif${rowCount}" class="form-control" required></td>
            @for ($j = 1; $j <= 5; $j++)
                <td><input type="number" name="alt${rowCount}_krit{{ $j }}" step="0.01" class="form-control" required></td>
            @endfor
            <td><button type="button" class="btn btn-danger removeRow">Hapus</button></td>
        </tr>`;
        document.querySelector('#alternatifTable tbody').insertAdjacentHTML('beforeend', row);
        attachRemoveRowEvent();
    });

    function attachRemoveRowEvent() {
        document.querySelectorAll('.removeRow').forEach(function (btn) {
            btn.addEventListener('click', function () {
                btn.closest('tr').remove();
            });
        });
    }

    attachRemoveRowEvent();
});
</script>
@endsection
