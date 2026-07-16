<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\DanaMasuk;
use App\Models\KategoriAnggaran;
use App\Models\Pengeluaran;
use App\Models\ProyekKampung;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PublicPageController extends Controller
{
    public function home(Request $request)
    {
        $year = $this->selectedYear($request);
        $status = $request->query('status', 'semua');

        $projects = $this->projectQuery($year)
            ->when($status !== 'semua', fn (Builder $query) => $query->where('status', $this->databaseStatus($status)))
            ->latest('updated_at')
            ->limit(8)
            ->get();

        return view('public.home', [
            'stats' => $this->stats($year),
            'projects' => $projects,
            'selectedStatus' => $status,
            'selectedYear' => $year,
            'availableYears' => $this->availableYears(),
        ]);
    }

    public function transparency(Request $request)
    {
        $year = $this->selectedYear($request);
        $status = $request->query('status', 'semua');
        $projects = $this->projectQuery($year)
            ->when($status !== 'semua', fn (Builder $query) => $query->where('status', $this->databaseStatus($status)))
            ->latest('updated_at')
            ->get();

        $allocations = KategoriAnggaran::query()
            ->withSum(['pengeluarans as realisasi' => fn (Builder $query) => $query
                ->where('status', 'terverifikasi')
                ->whereYear('tanggal', $year)], 'nominal')
            ->orderByDesc('pagu_anggaran')
            ->get();

        $timeline = $this->projectQuery($year)
            ->orderByDesc('tanggal_mulai')
            ->orderByDesc('updated_at')
            ->limit(6)
            ->get();

        return view('public.transparency', [
            'stats' => $this->stats($year),
            'projects' => $projects,
            'timeline' => $timeline,
            'allocations' => $allocations,
            'selectedStatus' => $status,
            'selectedYear' => $year,
            'availableYears' => $this->availableYears(),
        ]);
    }

    public function news()
    {
        $posts = Berita::query()
            ->where('status', 'terbit')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(9);

        return view('public.news-index', compact('posts'));
    }

    public function newsDetail(string $slug)
    {
        $post = Berita::query()
            ->where('slug', $slug)
            ->where('status', 'terbit')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        return view('public.news-show', compact('post'));
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

    public function downloadPdf(Request $request): Response
    {
        $year = $this->selectedYear($request);
        $incomes = DanaMasuk::query()
            ->where('status', 'terverifikasi')
            ->whereYear('tanggal', $year)
            ->orderBy('tanggal')
            ->get();
        $expenses = Pengeluaran::query()
            ->with('kategoriAnggaran')
            ->where('status', 'terverifikasi')
            ->whereYear('tanggal', $year)
            ->orderBy('tanggal')
            ->get();
        $projects = $this->projectQuery($year)->orderBy('nama')->get();

        return Pdf::loadView('public.report-pdf', [
            'year' => $year,
            'stats' => $this->stats($year),
            'incomes' => $incomes,
            'expenses' => $expenses,
            'projects' => $projects,
        ])->setPaper('a4')->download("laporan-dana-kampung-mbu-{$year}.pdf");
    }

    private function stats(int $year): array
    {
        $income = (float) DanaMasuk::query()
            ->where('status', 'terverifikasi')
            ->whereYear('tanggal', $year)
            ->sum('nominal');
        $expense = (float) Pengeluaran::query()
            ->where('status', 'terverifikasi')
            ->whereYear('tanggal', $year)
            ->sum('nominal');
        $projectQuery = $this->projectQuery($year);

        return [
            'income' => $income,
            'expense' => $expense,
            'remaining' => $income - $expense,
            'absorption' => $income > 0 ? round(($expense / $income) * 100, 1) : 0,
            'projects' => (clone $projectQuery)->count(),
            'active_projects' => (clone $projectQuery)->where('status', 'berjalan')->count(),
            'completed_projects' => (clone $projectQuery)->where('status', 'selesai')->count(),
        ];
    }

    private function projectQuery(int $year): Builder
    {
        return ProyekKampung::query()
            ->with('kategoriAnggaran')
            ->where(function (Builder $query) use ($year) {
                $query->whereYear('tanggal_mulai', $year)
                    ->orWhere(function (Builder $undated) {
                        $undated->whereNull('tanggal_mulai')->whereNull('tanggal_selesai');
                    });
            });
    }

    private function selectedYear(Request $request): int
    {
        $years = $this->availableYears();
        $requested = (int) $request->query('tahun');

        return in_array($requested, $years, true) ? $requested : $years[0];
    }

    private function availableYears(): array
    {
        $years = collect()
            ->merge(DanaMasuk::query()->whereNotNull('tanggal')->pluck('tanggal'))
            ->merge(Pengeluaran::query()->whereNotNull('tanggal')->pluck('tanggal'))
            ->merge(ProyekKampung::query()->whereNotNull('tanggal_mulai')->pluck('tanggal_mulai'))
            ->map(fn ($date) => (int) substr((string) $date, 0, 4))
            ->filter()
            ->unique()
            ->sortDesc()
            ->values()
            ->all();

        return $years ?: [(int) now()->year];
    }

    private function databaseStatus(string $status): string
    {
        return match ($status) {
            'sedang-berjalan' => 'berjalan',
            default => $status,
        };
    }
}
