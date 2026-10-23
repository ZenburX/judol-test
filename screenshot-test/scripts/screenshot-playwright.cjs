// Screenshot full-page semua URL di urls.js pakai Playwright (Chromium).
const { chromium } = require("playwright");
const fs = require("fs");
const path = require("path");
const urls = require("./urls.cjs");

const outDir = path.resolve(__dirname, "../storage/screenshots/playwright");

(async () => {
    for (const url of urls) {
        const browser = await chromium.launch();
        const page = await browser.newPage({ viewport: { width: 1280, height: 720 } });
        try {
            await page.goto(url, { waitUntil: "domcontentloaded", timeout: 60000 });
            await page.waitForTimeout(3000);
            fs.mkdirSync(outDir, { recursive: true });
            const name = new URL(url).hostname.replace(/\./g, "_");
            const file = path.join(outDir, `${name}.png`);
            // scroll bertahap supaya img lazy-load sempat ter-load sebelum screenshot
            await page.evaluate(async () => {
                for (let y = 0; y < document.body.scrollHeight; y += 600) {
                    window.scrollTo(0, y);
                    await new Promise((r) => setTimeout(r, 150));
                }
                window.scrollTo(0, 0);
                await new Promise((r) => setTimeout(r, 500));
            });
            await page.screenshot({ path: file, fullPage: true });
            // simpan bbox gambar/banner + status DOM untuk classifier
            const boxes = await page.evaluate(() => {
                const imgs = [...document.querySelectorAll("img")].map((img) => {
                    const b = img.getBoundingClientRect();
                    let status = "ok";
                    if (!img.complete || img.naturalWidth === 0) status = img.getAttribute("src") ? "broken_src" : "empty";
                    return { el: img, status };
                });
                // card non-img (banner/slot/ads) dianggap card_kosong jika tidak ada img ok di dalamnya
                const cards = [...document.querySelectorAll("[class*='banner'], [class*='slot'], [class*='ads'], iframe")].map((el) => {
                    const b = el.getBoundingClientRect();
                    const hasOkImg = el.querySelector("img")?.complete && el.querySelector("img")?.naturalWidth > 0;
                    return { el, status: hasOkImg || el.tagName === "IFRAME" ? "ok" : "card_kosong" };
                });
                return [...imgs, ...cards]
                    .map(({ el, status }) => { const b = el.getBoundingClientRect(); return { x: b.x, y: b.y, w: b.width, h: b.height, status }; })
                    .filter((b) => b.w >= 120 && b.h >= 80)
                    .map((b) => ({ x: Math.round(b.x), y: Math.round(b.y), w: Math.round(b.w), h: Math.round(b.h), status: b.status }));
            });
            fs.writeFileSync(path.join(outDir, `${name}.boxes.json`), JSON.stringify(boxes));
            // simpan teks visible halaman untuk deteksi keyword judol
            const text = await page.evaluate(() => document.body?.innerText?.slice(0, 200000) || "");
            fs.writeFileSync(path.join(outDir, `${name}.text.txt`), text);
            const nKosong = boxes.filter((b) => b.status !== "ok").length;
            console.log(`OK   playwright: ${url} -> ${file} (${boxes.length} area, ${nKosong} bermasalah)`);
        } catch (e) {
            console.error(`FAIL playwright: ${url} -> ${e.message}`);
        } finally {
            await browser.close();
        }
    }
})();
