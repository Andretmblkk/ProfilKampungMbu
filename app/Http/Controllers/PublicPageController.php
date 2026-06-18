<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PublicPageController extends Controller
{
    public function home(Request $request)
    {
        $status = $request->query('status', 'semua');
        $projects = collect($this->projects())
            ->when($status !== 'semua', fn ($items) => $items->where('status_key', $status))
            ->values()
            ->all();

        return view('public.home', [
            'stats' => $this->stats(),
            'projects' => $projects,
            'selectedStatus' => $status,
        ]);
    }

    public function transparency()
    {
        return view('public.transparency', [
            'stats' => $this->stats(),
            'projects' => $this->projects(),
            'timeline' => [
                ['month' => 'Mei 2024', 'title' => 'Paving Jalan Dusun 2', 'amount' => 'Rp 125.000.000', 'status' => 'Selesai 100%', 'side' => 'right'],
                ['month' => 'Juni 2024', 'title' => 'Renovasi Balai Posyandu', 'amount' => 'Rp 85.000.000', 'status' => 'Progres 65%', 'side' => 'left'],
                ['month' => 'Agustus 2024', 'title' => 'Instalasi Panel Surya Desa', 'amount' => 'Estimasi Rp 210.000.000', 'status' => 'Verifikasi', 'side' => 'right'],
            ],
        ]);
    }

    public function news()
    {
        return view('public.news-index', ['posts' => $this->posts()]);
    }

    public function newsDetail(string $slug)
    {
        $post = collect($this->posts())->firstWhere('slug', $slug);
        abort_if(! $post, 404);

        return view('public.news-show', ['post' => $post]);
    }

    public function contact()
    {
        return view('public.simple-page', [
            'title' => 'Kontak Kami',
            'body' => 'Hubungi Pemerintah Kampung Mbu melalui email admin@kampungmbu.go.id atau layanan warga pada hari kerja pukul 08.00-16.00 WIT.',
        ]);
    }

    public function support()
    {
        return view('public.simple-page', [
            'title' => 'IT Support Kampung',
            'body' => 'Jika mengalami kendala masuk sistem, kirim email ke support@kampungmbu.go.id dengan nama, jabatan, dan kendala yang dialami.',
        ]);
    }

    public function forgotPassword()
    {
        return view('public.simple-page', [
            'title' => 'Pemulihan Kata Sandi',
            'body' => 'Fitur reset otomatis belum diaktifkan. Silakan hubungi IT Support Kampung untuk verifikasi identitas dan pengaturan ulang akun admin.',
        ]);
    }

    public function privacy()
    {
        return view('public.simple-page', [
            'title' => 'Kebijakan Privasi',
            'body' => 'Data yang dikirim melalui sistem ini digunakan untuk layanan administrasi, transparansi dana, dan tindak lanjut laporan warga Kampung Mbu.',
        ]);
    }

    public function sitemap()
    {
        return view('public.sitemap');
    }

    public function downloadPdf(): StreamedResponse
    {
        $pdf = $this->minimalPdf('Laporan Dana Kampung Mbu 2024', [
            'Total Dana Masuk: Rp 2.450.000.000',
            'Total Pengeluaran: Rp 1.120.000.000',
            'Dana Tersisa: Rp 1.330.000.000',
            'Total Proyek: 14',
        ]);

        return response()->streamDownload(
            fn () => print($pdf),
            'laporan-dana-kampung-2024.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }

    private function stats(): array
    {
        return [
            'income' => 2450000000,
            'expense' => 1120000000,
            'remaining' => 1330000000,
            'absorption' => 82.4,
            'projects' => 14,
            'beneficiaries' => 1240,
        ];
    }

    private function projects(): array
    {
        return [
            ['name' => 'Pembangunan Jembatan Dusun A', 'budget' => 'Rp 450.000.000', 'category' => 'Infrastruktur', 'progress' => 85, 'status' => 'Sedang Berjalan', 'status_key' => 'sedang-berjalan'],
            ['name' => 'Rehabilitasi Balai Desa', 'budget' => 'Rp 120.000.000', 'category' => 'Publik', 'progress' => 100, 'status' => 'Selesai', 'status_key' => 'selesai'],
            ['name' => 'Pengadaan Bibit Pertanian Utama', 'budget' => 'Rp 75.000.000', 'category' => 'Ekonomi', 'progress' => 0, 'status' => 'Direncanakan', 'status_key' => 'direncanakan'],
        ];
    }

    private function posts(): array
    {
        return [
            ['slug' => 'musyawarah-transparansi-dana-2024', 'title' => 'Musyawarah Transparansi Dana 2024', 'date' => '12 Juni 2024', 'excerpt' => 'Pemerintah Kampung Mbu membuka forum publik untuk memaparkan realisasi anggaran semester berjalan.'],
            ['slug' => 'pembangunan-jembatan-dusun-a', 'title' => 'Pembangunan Jembatan Dusun A Masuk Tahap Akhir', 'date' => '28 Mei 2024', 'excerpt' => 'Progres fisik jembatan telah mencapai 85% dan ditargetkan selesai tepat waktu.'],
            ['slug' => 'pelatihan-operator-sistem-desa', 'title' => 'Pelatihan Operator Sistem Desa', 'date' => '06 Mei 2024', 'excerpt' => 'Operator kampung mengikuti pelatihan pengelolaan data keuangan dan laporan digital.'],
        ];
    }

    private function minimalPdf(string $title, array $lines): string
    {
        $content = "BT /F1 18 Tf 72 760 Td ($title) Tj /F1 12 Tf";
        $y = 0;
        foreach ($lines as $line) {
            $escaped = str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $line);
            $content .= " 0 -28 Td ($escaped) Tj";
            $y++;
        }
        $content .= ' ET';
        $objects = [
            "1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj",
            "2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj",
            "3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >> endobj",
            "4 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj",
            "5 0 obj << /Length ".strlen($content)." >> stream\n$content\nendstream endobj",
        ];
        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $object) {
            $offsets[] = strlen($pdf);
            $pdf .= $object."\n";
        }
        $xref = strlen($pdf);
        $pdf .= "xref\n0 ".(count($objects) + 1)."\n0000000000 65535 f \n";
        foreach (array_slice($offsets, 1) as $offset) {
            $pdf .= str_pad((string) $offset, 10, '0', STR_PAD_LEFT)." 00000 n \n";
        }
        return $pdf."trailer << /Size ".(count($objects) + 1)." /Root 1 0 R >>\nstartxref\n$xref\n%%EOF";
    }
}
