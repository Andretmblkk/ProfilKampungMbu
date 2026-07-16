<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Dana Kampung Mbu {{ $year }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #172033; font-size: 11px; }
        h1 { color: #0d4aaa; margin-bottom: 4px; }
        h2 { margin-top: 24px; color: #0d4aaa; font-size: 15px; }
        .muted { color: #64748b; }
        .summary { width: 100%; margin: 20px 0; border-collapse: collapse; }
        .summary td { width: 33.33%; padding: 12px; border: 1px solid #dbe4f0; }
        .summary strong { display: block; font-size: 15px; margin-top: 5px; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 8px; }
        table.data th, table.data td { padding: 7px; border: 1px solid #dbe4f0; text-align: left; }
        table.data th { background: #edf4ff; }
        .right { text-align: right !important; }
        .footer { margin-top: 28px; border-top: 1px solid #dbe4f0; padding-top: 8px; font-size: 9px; color: #64748b; }
    </style>
</head>
<body>
    <h1>Laporan Transparansi Dana Kampung Mbu</h1>
    <div class="muted">Distrik Melagi, Kabupaten Lanny Jaya, Papua Pegunungan · Periode {{ $year }}</div>

    <table class="summary">
        <tr>
            <td>Total Dana Masuk<strong>Rp {{ number_format($stats['income'], 0, ',', '.') }}</strong></td>
            <td>Total Pengeluaran<strong>Rp {{ number_format($stats['expense'], 0, ',', '.') }}</strong></td>
            <td>Dana Tersisa<strong>Rp {{ number_format($stats['remaining'], 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <h2>Dana Masuk</h2>
    <table class="data">
        <thead><tr><th>Tanggal</th><th>Sumber Dana</th><th>Kode</th><th class="right">Nominal</th></tr></thead>
        <tbody>
        @forelse($incomes as $income)
            <tr>
                <td>{{ $income->tanggal->format('d/m/Y') }}</td>
                <td>{{ $income->sumber_dana }}</td>
                <td>{{ $income->kode_transaksi }}</td>
                <td class="right">Rp {{ number_format((float) $income->nominal, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr><td colspan="4">Belum ada dana masuk terverifikasi pada periode ini.</td></tr>
        @endforelse
        </tbody>
    </table>

    <h2>Pengeluaran</h2>
    <table class="data">
        <thead><tr><th>Tanggal</th><th>Uraian</th><th>Kategori</th><th class="right">Nominal</th></tr></thead>
        <tbody>
        @forelse($expenses as $expense)
            <tr>
                <td>{{ $expense->tanggal->format('d/m/Y') }}</td>
                <td>{{ $expense->uraian }}</td>
                <td>{{ $expense->kategoriAnggaran?->nama ?? '-' }}</td>
                <td class="right">Rp {{ number_format((float) $expense->nominal, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr><td colspan="4">Belum ada pengeluaran terverifikasi pada periode ini.</td></tr>
        @endforelse
        </tbody>
    </table>

    <h2>Proyek Kampung</h2>
    <table class="data">
        <thead><tr><th>Proyek</th><th>Lokasi</th><th>Status</th><th>Progress</th><th class="right">Anggaran</th></tr></thead>
        <tbody>
        @forelse($projects as $project)
            <tr>
                <td>{{ $project->nama }}</td>
                <td>{{ $project->lokasi }}</td>
                <td>{{ ucfirst($project->status) }}</td>
                <td>{{ $project->progress }}%</td>
                <td class="right">Rp {{ number_format((float) $project->anggaran, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr><td colspan="5">Belum ada proyek pada periode ini.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div class="footer">Dokumen dibuat otomatis oleh Sistem Informasi Transparansi Dana Kampung Mbu pada {{ now()->format('d/m/Y H:i') }} WIT.</div>
</body>
</html>
