<?php

namespace App\Http\Controllers;

use App\Models\LaporanWarga;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LaporanWargaController extends Controller
{
    public function create()
    {
        return view('public.citizen-report');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_pelapor' => ['required', 'string', 'max:120'],
            'kontak' => ['required', 'string', 'max:80'],
            'kategori' => ['required', 'string', 'max:80'],
            'isi_laporan' => ['required', 'string', 'min:20'],
            'lampiran' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'nama_pelapor.required' => 'Nama pelapor wajib diisi.',
            'kontak.required' => 'Nomor kontak wajib diisi.',
            'kategori.required' => 'Kategori laporan wajib dipilih.',
            'isi_laporan.required' => 'Isi laporan wajib diisi.',
            'isi_laporan.min' => 'Isi laporan minimal 20 karakter.',
            'lampiran.mimes' => 'Lampiran harus berupa JPG, PNG, atau PDF.',
            'lampiran.max' => 'Ukuran lampiran maksimal 5MB.',
        ]);

        if ($request->hasFile('lampiran')) {
            $data['lampiran_path'] = $request->file('lampiran')->store('laporan-warga', 'public');
        }

        $data['nomor_tiket'] = 'LWK-'.now()->format('Ymd').'-'.Str::upper(Str::random(5));
        $data['status'] = 'baru';

        $laporan = LaporanWarga::query()->create($data);

        return redirect()->route('citizen-reports.create')->with('status', 'Laporan berhasil dikirim. Nomor tiket: '.$laporan->nomor_tiket);
    }
}
