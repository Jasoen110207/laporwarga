document.addEventListener('DOMContentLoaded', function () {
    // Form daftar: kolom kode admin
    var role = document.getElementById('role');
    var codeWrap = document.getElementById('admin-code-wrapper');
    var codeInput = document.getElementById('admin_code');

    if (role && codeWrap && codeInput) {
        var toggleCode = function () {
            var isAdmin = role.value === 'admin';
            codeWrap.hidden = !isAdmin;
            codeInput.required = isAdmin;
            if (!isAdmin) codeInput.value = '';
        };
        role.addEventListener('change', toggleCode);
        toggleCode();
    }

    // Form laporan: kolom kategori lainnya
    var select = document.getElementById('category_id');
    var otherWrap = document.getElementById('other-wrapper');
    var other = document.getElementById('other_category');

    if (select && otherWrap && other) {
        var toggleOther = function () {
            var opt = select.selectedOptions[0];
            var isOther = !!opt && opt.dataset.slug === 'lainnya';
            otherWrap.hidden = !isOther;
            other.required = isOther;
            if (!isOther) other.value = '';
        };
        select.addEventListener('change', toggleOther);
        toggleOther();
    }
});