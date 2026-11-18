// Prueba unitaria del $.ajaxPrefilter de assets/js/main.js (sin navegador).
const fs = require('fs'), path = require('path'), assert = require('assert');
const src = fs.readFileSync(path.join(__dirname, '..', 'assets/js/main.js'), 'utf8');
const m = src.match(/\$\.ajaxPrefilter\(function[\s\S]*?\n\}\);\n/);
assert(m, 'no se encontró el ajaxPrefilter en main.js');

let prefilter;
const meta = { length: 1, data: () => 'csrf_test_name', attr: () => 'TOK123' };
const $ = Object.assign(() => meta, { ajaxPrefilter: f => { prefilter = f; } });
new Function('$', m[0])($);

class FD { constructor(){ this.m = {}; } append(k,v){ this.m[k]=v; } has(k){ return k in this.m; } }
global.window = { FormData: FD }; global.FormData = FD;
let ok = 0, fail = 0;
const t = (name, fn) => { try { fn(); console.log('PASS ' + name); ok++; } catch (e) { console.log('FAIL ' + name + ' -> ' + e.message); fail++; } };

t('POST con data=undefined ($.post(url, null)) agrega el token', () => { const o = { type: 'POST' }; prefilter(o); assert.strictEqual(o.data, 'csrf_test_name=TOK123'); });
t('POST con string serializado agrega el token', () => { const o = { type: 'POST', data: 'a=1&b=2' }; prefilter(o); assert.strictEqual(o.data, 'a=1&b=2&csrf_test_name=TOK123'); });
t('POST con string que ya trae token no lo duplica', () => { const o = { type: 'POST', data: 'a=1&csrf_test_name=OLD' }; prefilter(o); assert.strictEqual(o.data, 'a=1&csrf_test_name=OLD'); });
t('POST con FormData agrega el token', () => { const o = { type: 'POST', data: new FD() }; prefilter(o); assert.strictEqual(o.data.m.csrf_test_name, 'TOK123'); });
t('GET no se modifica', () => { const o = { type: 'GET', data: 'a=1' }; prefilter(o); assert.strictEqual(o.data, 'a=1'); });
t('POST cross-domain no filtra el token', () => { const o = { type: 'POST', crossDomain: true }; prefilter(o); assert.strictEqual(o.data, undefined); });
console.log(`JS prefilter: ${ok} ok, ${fail} fallas`);
process.exit(fail ? 1 : 0);
