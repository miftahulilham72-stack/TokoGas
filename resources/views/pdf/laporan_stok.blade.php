<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Stok</title>
    <style>
        body { font-family: 'Arial', sans-serif; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        .header p { color: #666; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th { background: #333; color: white; padding: 10px; text-align: left; }
        td { padding: 8px 10px; border-bottom: 1px solid #ddd; }
        .text-center { text-align: center; }
        .text-danger { color: #dc3545; }
        .text-success { color: #28a745; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN STOK GAS</h1>
        <p>Tanggal: {{ date('d/m/Y H:i') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Jenis Gas</th>
                <th>Stok Saat Ini</th>
                <th>Stok Minimum</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stokData as $item)
            <tr>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->stok_sekarang }}</td>
                <td>{{ $item->stok_minimum }}</td>
                <td>
                    @php
                        $stok = $item->stok;
                        $status = $stok && $stok->isMenipis() ? 'Menipis' : 'Aman';
                        $color = $stok && $stok->isMenipis() ? 'danger' : 'success';
                    @endphp
                    <span style="color: {{ $color }};">{{ $status }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>Dicetak oleh: Sistem Manajemen Toko Gas</p>
    </div>
</body>
</html>