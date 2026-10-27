<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Monitor Judol — Dashboard</title>
    @include('sections.styles')
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
    @if ($content == 'laporan')
        @include('sections.laporan')
    @elseif ($content == 'histori')
        @include('sections.histori')
    @elseif ($content == 'setting')
        @include('sections.setting')
    @else
        @include('sections.home')
    @endif
</div>

</body>
</html>