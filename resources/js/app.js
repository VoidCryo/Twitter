import './bootstrap';

// ============================================================
// TENEBRIS — APP JS
// ============================================================

// ===== DELETE CONFIRM =====
var _deleteFormId = null;

window.confirmDelete = function (formId) {
    _deleteFormId = formId;
    var el = document.getElementById('deleteConfirmModal');
    if (!el) { console.error('deleteConfirmModal tidak ditemukan'); return; }
    bootstrap.Modal.getOrCreateInstance(el).show();
};

document.addEventListener('DOMContentLoaded', function () {

    // Wire tombol konfirmasi hapus
    var deleteConfirmBtn = document.getElementById('deleteConfirmBtn');
    if (deleteConfirmBtn) {
        deleteConfirmBtn.addEventListener('click', function () {
            if (!_deleteFormId) return;
            var el = document.getElementById('deleteConfirmModal');
            var m = bootstrap.Modal.getInstance(el);
            if (m) m.hide();
            document.getElementById(_deleteFormId).submit();
            _deleteFormId = null;
        });
    }

    // ===== AUTO-DISMISS ALERTS =====
    document.querySelectorAll('.alert-floating').forEach(function (el) {
        setTimeout(function () { bootstrap.Alert.getOrCreateInstance(el).close(); }, 4000);
    });

    // ===== AUTO-RESIZE TEXTAREAS =====
    function autoResize(el) {
        el.style.height = '0';
        el.style.height = Math.max(80, el.scrollHeight) + 'px';
    }
    document.querySelectorAll('.compose-textarea').forEach(function (ta) {
        ta.addEventListener('input', function () { autoResize(this); });
    });

    document.addEventListener('shown.bs.modal', function (e) {
        var ta = e.target.querySelector('.compose-textarea');
        if (ta) { ta.style.height = '80px'; ta.focus(); }
    });
    document.addEventListener('hidden.bs.modal', function (e) {
        e.target.querySelectorAll('.compose-textarea').forEach(function (ta) {
            ta.value = ''; ta.style.height = '80px';
        });
        e.target.querySelectorAll('.compose-media-preview').forEach(function (p) {
            p.innerHTML = ''; p.classList.add('d-none');
        });
        e.target.querySelectorAll('input[type=file]').forEach(function (inp) { inp.value = ''; });
    });
});

// ===== MUAT LEBIH BANYAK — FEED =====
window.loadMoreFeed = function (btn) {
    var tab       = btn.dataset.tab;
    var nextPage  = parseInt(btn.dataset.next);
    var url       = btn.dataset.url;
    var container = document.getElementById(btn.dataset.container);
    var wrap      = document.getElementById(btn.dataset.wrap);

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Memuat...';

    window.axios.get(url, { params: { tab: tab, page: nextPage } })
        .then(function (res) {
            container.insertAdjacentHTML('beforeend', res.data.html);
            if (res.data.hasMore) {
                btn.dataset.next = String(nextPage + 1);
                btn.disabled = false;
                btn.innerHTML = '<i class="ri-refresh-line me-1"></i> Muat Lebih Banyak';
            } else {
                if (wrap) wrap.remove();
            }
        })
        .catch(function (err) {
            console.error('[loadMoreFeed]', err);
            btn.disabled = false;
            btn.innerHTML = '<i class="ri-refresh-line me-1"></i> Coba Lagi';
        });
};

// ===== MUAT LEBIH BANYAK — BALASAN =====
window.loadMoreReplies = function (btn) {
    var nextPage  = parseInt(btn.dataset.next);
    var url       = btn.dataset.url;
    var container = document.getElementById(btn.dataset.container);
    var wrap      = document.getElementById(btn.dataset.wrap);

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Memuat...';

    window.axios.get(url, { params: { page: nextPage } })
        .then(function (res) {
            container.insertAdjacentHTML('beforeend', res.data.html);
            if (res.data.hasMore) {
                btn.dataset.next = String(nextPage + 1);
                btn.disabled = false;
                btn.innerHTML = '<i class="ri-refresh-line me-1"></i> Muat Lebih Banyak';
            } else {
                if (wrap) wrap.remove();
            }
        })
        .catch(function (err) {
            console.error('[loadMoreReplies]', err);
            btn.disabled = false;
            btn.innerHTML = '<i class="ri-refresh-line me-1"></i> Coba Lagi';
        });
};

// ===== IMAGE PREVIEW =====
window.previewImages = function (input, containerId) {
    var container = document.getElementById(containerId);
    if (!container) return;
    container.innerHTML = '';
    var files = Array.from(input.files).slice(0, 4);
    if (!files.length) { container.classList.add('d-none'); return; }
    container.classList.remove('d-none');
    files.forEach(function (file) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var wrap = document.createElement('div');
            wrap.className = 'compose-preview-item';
            var img = document.createElement('img');
            img.src = e.target.result;
            var btn = document.createElement('button');
            btn.className = 'compose-preview-remove';
            btn.type = 'button';
            btn.innerHTML = '&times;';
            btn.onclick = function () {
                wrap.remove();
                if (!container.children.length) container.classList.add('d-none');
            };
            wrap.appendChild(img);
            wrap.appendChild(btn);
            container.appendChild(wrap);
        };
        reader.readAsDataURL(file);
    });
};

// ===== LIGHTBOX =====
var lbImages = [], lbIndex = 0;

window.openLightbox = function (images, startIndex) {
    if (!images || !images.length) return;
    lbImages = images;
    lbIndex  = startIndex || 0;
    renderLightbox();
    document.getElementById('lightboxOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
};

function renderLightbox() {
    document.getElementById('lbImg').src = lbImages[lbIndex];
    var total = lbImages.length;
    document.getElementById('lbCounter').textContent = total > 1 ? (lbIndex + 1) + ' / ' + total : '';
    document.getElementById('lbPrev').style.display = (total > 1 && lbIndex > 0)         ? 'flex' : 'none';
    document.getElementById('lbNext').style.display = (total > 1 && lbIndex < total - 1) ? 'flex' : 'none';
    var dots = document.getElementById('lbDots');
    dots.innerHTML = '';
    if (total > 1) {
        lbImages.forEach(function (_, i) {
            var d = document.createElement('span');
            d.className = 'lb-dot' + (i === lbIndex ? ' active' : '');
            (function (idx) { d.onclick = function () { lbIndex = idx; renderLightbox(); }; })(i);
            dots.appendChild(d);
        });
    }
}

window.lbMove = function (dir) {
    lbIndex = Math.max(0, Math.min(lbImages.length - 1, lbIndex + dir));
    renderLightbox();
};

window.closeLightbox = function () {
    document.getElementById('lightboxOverlay').classList.remove('active');
    document.body.style.overflow = '';
};
