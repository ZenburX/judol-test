@php
    $total = count($results);
    $judol = collect($results)->where('is_judol', true)->count();
    $bersih = $total - $judol;
    // shortcut: blur & kosong belum ada sumber datanya di result.json, tampil 0 sampai classifier menyimpannya
    $kosong = 0;
    $blur = 0;
@endphp
<div class="summary-grid">
    <div class="summary-card judol">
        <div class="summary-number">{{ $judol }}</div>
        <div class="summary-label">JUDOL</div>
    </div>
    <div class="summary-card bersih">
        <div class="summary-number">{{ $bersih }}</div>
        <div class="summary-label">BERSIH</div>
    </div>
    <div class="summary-card kosong">
        <div class="summary-number">{{ $kosong }}</div>
        <div class="summary-label">KOSONG</div>
    </div>
    <div class="summary-card blur">
        <div class="summary-number">{{ $blur }}</div>
        <div class="summary-label">BLUR</div>
    </div>
</div>

@if ($total > 0)
    <p>Total gambar diproses: {{ $total }}</p>
    <p>Persentase JUDOL: {{ number_format($judol / $total * 100, 1) }}%</p>
@endif

<div>
    <h3 style="margin:1.5rem 0 .5rem;">Link Terpantau</h3>
    @if (session('run_log'))
        <pre style="max-height:12rem;overflow:auto;background:#f6f8fa;padding:.5rem .75rem;border-radius:6px;font-size:.75rem;margin-bottom:1rem;">{{ implode("\n", session('run_log')) }}</pre>
    @endif
    @if (session('urls_saved'))
        <p style="color:#157347;margin-bottom:1rem;">{{ session('urls_saved') }}</p>
    @endif

    <form method="post" action="/urls">
        @csrf
        <div class="mb-3">
            <label class="form-label">Tambah link baru (1 per baris)</label>
            <textarea name="urls" class="form-control" rows="4"
                placeholder="https://example.com
https://web-kamu.com"></textarea>
        </div>
        <button type="submit" class="btn-action">Simpan Link</button>
    </form>

    <form method="post" action="/run/all" style="margin-top:1rem;">
        @csrf
        <button type="submit" class="btn-action">Cek Link Baru</button>
    </form>
    <form method="post" action="/run/all-force" style="margin-top:.5rem;">
        @csrf
        <button type="submit" class="btn-action">Rescan Semua</button>
    </form>

    <table style="width:100%;margin-top:1rem;">
        <thead>
            <tr>
                <th>URL</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($urls as $u)
            @php
                $png = str_replace('.', '_', parse_url($u, PHP_URL_HOST) ?? '') . '.png';
                $r = collect($results)->firstWhere('gambar', $png);
            @endphp
            <tr>
                <td><small>{{ $u }}</small></td>
                <td>
                    @if ($r)
                        <span class="badge {{ $r['is_judol'] ? 'judol' : 'bersih' }}" style="font-size:.7rem;">
                            {{ $r['is_judol'] ? 'JUDOL' : 'BERSIH' }}
                        </span>
                    @elseif ($images->contains($png))
                        <span class="badge kosong" style="font-size:.7rem;">Screenshot saja</span>
                    @else
                        <span class="badge blur" style="font-size:.7rem;">Belum diproses</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="2" class="no-data">Belum ada link. Tambahkan di atas lalu klik Cek Link Baru.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
