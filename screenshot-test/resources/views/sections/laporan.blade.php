@php $total = count($results); $filtered = $results; @endphp
@if ($total == 0)
    <div class="no-data">Belada ada riwayat eksekusi. Jalankan screenshot atau klasifikasi terlebih dahulu.</div>
@else
    <table>
        <thead>
            <tr>
                <th>Gambar</th>
                <th>Status</th>
                <th>Skor</th>
                <th>Keyword</th>
                <th>Lokasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($filtered as $r)
            <tr>
                <td>{{ $r['gambar'] }}</td>
                <td>
                    <span class="badge {{ $r['is_judol'] ? 'judol' : 'bersih' }}">
                        {{ $r['is_judol'] ? 'JUDOL' : 'BERSIH' }}
                    </span>
                </td>
                <td>{{ number_format($r['skor'] ?? 0, 3) }}</td>
                <td>
                    @if (!empty($r['keyword_judol']))
                        {{ $r['keyword_judol'] }} kata
                    @else
                        -
                    @endif
                </td>
                <td><code>x={{ $r['lokasi']['x'] ?? 0 }}, y={{ $r['lokasi']['y'] ?? 0 }}</code></td>
                <td>
                    <a class="btn-action" href="/shots/{{ $r['gambar'] }}" target="_blank">Lihat</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="no-data">Belum ada hasil. Jalankan screenshot & klasifikasi.</td></tr>
        @endforelse
        </tbody>
    </table>
@endif