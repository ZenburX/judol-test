// Klasifikasi judol lokal: CLIP zero-shot via transformers.js + keyword DOM.
// Input: storage/screenshots/playwright/*.png (+ .boxes.json berisi bbox img/banner, .text.txt teks halaman).
// Output: storage/classification/result.json + ringkasan console.

const fs = require("fs");
const path = require("path");
const sharp = require("sharp");
const { pipeline, RawImage } = require("@huggingface/transformers");

const shotDir = path.resolve(__dirname, "../storage/screenshots/playwright");
const outDir = path.resolve(__dirname, "../storage/classification");
const THRESHOLD = 0.7;

// Keyword injeksi judol dari teks halaman
const JUDOL_WORDS = /\b(mahjong ways|slot gacor|slotgacor|maxwin|scatter hitam|judi bola|situs judi|casino|togel|rtp slot|poker|sbobet|bet77)\b/gi;

// Threshold keyword: >=2 kali muncul = injeksi judol
const KEYWORD_MIN = 2;

// Label judol & non-judol untuk CLIP
const LABELS_JUDOL = [
    "slot machine game interface, casino jackpot banner with gold coins and money",
    "gambling website popup, poker chips and dice",
];
const LABELS_NON_JUDOL = [
    "photograph of a laptop or computer screen",
    "dark themed video player or social media embed",
    "tourism or travel promotion banner, government website",
    "e-commerce or product sale banner",
    "plain text heading on a website, typography only",
];

// Tile 512 dengan overlap 128 (stride 384) supaya tidak ada banner terpotong di tengah.
const TILE = 512;
const OVERLAP = 128;

function makeTiles(width, height) {
    const stride = TILE - OVERLAP;
    const xs = [];
    for (let x = 0; x + 100 <= width; x += stride) xs.push(Math.min(x, Math.max(0, width - TILE)));
    const ys = [];
    for (let y = 0; y + 100 <= height; y += stride) ys.push(Math.min(y, Math.max(0, height - TILE)));
    return xs.flatMap((x) => ys.map((y) => ({ x, y })));
}

async function run() {
    fs.mkdirSync(outDir, { recursive: true });
    const force = process.argv.includes("--force");
    const outFile = path.join(outDir, "result.json");
    const prev = fs.existsSync(outFile) ? JSON.parse(fs.readFileSync(outFile, "utf8")) : [];
    const prevNames = new Set(prev.map((r) => r.gambar));
    const files = fs
        .readdirSync(shotDir)
        .filter((f) => f.endsWith(".png") && !f.startsWith("viewport") && (force || !prevNames.has(f)));

    if (files.length === 0) {
        console.log("SKIP  classify: semua PNG sudah ada di result.json (pakai --force untuk ulang semua).");
        return;
    }

    const model = await pipeline("zero-shot-image-classification", "Xenova/clip-vit-base-patch32");

    // hasil lama dipertahankan, entri baru ditambahkan di belakangnya
    const results = [...prev];

    for (const file of files) {
        const fullPath = path.join(shotDir, file);
        const meta = await sharp(fullPath).metadata();
        let crops = makeTiles(meta.width, meta.height).map((t) => ({ ...t, w: TILE, h: TILE, kind: "tile" }));

        // crop elemen img/banner dari bbox yang disimpan saat screenshot
        const boxesFile = fullPath.replace(/\.png$/, ".boxes.json");
        const elementBoxes = [];
        if (fs.existsSync(boxesFile)) {
            for (const b of JSON.parse(fs.readFileSync(boxesFile))) {
                elementBoxes.push({ ...b, kind: "element" });
                crops.push({ ...b, kind: "element" });
            }
        } else {
            console.warn(`WARN: ${boxesFile} tidak ada, jalankan ulang script screenshot.`);
        }

        let best = { score: 0, label: "", kind: "", region: null };
        let elScore = 0, tileScore = 0;
        for (const c of crops) {
            const left = Math.max(0, c.x);
            const top = Math.max(0, c.y);
            const width = Math.min(c.x + c.w, meta.width) - left;
            const height = Math.min(c.y + c.h, meta.height) - top;
            if (width <= 0 || height <= 0) continue;
            const buf = await sharp(fullPath).extract({ left, top, width, height }).resize(224, 224).png().toBuffer();
            const img = await RawImage.fromBlob(new Blob([buf]));
            const raw = await model(img, LABELS_JUDOL.concat(LABELS_NON_JUDOL));
            // skor judol = semua label judol vs semua label non-judol setelah softmax
            const sJudol = raw.filter((r) => LABELS_JUDOL.includes(r.label)).reduce((a, r) => a + r.score, 0);
            const region = { x: left, y: top, w: width, h: height };
            if (c.kind === "element" && sJudol > elScore) elScore = sJudol;
            if (c.kind === "tile" && sJudol > tileScore) tileScore = sJudol;
            if (sJudol > best.score) best = { score: sJudol, label: raw[0].label, kind: c.kind, region };
        }

        // keyword judol dari teks halaman
        const textFile = fullPath.replace(/\.png$/, ".text.txt");
        let keyword = 0;
        if (fs.existsSync(textFile)) {
            keyword = (fs.readFileSync(textFile, "utf8").match(JUDOL_WORDS) || []).length;
        }

        // logika deteksi: JUDOL jika keyword ditemukan (andalan utama),
        // ATAU CLIP element skor di atas threshold (fallback visual).
        const isJudol = keyword >= KEYWORD_MIN || elScore >= THRESHOLD;
        const res = {
            gambar: file,
            is_judol: isJudol,
            skor: Number(best.score.toFixed(3)),
            skor_element: Number(elScore.toFixed(3)),
            keyword_judol: keyword,
            area_teratas: best.kind,
            lokasi: best.score >= THRESHOLD ? best.region : null,
            label_teratas: best.label,
        };
        results.push(res);
        console.log(`${res.is_judol ? "JUDOL" : "BERSIH"}  ${file}  el=${res.skor_element} kw=${keyword}`);
    }

    fs.writeFileSync(outFile, JSON.stringify(results, null, 2));
    const nJudol = results.filter((r) => r.is_judol).length;
    console.log(`\nSelesai: ${nJudol}/${results.length} terdeteksi judol -> ${outFile}`);
}

run().catch((e) => { console.error(e); process.exit(1); });