<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Wishnotes</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; font-size: 12px; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
        .footer { margin-top: 30px; text-align: right; font-size: 12px; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px; padding: 10px; background: #eee; border: 1px solid #ccc;">
        <strong>Info:</strong> Halaman ini akan otomatis membuka dialog print. Silakan pilih "Save as PDF".
        <a href="{{ route('admin.dashboard') }}" style="margin-left: 10px;">Kembali ke Dashboard</a>
    </div>

    <div class="header">
        <h2>LAPORAN AKTIVITAS WISHNOTES</h2>
        <p>Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>

    <div style="margin-bottom: 20px;">
        <strong>Ringkasan Statistik:</strong><br>
        Total Pengguna: {{ $totalUsers }}<br>
        Total Catatan: {{ $totalNotes }}
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Pemilik</th>
                <th>Tipe</th>
                <th>Privasi</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notes as $note)
            <tr>
                <td>{{ $note->id }}</td>
                <td>{{ $note->judul }}</td>
                <td>{{ $note->user->name ?? 'Anonim' }}</td>
                <td>{{ ucfirst($note->tipe_wadah) }}</td>
                <td>{{ ucfirst($note->privasi) }}</td>
                <td>{{ $note->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak oleh: Admin Wishnotes
    </div>

</body>
</html>