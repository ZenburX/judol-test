@parent

<div>
    <form method="post" action="/urls">
        @csrf
        <div class="mb-3">
            <label class="form-label">Daftar URL (1 per baris)</label>
            <textarea name="urls" class="form-control" rows="5"
                placeholder="https://example.com
https://web-kamu.com">{{ implode("\n", $urls ?? []) }}</textarea>
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
</div>