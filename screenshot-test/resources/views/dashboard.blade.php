<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Monitor Judol — Dashboard</title>
    <style>
        body { font-family: system-ui, sans-serif; margin: 2rem; max-width: 1100px; color: #1b1b1b; }
        h1 { font-size: 1.4rem; } h2 { font-size: 1.1rem; margin-top: 2.5rem; }
        button { padding: .5rem 1rem; border: 1px solid #555; background: #fff; cursor: pointer; border-radius: 6px; }
        button:hover { background: #f0f0f0; }
        table { border-collapse: collapse; width: 100%; margin-top: .75rem; }
        th, td { border: 1px solid #ddd; padding: .4rem .6rem; font-size: .85rem; text-align: left; }
        .badge { display: inline-block; padding: .15rem .5rem; border-radius: 999px; color: #fff; font-size: .75rem; }
        .badge.judol { background: #c0392b; } .badge.bersih { background: #1e8e3e; }
        .badge.kosong { background: #7f8c8d; } .badge.blur { background: #2471a3; }
        textarea { width: 100%; min-height: 80px; font-family: monospace; font-size: .85rem; padding: .5rem; }
        pre.log { background: #f4f4f4; padding: .75rem; font-size: .78rem; max-height: 200px; overflow: auto; white-space: pre-wrap; }
        .thumb { width: 90px; cursor: zoom-in; }
        details img { max-width: 100%; border: 1px solid #ccc; margin-top: .5rem; }
        .flash { padding: .6rem .9rem; border-radius: 6px; background: #e7f3e7; margin-bottom: 1rem; }
    </style>
</head>
<body>
<h1>🖼️ Monitor Judol — Dashboard</h1>

@if (session('urls_saved'))<div class="flash">{{ session('urls_saved') }}</div>@endif
@if (session('run_ok') !== null)
    <div class="flash" style="{{ session('run_ok') ? '' : 'background:#fbeaea' }}">
        {{ session('run_ok') ? 'Selesai.' : 'Selesai dengan error (cek log).' }}
    </div>
    <pre class="log">{{ implode("\n", session('run_log', [])) }}</pre>
@endif

<h2>1. Daftar URL</h2>
<form method="post" action="/urls">
    @csrf
    <textarea name="urls" placeholder="https://contoh.com">{{ implode("\n", $urls) }}</textarea>
    <button type="submit">Simpan URL</button>
</form>

<h2>2. Jalankan</h2>
<form method="post" action="/run/screenshot" style="display:inline">@csrf
    <button type="submit">📸 Screenshot (Playwright)</button>
</form>
<form method="post" action="/run/classify" style="display:inline;margin-left:.5rem">@csrf
    <button type="submit">🤖 Klasifikasi Judol</button>
</form>

<h2>3. Screenshot</h2>
<table>
    <tr><th>Gambar</th><th>Preview</th></tr>
    @forelse ($images as $img)
        <tr>
            <td>{{ $img }}</td>
            <td><details><summary><img class="thumb" src="/shots/{{ $img }}" alt=""></summary>
                <img src="/shots/{{ $img }}" alt="{{ $img }}"></details></td>
        </tr>
    @empty
        <tr><td colspan="2">Belum ada screenshot. Jalankan tombol di atas.</td></tr>
    @endforelse
</table>

<h2>4. Hasil Klasifikasi</h2>
<table>
    <tr><th>Gambar</th><th>Status</th><th>Skor</th><th>Area</th><th>Lokasi</th><th>Temuan</th><th>Preview</th></tr>
    @forelse ($results as $r)
        <tr>
            <td>{{ $r['gambar'] }}</td>
            <td><span class="badge {{ $r['is_judol'] ? 'judol' : 'bersih' }}">{{ $r['is_judol'] ? 'JUDOL' : 'BERSIH' }}</span></td>
            <td>{{ $r['skor'] }}</td>
            <td>{{ $r['area_teratas'] }}</td>
            <td><code>x={{ $r['lokasi']['x'] }}, y={{ $r['lokasi']['y'] }}, {{ $r['lokasi']['w'] }}×{{ $r['lokasi']['h'] }}</code></td>
            <td>
                @forelse ($r['temuan'] ?? [] as $t)
                    <span class="badge {{ $t['kategori'] === 'card_kosong' ? 'kosong' : 'blur' }}">{{ $t['kategori'] === 'card_kosong' ? 'KARTU KOSONG' : 'BLUR' }}</span>
                    <span style="font-size:.7rem">{{ $t['detail'] }} · y={{ $t['lokasi']['y'] }}</span><br>
                @empty
                    <span style="font-size:.8rem">Tanpa temuan</span>
                @endforelse
            </td>
            <td><details><summary>lihat</summary>
                <img src="/shots/{{ $r['gambar'] }}" alt="">
                <p>Area dicurigai: {{ $r['lokasi']['x'] }},{{ $r['lokasi']['y'] }} (tile atas kiri)</p>
            </details></td>
        </tr>
    @empty
        <tr><td colspan="7">Belum ada hasil. Jalankan klasifikasi.</td></tr>
    @endforelse
</table>
</body>
</html>
