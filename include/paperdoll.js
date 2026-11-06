(function () {
    'use strict';

    var cataloguePromise, images = {}, layers = {}, layerKeys = [], editor;
    var slots = {eyes: '眼镜', head: '帽子与头饰', neck: '颈部饰品', back: '背部饰品', waist: '腰部饰品', legs: '下身饰品'};
    var categories = {hair: '发型', top: '上身', bottom: '下身'};

    function copy(value) { return JSON.parse(JSON.stringify(value)); }
    function request(url, body) {
        return new Promise(function (resolve, reject) {
            var xhr = new XMLHttpRequest();
            xhr.open(body ? 'POST' : 'GET', url, true);
            xhr.timeout = 15000;
            if (body) xhr.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
            xhr.onload = function () {
                var data;
                try { data = JSON.parse(xhr.responseText); } catch (e) { reject(new Error('服务器响应无法读取，请刷新页面后重试。')); return; }
                if (xhr.status < 200 || xhr.status >= 300 || data.error) reject(new Error(data.error || '保存未完成，请刷新页面后重试。'));
                else resolve(data);
            };
            xhr.onerror = function () { reject(new Error('连接失败，请稍后重试。')); };
            xhr.ontimeout = function () { reject(new Error('连接超时，请刷新页面确认保存结果后重试。')); };
            xhr.send(body ? JSON.stringify(body) : null);
        });
    }
    function catalogue() {
        if (!cataloguePromise) cataloguePromise = request('gamedata/paperdoll/catalog.json');
        return cataloguePromise;
    }
    function image(src) {
        if (!images[src]) images[src] = new Promise(function (resolve, reject) {
            var img = new Image();
            img.onload = function () { resolve(img); };
            img.onerror = function () { delete images[src]; reject(new Error('人物部件暂时无法载入，请刷新页面重试。')); };
            img.src = src;
        });
        return images[src];
    }
    function find(catalog, category, id) {
        return (catalog[category] || []).filter(function (part) { return part.id === id; })[0];
    }
    function layer(part, color, catalog) {
        var key = part.src + ':' + color + ':' + part.colorable;
        if (!layers[key]) {
            layerKeys.push(key);
            if (layerKeys.length > 96) delete layers[layerKeys.shift()];
            layers[key] = image(part.src).then(function (img) {
            var c = document.createElement('canvas');
            c.width = catalog.canvas.width; c.height = catalog.canvas.height;
            var ctx = c.getContext('2d', {willReadFrequently: true});
            ctx.drawImage(img, 0, 0, c.width, c.height);
            var data = ctx.getImageData(0, 0, c.width, c.height), p = data.data;
            var rgb = /^#[0-9a-f]{6}$/i.test(color || '') ? [parseInt(color.substr(1, 2), 16), parseInt(color.substr(3, 2), 16), parseInt(color.substr(5, 2), 16)] : null;
            for (var i = 0; i < p.length; i += 4) {
                if (p[i + 3] < 64) { p[i + 3] = 0; continue; }
                if (!rgb || part.colorable === false) continue;
                var light = (.2126 * p[i] + .7152 * p[i + 1] + .0722 * p[i + 2]) / 255;
                // 深色描线不染成亮色，明暗层次随主色一起保留。
                for (var k = 0; k < 3; k++) p[i + k] = light < .12 ? p[i + k] : Math.min(255, rgb[k] * (.3 + light) + Math.max(0, light - .7) * 180);
            }
            ctx.putImageData(data, 0, 0);
            return c;
            }).catch(function (error) { delete layers[key]; throw error; });
        }
        return layers[key];
    }
    function compose(state, catalog) {
        var list = [{src: catalog.base, z: 0, colorable: false}], order = {bottom: 20, top: 30, hair: 40}, hairClip = 0;
        Object.keys(order).forEach(function (category) {
            var selected = state[category] || catalog.default[category], part = find(catalog, category, selected.id);
            if (part) list.push(Object.assign({}, part, {category: category, color: selected.color, z: part.z === undefined ? order[category] : part.z}));
        });
        (state.accessories || []).forEach(function (selected) {
            var part = find(catalog, 'accessories', selected.id);
            if (part) {
                if (part.slot === 'head' && Number(part.hair_clip) > 0) hairClip = Math.min(catalog.canvas.height, Number(part.hair_clip));
                list.push(Object.assign({}, part, {color: selected.color, z: part.z === undefined ? 50 : part.z}));
            }
        });
        list.sort(function (a, b) { return a.z - b.z; });
        return Promise.all(list.map(function (part) { return layer(part, part.color, catalog); })).then(function (parts) {
            var c = document.createElement('canvas'); c.width = catalog.canvas.width; c.height = catalog.canvas.height;
            var ctx = c.getContext('2d');
            parts.forEach(function (part, index) {
                ctx.save();
                if (list[index].category === 'hair' && hairClip) { ctx.beginPath(); ctx.rect(0, hairClip, c.width, c.height - hairClip); ctx.clip(); }
                ctx.drawImage(part, 0, 0); ctx.restore();
            });
            return c;
        });
    }
    function draw(canvas, state, options) {
        options = options || {};
        var generation = (canvas._paperdollGeneration || 0) + 1;
        canvas._paperdollGeneration = generation;
        return catalogue().then(function (catalog) { return compose(state, catalog); }).then(function (source) {
            if (generation !== canvas._paperdollGeneration || !canvas.isConnected) return;
            var ctx = canvas.getContext('2d', {willReadFrequently: true}), height = options.height || canvas.height;
            var width = source.width * height / source.height, left = (canvas.width - width) / 2, top = (canvas.height - height) / 2;
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.imageSmoothingEnabled = false;
            ctx.drawImage(source, left, top, width, height);
            if (options.fill) {
                var data = ctx.getImageData(0, 0, canvas.width, canvas.height), p = data.data;
                for (var y = 0; y < canvas.height; y++) for (var x = 0; x < canvas.width; x++) {
                    var i = (y * canvas.width + x) * 4;
                    if (!p[i + 3]) continue;
                    var r = p[i], g = p[i + 1], b = p[i + 2], light = (.2126 * r + .7152 * g + .0722 * b) / 255;
                    var isLeft = x < canvas.width / 2, level = Math.max(0, Math.min(100, Number(isLeft ? options.sp : options.hp) || 0));
                    if (y >= top + height * (1 - level / 100)) {
                        var tint = isLeft ? [240, 199, 77] : [247, 101, 92], shade = .24 + .76 * Math.sqrt(light);
                        p[i] = tint[0] * shade * .86 + r * .14; p[i + 1] = tint[1] * shade * .86 + g * .14; p[i + 2] = tint[2] * shade * .86 + b * .14;
                    } else { p[i] = 28 + 58 * light; p[i + 1] = 34 + 63 * light; p[i + 2] = 35 + 65 * light; }
                }
                ctx.putImageData(data, 0, 0);
            }
            canvas.setAttribute('data-pd-ready', '1');
        });
    }
    function refresh() {
        Array.prototype.forEach.call(document.querySelectorAll('canvas[data-pd-state]'), function (canvas) {
            if (canvas.getAttribute('data-pd-ready')) return;
            var state;
            try { state = JSON.parse(canvas.getAttribute('data-pd-state')); } catch (e) { return; }
            draw(canvas, state, {fill: canvas.getAttribute('data-pd-fill') === '1', hp: canvas.getAttribute('data-pd-hp'), sp: canvas.getAttribute('data-pd-sp'), height: Number(canvas.getAttribute('data-pd-height')) || canvas.height}).catch(function () {
                canvas.setAttribute('aria-label', '人物部件暂时无法载入；生命、体力和伤情仍可在状态栏查看。');
            });
        });
        initEditor();
    }
    function accessoryMap(state, catalog) {
        var map = {};
        (state.accessories || []).forEach(function (selected) {
            var part = find(catalog, 'accessories', selected.id);
            if (part) map[part.slot] = selected;
        });
        return map;
    }
    function changedCount(old, current, catalog) {
        var count = 0;
        Object.keys(categories).forEach(function (key) { if (old[key].id !== current[key].id || old[key].color.toLowerCase() !== current[key].color.toLowerCase()) count++; });
        var before = accessoryMap(old, catalog), after = accessoryMap(current, catalog), keys = {};
        Object.keys(before).concat(Object.keys(after)).forEach(function (key) { keys[key] = true; });
        Object.keys(keys).forEach(function (key) {
            if (!before[key] || !after[key] || before[key].id !== after[key].id || before[key].color.toLowerCase() !== after[key].color.toLowerCase()) count++;
        });
        return count;
    }
    function element(tag, text, className) {
        var el = document.createElement(tag);
        if (text !== undefined) el.textContent = text;
        if (className) el.className = className;
        return el;
    }
    function initEditor() {
        var root = document.getElementById('paperdoll-editor'), bootstrap = document.getElementById('paperdoll-bootstrap');
        if (!root || !bootstrap || root._paperdollEditor) return;
        var boot;
        try { boot = JSON.parse(bootstrap.textContent); } catch (e) { return; }
        if (boot.error) { root.querySelector('.pd-message').textContent = boot.error; return; }
        if (!boot.catalog || !boot.state) return;
        root._paperdollEditor = true;
        cataloguePromise = Promise.resolve(boot.catalog);
        editor = {saved: copy(boot.state), state: copy(boot.state), boot: boot, busy: false, previewKey: '', previewReady: false, previewGeneration: 0};
        var fields = root.querySelector('.pd-fields'), controls = [], message = root.querySelector('.pd-message'), cost = root.querySelector('.pd-cost'), save = root.querySelector('[data-pd-save]');
        var classic = root.querySelector('[data-pd-classic]'), balance = root.querySelector('[data-pd-balance]');
        function selected(category, slot) { return slot ? accessoryMap(editor.state, boot.catalog)[slot] : editor.state[category]; }
        function put(category, slot, value) {
            if (!slot) editor.state[category] = value;
            else {
                editor.state.accessories = editor.state.accessories.filter(function (part) { return find(boot.catalog, 'accessories', part.id).slot !== slot; });
                if (value) editor.state.accessories.push(value);
            }
        }
        function addField(category, slot, label) {
            var choices = (boot.catalog[category] || []).filter(function (part) { return !slot || part.slot === slot; });
            if (!choices.length) return;
            var row = element('div', undefined, 'pd-field'), caption = element('label', label), select = element('select'), color = element('input'), palette = element('div', undefined, 'pd-colors');
            var id = 'pd-choice-' + (slot || category); caption.htmlFor = id; select.id = id;
            select.setAttribute('aria-label', label); color.type = 'color'; color.setAttribute('aria-label', label + '颜色');
            if (slot) select.appendChild(new Option('不佩戴', ''));
            choices.forEach(function (part) { select.appendChild(new Option(part.label, part.id)); });
            row.appendChild(caption); row.appendChild(select); row.appendChild(color); row.appendChild(palette); fields.appendChild(row);
            function applyColor(value) {
                var part = selected(category, slot);
                if (part) { part.color = value.toLowerCase(); update(); }
            }
            select.addEventListener('change', function () {
                var part = find(boot.catalog, category, select.value), previous = selected(category, slot);
                put(category, slot, part ? {id: part.id, color: part.color || (part.colors && part.colors[0]) || (previous && previous.color) || '#6b7184'} : null);
                update();
            });
            color.addEventListener('input', function () { applyColor(color.value); });
            controls.push(function () {
                var current = selected(category, slot), item = current && find(boot.catalog, category, current.id);
                select.value = current ? current.id : ''; color.value = current ? current.color : '#6b7184'; color.disabled = !current || item.colorable === false;
                palette.textContent = '';
                ((item && item.colors) || ['#42475b', '#876451', '#547264', '#a34e56', '#b7a886', '#d2d2d0']).forEach(function (hex) {
                    var button = element('button'); button.type = 'button'; button.style.backgroundColor = hex; button.setAttribute('aria-label', label + '配色 ' + hex); button.title = hex; button.disabled = color.disabled;
                    button.addEventListener('click', function () { applyColor(hex); }); palette.appendChild(button);
                });
            });
        }
        Object.keys(categories).forEach(function (category) { addField(category, null, categories[category]); });
        Object.keys(slots).forEach(function (slot) { addField('accessories', slot, slots[slot]); });
        function updateSummary() {
            var changed = changedCount(editor.saved, editor.state, boot.catalog), fee = editor.saved.free_used ? changed * boot.catalog.unit_price : 0;
            cost.textContent = changed ? '改动 ' + changed + ' 个部件 · ' + (editor.saved.free_used ? fee + ' 切糕' : '首次自定义免费') : '未改动外观 · 0 切糕';
            balance.textContent = boot.credits2;
            save.textContent = editor.busy ? '正在保存…' : fee ? '保存形象 · ' + fee + ' 切糕' : '免费保存';
            save.disabled = editor.busy || !editor.previewReady || (!changed && !!editor.saved.classic === !!editor.state.classic) || fee > boot.credits2;
            root.querySelector('.pd-insufficient').hidden = fee <= boot.credits2;
        }
        function update() {
            controls.forEach(function (control) { control(); }); classic.checked = !!editor.state.classic; classic.disabled = editor.busy;
            Array.prototype.forEach.call(fields.querySelectorAll('select'), function (control) { control.disabled = editor.busy; });
            if (editor.busy) Array.prototype.forEach.call(fields.querySelectorAll('input,button'), function (control) { control.disabled = true; });
            var previewKey = JSON.stringify([editor.state.hair, editor.state.top, editor.state.bottom, editor.state.accessories]);
            if (previewKey !== editor.previewKey) {
                if (message.textContent === editor.previewError) message.textContent = '';
                editor.previewKey = previewKey; editor.previewReady = false;
                var generation = ++editor.previewGeneration;
                Promise.all(Array.prototype.map.call(root.querySelectorAll('canvas[data-pd-preview]'), function (canvas) {
                    return draw(canvas, editor.state, {fill: canvas.getAttribute('data-pd-preview') === 'classic', hp: 100, sp: 100, height: canvas.height === 210 ? 200 : 160});
                })).then(function () {
                    if (generation !== editor.previewGeneration) return;
                    editor.previewReady = true; updateSummary();
                }).catch(function (error) {
                    if (generation !== editor.previewGeneration) return;
                    editor.previewError = error.message; message.textContent = error.message; editor.previewReady = false; updateSummary();
                });
            }
            updateSummary();
        }
        classic.addEventListener('change', function () { editor.state.classic = classic.checked; update(); });
        root.addEventListener('submit', function (event) {
            event.preventDefault(); if (save.disabled || editor.busy) return;
            editor.busy = true; message.textContent = ''; update();
            var appearance = copy(editor.state); delete appearance.free_used; delete appearance.revision;
            request(boot.endpoint, {csrf: boot.csrf, base: boot.base, appearance: appearance}).then(function (result) {
                editor.saved = copy(result.state); editor.state = copy(result.state); boot.base = result.base; boot.credits2 = result.credits2;
                message.textContent = result.message || '形象已保存。';
                var credits = document.getElementById('credits2'); if (credits) credits.textContent = result.credits2;
            }).catch(function (error) { message.textContent = error.message; }).then(function () { editor.busy = false; update(); });
        });
        update();
    }
    window.PHPDTSPaperdoll = {refresh: refresh, draw: draw};
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', refresh); else refresh();
})();
