import fs from 'node:fs/promises';
import path from 'node:path';

const CDP = 'http://127.0.0.1:9222';
const BASE = 'http://127.0.0.1:8017';
const OUT = 'D:/profilkampung_screenshots_rapi';
const VIEW = { width: 1366, height: 768 };
const STEP = 680;
const sleep = ms => new Promise(r => setTimeout(r, ms));

function slugify(s) {
  return (s || 'halaman')
    .toLowerCase()
    .normalize('NFKD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-+|-+$/g, '')
    .slice(0, 70) || 'halaman';
}

async function getJson(url, opts = {}) {
  const res = await fetch(url, opts);
  if (!res.ok) throw new Error(`${res.status} ${res.statusText} for ${url}`);
  return await res.json();
}

async function connect() {
  let pages = await getJson(`${CDP}/json`);
  let page = pages.find(p => p.type === 'page' && p.url.startsWith(BASE)) || pages.find(p => p.type === 'page');
  if (!page) page = await getJson(`${CDP}/json/new?${encodeURIComponent(BASE)}`, { method: 'PUT' });
  const ws = new WebSocket(page.webSocketDebuggerUrl);
  let seq = 0;
  const pending = new Map();
  ws.addEventListener('message', ev => {
    const msg = JSON.parse(ev.data);
    if (msg.id && pending.has(msg.id)) {
      const { resolve, reject } = pending.get(msg.id);
      pending.delete(msg.id);
      msg.error ? reject(new Error(JSON.stringify(msg.error))) : resolve(msg.result);
    }
  });
  await new Promise((resolve, reject) => {
    ws.addEventListener('open', resolve, { once: true });
    ws.addEventListener('error', reject, { once: true });
  });
  const send = (method, params = {}) => new Promise((resolve, reject) => {
    const id = ++seq;
    pending.set(id, { resolve, reject });
    ws.send(JSON.stringify({ id, method, params }));
  });
  return { send, close: () => ws.close() };
}

async function waitReady(send) {
  for (let i = 0; i < 80; i++) {
    const r = await send('Runtime.evaluate', { expression: 'document.readyState', returnByValue: true });
    if (r.result.value === 'complete') return;
    await sleep(100);
  }
}

async function evalValue(send, expression) {
  const r = await send('Runtime.evaluate', { expression, returnByValue: true, awaitPromise: true });
  if (r.exceptionDetails) throw new Error(JSON.stringify(r.exceptionDetails));
  return r.result.value;
}

function normalizeUrl(raw) {
  try {
    const u = new URL(raw, BASE);
    if (u.origin !== BASE) return null;
    if (['mailto:', 'tel:', 'javascript:'].includes(u.protocol)) return null;
    if (u.hash) u.hash = '';
    if (/\.(pdf|jpg|jpeg|png|gif|svg|webp|css|js|zip)$/i.test(u.pathname)) return null;
    if (u.pathname === '/laporan/pdf') return null; // download endpoint, not an HTML page
    return u.href.replace(/\/$/, '/') ;
  } catch { return null; }
}

async function discover(send, seeds) {
  const seen = new Set();
  const queue = [...seeds];
  const result = [];
  while (queue.length && result.length < 80) {
    const url = queue.shift();
    if (!url || seen.has(url)) continue;
    seen.add(url);
    await send('Page.navigate', { url });
    await waitReady(send);
    await sleep(350);
    const statusText = await evalValue(send, `document.body ? document.body.innerText.slice(0,200) : ''`);
    const title = await evalValue(send, `document.title || location.pathname`);
    if (/404|Not Found|500|Server Error/i.test(`${title} ${statusText}`)) continue;
    result.push({ url, title });
    const links = await evalValue(send, `Array.from(document.querySelectorAll('a[href]')).map(a => a.href)`);
    for (const raw of links || []) {
      const n = normalizeUrl(raw);
      if (n && !seen.has(n) && !queue.includes(n)) queue.push(n);
    }
  }
  return result;
}

async function capturePage(send, item, index, manifest) {
  await send('Page.bringToFront');
  await send('Emulation.setDeviceMetricsOverride', { width: VIEW.width, height: VIEW.height, deviceScaleFactor: 1, mobile: false });
  await send('Page.navigate', { url: item.url });
  await waitReady(send);
  await sleep(700);

  // Mouse movement visible in page hover/cursor stream. Bukan cuma diam kayak screenshot kuburan.
  for (const [x, y] of [[90,90], [420,120], [760,170], [1120,220], [650,420]]) {
    await send('Input.dispatchMouseEvent', { type: 'mouseMoved', x, y, button: 'none' });
    await sleep(100);
  }

  const info = await evalValue(send, `(() => {
    const de = document.documentElement, b = document.body;
    return {
      title: document.title || '',
      h1: (document.querySelector('h1')?.innerText || document.querySelector('h2')?.innerText || '').trim(),
      path: location.pathname,
      height: Math.max(de.scrollHeight, b?.scrollHeight || 0, de.clientHeight),
      width: Math.max(de.scrollWidth, b?.scrollWidth || 0, de.clientWidth)
    };
  })()`);
  const labelBase = slugify(info.h1 || info.title || item.title || info.path);
  const maxY = Math.max(0, info.height - VIEW.height);
  const scrolls = [0];
  for (let y = STEP; y < maxY; y += STEP) scrolls.push(y);
  if (maxY > 120 && !scrolls.includes(maxY)) scrolls.push(maxY);

  let part = 0;
  for (const y of scrolls) {
    part++;
    await evalValue(send, `window.scrollTo(0, ${Math.round(y)}); true`);
    await sleep(450);
    await send('Input.dispatchMouseEvent', { type: 'mouseMoved', x: 120 + (part * 120) % 1000, y: 160 + (part * 80) % 420, button: 'none' });
    await sleep(120);
    const posName = part === 1 ? 'atas' : (part === scrolls.length ? 'bawah' : `tengah-${part-1}`);
    const file = `${String(index).padStart(2,'0')}-${labelBase}-${String(part).padStart(2,'0')}-${posName}.png`;
    const shot = await send('Page.captureScreenshot', { format: 'png', fromSurface: true, captureBeyondViewport: false });
    await fs.writeFile(path.join(OUT, file), Buffer.from(shot.data, 'base64'));
    manifest.screenshots.push({ page_index: index, url: item.url, title: info.title, h1: info.h1, part, scrollY: Math.round(y), file });
  }
}

async function main() {
  await fs.rm(OUT, { recursive: true, force: true });
  await fs.mkdir(OUT, { recursive: true });
  const c = await connect();
  const send = c.send;
  await send('Page.enable');
  await send('Runtime.enable');
  try {
    const seeds = [
      '/', '/transparansi', '/berita', '/laporan-warga', '/kontak', '/support', '/forgot-password', '/kebijakan-privasi', '/peta-situs', '/login'
    ].map(p => BASE + p);
    const pages = await discover(send, seeds);
    // Keep stable human order: seeded routes first, then discovered detail pages.
    const order = new Map(seeds.map((u, i) => [u.replace(/\/$/, '/'), i]));
    pages.sort((a,b) => (order.get(a.url) ?? 999) - (order.get(b.url) ?? 999) || a.url.localeCompare(b.url));
    const manifest = { base: BASE, viewport: VIEW, output: OUT, pages: pages, screenshots: [] };
    let idx = 0;
    for (const p of pages) {
      idx++;
      console.log(`CAPTURE ${idx}/${pages.length}: ${p.url} :: ${p.title}`);
      await capturePage(send, p, idx, manifest);
    }
    await fs.writeFile(path.join(OUT, 'manifest.json'), JSON.stringify(manifest, null, 2), 'utf8');
    console.log(`DONE pages=${pages.length} screenshots=${manifest.screenshots.length} out=${OUT}`);
  } finally {
    c.close();
  }
}

main().catch(err => { console.error(err); process.exit(1); });
