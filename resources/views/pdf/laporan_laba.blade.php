<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Laba / Rugi</title>
    <style>
        body { font-family: 'Arial', sans-serif; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        .header p { color: #666; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th { background: #333; color: white; padding: 10px; text-align: left; }
        td { padding: 8px 10px; border-bottom: 1px solid #ddd; }
        .text-center { text-align: center; }
        .text-success { color: #28a745; }
        .text-danger { color: #dc3545; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #666; }
        .total-box { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 20px; }
        .total-box h2 { margin: 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN LABA / RUGI</h1>
        <p>Periode: {{ date('F', mktime(0,0,0,$bulan,1)) }} {{ $tahun }}</p>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
    </div>
    
    @if(count($detailLaba) > 0)
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jenis Gas</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Harga Jual</th>
                <th>Harga Beli</th>
                <th>Keuntungan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detailLaba as $item)
            <tr>
                <td>{{ $item['tanggal']->format('d/m/Y') }}</td>
                <td>{{ $item['jenis_gas'] }}</td>
                <td>{{ ucfirst($item['tipe']) }}</td>
                <td>{{ $item['jumlah'] }}</td>
                <td>Rp {{ number_format($item['harga_jual'], 0, ',', '.') }}</td>
                <td>Rp {{ number_format($item['harga_beli'], 0, ',', '.') }}</td>
                <td>Rp {{ number_format($item['keuntungan'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="total-box">
        <h2>Total Keuntungan: <span class="text-success">Rp {{ number_format($totalKeuntungan, 0, ',', '.') }}</span></h2>
    </div>
    @else
    <p class="text-center">Belum ada data penjualan untuk periode ini.</p>
    @endif
    
    <div class="footer">
        <p>Dicetak oleh: Sistem Manajemen Toko Gas</p>
    </div>
</body>
</html>