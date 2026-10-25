<?php $total = count($results); ?>
<?php if($total == 0): ?>
    <div class="no-data">Belada ada riwayat eksekusi. Jalankan screenshot atau klasifikasi terlebih dahulu.</div>
<?php else: ?>
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
        <?php $__empty_1 = true; $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e(substr($r['timestamp'] ?? now()->toDateString(), 0, 10)); ?></td>
                <td>
                    <?php if(str_contains($r['gambar'], 'screenshot')): ?>
                        <span class="btn-action tiny" style="font-size:.7rem;padding:.2rem .4rem;">ss</span>
                    <?php endif; ?>
                    <?php if(str_contains($r['gambar'], 'classify')): ?>
                        <span class="btn-action tiny" style="font-size:.7rem;padding:.2rem .4rem;">kl</span>
                    <?php endif; ?>
                </td>
                <td><?php echo e(substr($r['url'] ?? '-', 0, 40)); ?>...</td>
                <td><small><?php echo e($r['gambar']); ?></small></td>
                <td>
                    <?php if($r['is_judol'] ?? false): ?>
                        <span class="badge judol" style="font-size:.7rem;">JUDOL</span>
                    <?php else: ?>
                        <span class="badge bersih" style="font-size:.7rem;">BERSIH</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="5" class="no-data">Belum ada riwayat eksekusi.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
<?php endif; ?><?php /**PATH C:\File Rubenz TA\Projek Kecil2an\test\screenshot-test\resources\views/sections/histori.blade.php ENDPATH**/ ?>