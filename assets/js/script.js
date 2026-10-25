document.addEventListener('DOMContentLoaded', function () {
    // 1. Otomatis menghilangkan alert setelah 3 detik
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 3000);
    });

    // 2. Konfirmasi hapus untuk semua tombol dengan atribut data-confirm
    const deleteButtons = document.querySelectorAll('[data-confirm]');
    deleteButtons.forEach(function (button) {
        button.addEventListener('click', function (e) {
            const message = this.getAttribute('data-confirm') || 'Apakah Anda yakin ingin menghapus data ini?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // 3. Format otomatis input harga menjadi angka saja
    const hargaInput = document.querySelector('input[name="harga"]');
    if (hargaInput) {
        hargaInput.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
});