// ── LIGHTBOX ──
(function () {
    var _images = [], _idx = 0;
    var _overlay, _track, _counter, _dots, _prev, _next;

    function _init() {
        _overlay = document.getElementById('lbOverlay');
        _track   = document.getElementById('lbTrack');
        _counter = document.getElementById('lbCounter');
        _dots    = document.getElementById('lbDots');
        _prev    = document.getElementById('lbPrev');
        _next    = document.getElementById('lbNext');
        if (!_overlay) return;

        _overlay.addEventListener('click', function (e) { if (e.target === _overlay) lbClose(); });
        document.addEventListener('keydown', function (e) {
            if (!_overlay.classList.contains('lb-open')) return;
            if (e.key === 'Escape')     lbClose();
            if (e.key === 'ArrowLeft')  lbMove(-1);
            if (e.key === 'ArrowRight') lbMove(1);
        });

        // swipe support
        var _tx0 = 0, _ty0 = 0, _drag = false;
        _overlay.addEventListener('touchstart', function (e) {
            if (e.touches.length !== 1) return;
            _tx0 = e.touches[0].clientX; _ty0 = e.touches[0].clientY; _drag = true;
        }, { passive: true });
        _overlay.addEventListener('touchmove', function (e) {
            if (!_drag || e.touches.length !== 1) return;
            var dx = e.touches[0].clientX - _tx0, dy = e.touches[0].clientY - _ty0;
            if (Math.abs(dx) > Math.abs(dy)) e.preventDefault();
        }, { passive: false });
        _overlay.addEventListener('touchend', function (e) {
            if (!_drag) return; _drag = false;
            var dx = e.changedTouches[0].clientX - _tx0, dy = e.changedTouches[0].clientY - _ty0;
            if (Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > 40) lbMove(dx < 0 ? 1 : -1);
        }, { passive: true });
    }

    window.openLightbox = function (images, startIdx) {
        if (!images || !images.length) return;
        _images = images; _idx = startIdx || 0;
        _track.innerHTML = '';
        _images.forEach(function (src) {
            var slide = document.createElement('div');
            slide.className = 'lb-slide';
            var img = document.createElement('img');
            img.src = src; img.alt = 'preview'; img.draggable = false;
            slide.appendChild(img); _track.appendChild(slide);
        });
        _render(false);
        _overlay.classList.add('lb-open');
        document.body.style.overflow = 'hidden';
    };

    window.lbClose = function () {
        _overlay.classList.remove('lb-open');
        document.body.style.overflow = '';
    };

    window.lbMove = function (dir) {
        var n = _idx + dir;
        if (n < 0 || n >= _images.length) return;
        _idx = n; _render(true);
    };

    function _render(animate) {
        _track.style.transition = animate ? 'transform .28s cubic-bezier(.4,0,.2,1)' : 'none';
        _track.style.transform  = 'translateX(' + (-_idx * 100) + '%)';
        var total = _images.length;
        _counter.textContent = total > 1 ? (_idx + 1) + ' / ' + total : '';
        _prev.hidden = _idx === 0;
        _next.hidden = _idx === total - 1;
        _dots.innerHTML = '';
        if (total > 1) {
            _images.forEach(function (_, i) {
                var d = document.createElement('button');
                d.className = 'lb-dot' + (i === _idx ? ' lb-dot-active' : '');
                d.setAttribute('aria-label', 'Gambar ' + (i + 1));
                (function (ii) { d.onclick = function () { _idx = ii; _render(true); }; })(i);
                _dots.appendChild(d);
            });
        }
    }

    document.addEventListener('DOMContentLoaded', _init);
})();
