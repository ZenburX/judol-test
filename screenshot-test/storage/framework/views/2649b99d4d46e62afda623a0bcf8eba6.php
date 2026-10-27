<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Monitor Judol — Dashboard</title>
    <?php echo $__env->make('sections.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>
<body>

<nav class="navbar">
    <a class="logo" href="/">Monitor Judol</a>
    <div class="nav-links">
        <a href="/" class="active">Dashboard</a>
        <a href="/laporan">Laporan</a>
        <a href="/histori">Histori</a>
        <a href="/setting">Setting</a>
    </div>
</nav>

<div class="container">
    <?php if($content == 'laporan'): ?>
        <?php echo $__env->make('sections.laporan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php elseif($content == 'histori'): ?>
        <?php echo $__env->make('sections.histori', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php elseif($content == 'setting'): ?>
        <?php echo $__env->make('sections.setting', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php else: ?>
        <?php echo $__env->make('sections.home', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>
</div>

</body>
</html><?php /**PATH C:\File Rubenz TA\Projek Kecil2an\test\screenshot-test\resources\views/dashboard.blade.php ENDPATH**/ ?>