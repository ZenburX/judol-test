@judol=$judol; $bersih=$bersih; $kosong=$kosong; $blur=$blur; $total=$total;
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
    <p>Persentase JUDOL: {{ number_format($total > 0 ? $judol / $total * 100, 1) : 0 }}%</p>
@endif