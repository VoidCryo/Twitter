// ── TOAST ──
(function () {
    window.showToast = function (msg, type) {
        var container = document.getElementById('toastContainer');
        if (!container) return;
        type = type || 'info';
        var icons = { success: 'ri-checkbox-circle-line', error: 'ri-error-warning-line', info: 'ri-information-line' };
        var t = document.createElement('div');
        t.className = 'tnb-toast toast-' + type;
        t.innerHTML = '<i class="' + (icons[type] || icons.info) + '"></i>'
            + '<span class="toast-msg">' + msg + '</span>'
            + '<button class="toast-close" onclick="this.closest(\'.tnb-toast\').remove()"><i class="ri-close-line"></i></button>';
        container.appendChild(t);
        setTimeout(function () {
            t.classList.add('toast-hide');
            setTimeout(function () { t.remove(); }, 350);
        }, 4000);
    };
})();
