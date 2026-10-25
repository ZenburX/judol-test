<?php echo \Illuminate\View\Factory::parentPlaceholder(''); ?>

<div>
    <form method="post" action="/urls">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label class="form-label">Daftar URL (1 per baris)</label>
            <textarea name="urls" class="form-control" rows="5"
                placeholder="https://example.com
https://web-kamu.com"><?php echo e(implode("\n", $urls ?? [])); ?></textarea>
        </div>
        <button type="submit" class="btn-action">Simpan URL</button>
    </form>
</div>
<div>
    <h3>Informasi Sistem</h3>
    <p>Script screenshot: <code>scripts/screenshot-playwright.cjs</code></p>
    <p>Script klasifikasi: <code>scripts/classify-judol.cjs</code></p>
    <p>Model CLIP: <code>Xenova/clip-vit-base-patch32</code></p>
    <p>Output: <code>storage/classification/result.json</code></p>
</div><?php /**PATH C:\File Rubenz TA\Projek Kecil2an\test\screenshot-test\resources\views/sections/setting.blade.php ENDPATH**/ ?>