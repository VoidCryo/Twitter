import './bootstrap';
import './toast';
import './lightbox';

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
