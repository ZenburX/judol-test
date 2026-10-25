<?php

use Illuminate\Support\Facades\Route;
use Symfony\Component\Process\Process;

$scriptsPath = base_path('scripts');
$shotDir = storage_path('screenshots/playwright');
$classificationFile = storage_path('classification/result.json');

// Helper: jalankan script node sinkron, kembalikan output terakhir
function runNode(string $script): array
{
    // env lengkap dari php -S, Node crash (CSPRNG) dengan env parsial
    $env = getenv();

    $process = new Process(['node', $script], base_path(), $env);
    $process->setTimeout(600);
    $process->run();
    $lines = array_filter(explode("\n", $process->getOutput().$process->getErrorOutput()), fn ($l) => trim($l) !== '');
    return ['ok' => $process->isSuccessful(), 'log' => array_values($lines)];
}

// Dashboard / Home
Route::get('/', function () use ($classificationFile, $shotDir) {
    $results = file_exists($classificationFile) ? json_decode(file_get_contents($classificationFile), true) : [];
    $images = collect(glob($shotDir.'/*.png'))->map(fn ($p) => basename($p));
    $urls = [];
    if (file_exists(base_path('scripts/urls.cjs'))) {
        preg_match_all('/"(https?:[^"]+)"/', file_get_contents(base_path('scripts/urls.cjs')), $m);
        $urls = $m[1];
    }
    return view('dashboard', ['content' => 'home', 'results' => $results, 'images' => $images, 'urls' => $urls]);
});

// Jalankan screenshot / klasifikasi
Route::post('/run/{type}', function (string $type) {
    $script = match ($type) {
        'screenshot' => 'scripts/screenshot-playwright.cjs',
        'classify' => 'scripts/classify-judol.cjs',
        default => abort(400),
    };
    $out = runNode($script);
    return back()->with('run_log', $out['log'])->with('run_ok', $out['ok']);
});

// Simpan daftar URL
Route::post('/urls', function (\Illuminate\Http\Request $request) {
    $urls = collect(preg_split('/[\r\n]+/', trim($request->input('urls', '')))
        ->map(fn ($u) => trim($u))
        ->filter(fn ($u) => filter_var($u, FILTER_VALIDATE_URL))
        ->values());
    $body = "// Daftar web yang mau di-screenshot. Di-generate otomatis dari dashboard.\nmodule.exports = "
        .json_encode($urls->all(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).";\n";
    file_put_contents(base_path('scripts/urls.cjs'), $body);
    return back()->with('urls_saved', count($urls).' URL tersimpan');
});

// Serve screenshot (di luar public, biar tidak perlu storage:link)
Route::get('/shots/{file}', function (string $file) use ($shotDir) {
    abort_if(basename($file) !== $file || !str_ends_with($file, '.png'), 404);
    abort_unless(file_exists($path = $shotDir.'/'.$file), 404);
    return response()->file($path);
});

// Laporan
Route::get('/laporan', function () use ($classificationFile) {
    $results = file_exists($classificationFile) ? json_decode(file_get_contents($classificationFile), true) : [];
    return view('dashboard', ['content' => 'laporan', 'results' => $results]);
});

// Histori
Route::get('/histori', function () {
    // Baca riwayat dari folder atau hasil terakhir
    $shotsDir = storage_path('screenshots/playwright');
    $histori = [];
    $pngFiles = glob($shotsDir.'/*.png');
    foreach ($pngFiles as $png) {
        $meta = pathinfo(basename($png));
        $ts = filemtime($png);
        $histori[] = [
            'timestamp' => date('Y-m-d H:i', $ts),
            'url' => str_replace('.png', '', $meta['filename']),
            'gambar' => basename($png),
        ];
    }
    // Urutkan terbaru dulu
    usort($histori, fn($a, $b) => $b['timestamp'] <=> $a['timestamp']);
    return view('dashboard', ['content' => 'histori', 'results' => $histori]);
});

// Setting
Route::get('/setting', function () use ($classificationFile, $shotDir) {
    $results = file_exists($classificationFile) ? json_decode(file_get_contents($classificationFile), true) : [];
    $images = collect(glob($shotDir.'/*.png'))->map(fn ($p) => basename($p));
    return view('dashboard', ['content' => 'setting', 'results' => $results, 'images' => $images]);
});

// Ekspor CSV
Route::get('/export-csv', function ($classificationFile) use ($shotDir) {
    $results = file_exists($classificationFile) ? json_decode(file_get_contents($classificationFile), true) : [];
    if (empty($results)) {
        return back()->with('flash', 'Belum ada data untuk dieksport.');
    }
    $fp = fopen('storage:classification/export.csv', 'w');
    fputcsv($fp, ['gambar', 'is_judol', 'skor', 'keyword_judol', 'lokasi_x', 'lokasi_y', 'label_teratas']);
    foreach ($results as $r) {
        $loc = $r['lokasi'] ?? null;
        fputcsv($fp, [
            $r['gambar'],
            $r['is_judol'] ? '1' : '0',
            $r['skor'] ?? 0,
            $r['keyword_judol'] ?? 0,
            $loc['x'] ?? '',
            $loc['y'] ?? '',
            $r['label_teratas'] ?? '',
        ]);
    }
    fclose($fp);
    return response()->download(storage_path('app/classification/export.csv'))
        ->deleteFileAfterSend(true);
});
