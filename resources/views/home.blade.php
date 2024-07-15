@extends('layouts.app')

@section('content')
<h1>Input Data</h1>
<form action="{{ route('calculate') }}" method="POST">
    @csrf
    <h2>Kriteria</h2>
    <table class="table">
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
                    <td>
                        <input type="text" name="kriteria{{ $i }}" class="form-control" required>
                    </td>
                    <td>
                        <input type="number" step="0.01" name="bobot{{ $i }}" class="form-control" required>
                    </td>
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

    <h2>Alternatif</h2>
    <table class="table" id="alternatifTable">
        <thead>
            <tr>
                <th>Nama Alternatif</th>
                <th>Bobot</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 1; $i <= 5; $i++)
                <tr>
                    <td>
                        <input type="text" name="nama_alternatif{{ $i }}" class="form-control" value="Alternatif {{ $i }}" required>
                    </td>
                    <td>
                        @for ($j = 1; $j <= 5; $j++)
                            <div class="form-group">
                                <label for="alt{{ $i }}_krit{{ $j }}">Kriteria {{ $j }}:</label>
                                <input type="number" step="0.01" name="alt{{ $i }}_krit{{ $j }}" class="form-control" required>
                            </div>
                        @endfor
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger remove-row">Remove</button>
                    </td>
                </tr>
            @endfor
        </tbody>
    </table>
    <button type="button" id="addRow" class="btn btn-success">Add Row</button>

    <button type="submit" class="btn btn-primary">Hitung</button>
</form>

<script>
    document.getElementById('addRow').addEventListener('click', function() {
        let table = document.getElementById('alternatifTable').getElementsByTagName('tbody')[0];
        let rowCount = table.rows.length + 1;
        let row = table.insertRow();
        row.innerHTML = `
            <td>
                <input type="text" name="nama_alternatif${rowCount}" class="form-control" value="Alternatif ${rowCount}" required>
            </td>
            <td>
                ${Array.from({ length: 5 }, (_, j) => `
                    <div class="form-group">
                        <label for="alt${rowCount}_krit${j+1}">Kriteria ${j+1}:</label>
                        <input type="number" step="0.01" name="alt${rowCount}_krit${j+1}" class="form-control" required>
                    </div>
                `).join('')}
            </td>
            <td>
                <button type="button" class="btn btn-danger remove-row">Remove</button>
            </td>
        `;
    });

    document.getElementById('alternatifTable').addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-row')) {
            event.target.closest('tr').remove();
        }
    });
</script>
@endsection
