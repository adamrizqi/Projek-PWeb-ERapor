/**
 * File: public/js/siswa-index.js
 * Logika untuk halaman daftar siswa
 */

document.addEventListener('DOMContentLoaded', function () {

    // 1. Menangani Tombol Delete
    // Kita cari semua elemen yang punya class 'btn-delete'
    const deleteButtons = document.querySelectorAll('.btn-delete');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Ambil data dari atribut "data-" HTML
            // this.dataset.id akan mengambil value dari data-id="..."
            const id = this.dataset.id;
            const name = this.dataset.name;

            // Tampilkan konfirmasi
            if (confirm(`Apakah Anda yakin ingin menghapus data siswa: ${name}?`)) {
                // Jika Yes, cari form dengan ID yang sesuai dan submit
                const formId = `delete-form-${id}`;
                const form = document.getElementById(formId);

                if (form) {
                    form.submit();
                } else {
                    console.error(`Form dengan ID ${formId} tidak ditemukan!`);
                }
            }
        });
    });

    // 2. (Opsional) Logika lain bisa ditambahkan di sini
    console.log('Script Siswa Index berhasil dimuat.');
});
