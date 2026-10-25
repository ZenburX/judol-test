<?php $total = count($results); $filtered = $results; ?>
<?php if($total == 0): ?>
    <div class="no-data">Belada ada riwayat eksekusi. Jalankan screenshot atau klasifikasi terlebih dahulu.</div>
<?php else: ?>
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
        <?php $__empty_1 = true; $__currentLoopData = $filtered; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($r['gambar']); ?></td>
                <td>
                    <span class="badge <?php echo e($r['is_judol'] ? 'judol' : 'bersih'); ?>">
                        <?php echo e($r['is_judol'] ? 'JUDOL' : 'BERSIH'); ?>

                    </span>
                </td>
                <td><?php echo e(number_format($r['skor'] ?? 0, 3)); ?></td>
                <td>
                    <?php if(!empty($r['keyword_judol'])): ?>
                        <?php echo e($r['keyword_judol']); ?> kata
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td><code>x=<?php echo e($r['lokasi']['x'] ?? 0); ?>, y=<?php echo e($r['lokasi']['y'] ?? 0); ?></code></td>
                <td>
                    <a class="btn-action" href="/shots/<?php echo e($r['gambar']); ?>" target="_blank">Lihat</a>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6" class="no-data">Belum ada hasil. Jalankan screenshot & klasifikasi.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
<?php endif; ?><?php /**PATH C:\File Rubenz TA\Projek Kecil2an\test\screenshot-test\resources\views/sections/laporan.blade.php ENDPATH**/ ?>