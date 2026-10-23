// Screenshot full-page semua URL di urls.js pakai Puppeteer (Chrome).
const puppeteer = require("puppeteer");
const fs = require("fs");
const path = require("path");
const urls = require("./urls.cjs");

const outDir = path.resolve(__dirname, "../storage/screenshots/puppeteer");

(async () => {
    for (const url of urls) {
        const browser = await puppeteer.launch();
        const page = await browser.newPage();
        try {
            await page.goto(url, { waitUntil: "domcontentloaded", timeout: 60000 });
            await new Promise((r) => setTimeout(r, 3000));
            fs.mkdirSync(outDir, { recursive: true });
            const name = new URL(url).hostname.replace(/\./g, "_");
            const file = path.join(outDir, `${name}.png`);
            await page.screenshot({ path: file, fullPage: true });
            console.log(`OK   puppeteer: ${url} -> ${file}`);
        } catch (e) {
            console.error(`FAIL puppeteer: ${url} -> ${e.message}`);
        } finally {
            await browser.close();
        }
    }
})();
