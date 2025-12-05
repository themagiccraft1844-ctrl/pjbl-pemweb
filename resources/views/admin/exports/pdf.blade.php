<!DOCTYPE html>
<html>
<head>
    <title>Data Wishnotes PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #aaa; padding: 6px; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>Data Wishnotes</h2>

    <table>
        <thead>
            <tr>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Tipe</th>
                <th>Privasi</th>
                <th>Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notes as $n)
            <tr>
                <td>{{ $n->judul }}</td>
                <td>{{ $n->deskripsi_singkat }}</td>
                <td>{{ $n->tipe_wadah }}</td>
                <td>{{ $n->privasi }}</td>
                <td>{{ $n->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
