<?php
    $total = count($results);
    $judol = collect($results)->where('is_judol', true)->count();
    $bersih = $total - $judol;
    // shortcut: blur & kosong belum ada sumber datanya di result.json, tampil 0 sampai classifier menyimpannya
    $kosong = 0;
    $blur = 0;
?>
<div class="summary-grid">
    <div class="summary-card judol">
        <div class="summary-number"><?php echo e($judol); ?></div>
        <div class="summary-label">JUDOL</div>
    </div>
    <div class="summary-card bersih">
        <div class="summary-number"><?php echo e($bersih); ?></div>
        <div class="summary-label">BERSIH</div>
    </div>
    <div class="summary-card kosong">
        <div class="summary-number"><?php echo e($kosong); ?></div>
        <div class="summary-label">KOSONG</div>
    </div>
    <div class="summary-card blur">
        <div class="summary-number"><?php echo e($blur); ?></div>
        <div class="summary-label">BLUR</div>
    </div>
</div>

<?php if($total > 0): ?>
    <p>Total gambar diproses: <?php echo e($total); ?></p>
    <p>Persentase JUDOL: <?php echo e(number_format($judol / $total * 100, 1)); ?>%</p>
<?php endif; ?>
<?php /**PATH C:\File Rubenz TA\Projek Kecil2an\test\screenshot-test\resources\views/sections/home.blade.php ENDPATH**/ ?>