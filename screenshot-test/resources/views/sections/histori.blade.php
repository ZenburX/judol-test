@php $total = count($results); @endphp
@if ($total == 0)
    <div class="no-data">Belada ada riwayat eksekusi. Jalankan screenshot atau klasifikasi terlebih dahulu.</div>
@else
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Aksi</th>
                <th>URL</th>
                <th>Gambar</th>
                <th>Hasil</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($results as $r)
            <tr>
                <td>{{ substr($r['timestamp'] ?? now()->toDateString(), 0, 10) }}</td>
                <td>
                    @if (str_contains($r['gambar'], 'screenshot'))
                        <span class="btn-action tiny" style="font-size:.7rem;padding:.2rem .4rem;">ss</span>
                    @endif
                    @if (str_contains($r['gambar'], 'classify'))
                        <span class="btn-action tiny" style="font-size:.7rem;padding:.2rem .4rem;">kl</span>
                    @endif
                </td>
                <td>{{ substr($r['url'] ?? '-', 0, 40) }}...</td>
                <td><small>{{ $r['gambar'] }}</small></td>
                <td>
                    @if ($r['is_judol'] ?? false)
                        <span class="badge judol" style="font-size:.7rem;">JUDOL</span>
                    @else
                        <span class="badge bersih" style="font-size:.7rem;">BERSIH</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="no-data">Belum ada riwayat eksekusi.</td></tr>
        @endforelse
        </tbody>
    </table>
@endif