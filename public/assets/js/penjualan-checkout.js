document.addEventListener('DOMContentLoaded', function () {
    const paymentMethodEl = document.getElementById('paymentMethod');
    const cashWrapper = document.getElementById('cashInputWrapper');
    const uangDibayarEl = document.getElementById('uangDibayar');
    const kembalianInputEl = document.getElementById('kembalianInput');
    const kembalianDisplayEl = document.getElementById('kembalianDisplay');
    const kurangInfoEl = document.getElementById('kurangInfo');
    const checkoutForm = document.getElementById('checkoutForm');

    if (!checkoutForm) return;

    // Ambil total pembayaran dari data attribute di form (bukan dari Blade langsung)
    const totalPembayaran = parseFloat(checkoutForm.dataset.total) || 0;

    function toggleCashInput() {
        const method = paymentMethodEl.value;
        cashWrapper.classList.toggle('d-none', method !== 'CASH');
    }

    // Ambil angka murni dari input yang sudah diformat titik (hapus semua titik)
    function getRawNumber(formattedValue) {
        return parseFloat(formattedValue.replace(/\./g, '')) || 0;
    }

    // Format angka jadi "1.000.000"
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    function hitungKembalian() {
        const uangDibayar = getRawNumber(uangDibayarEl.value);
        const selisih = uangDibayar - totalPembayaran;

        if (selisih < 0) {
            // Uang dibayar kurang dari total
            const kurang = Math.abs(selisih);

            kembalianInputEl.value = 0;
            kembalianDisplayEl.value = 'Rp 0';

            kurangInfoEl.textContent = 'Uang kurang Rp ' + formatRupiah(kurang);
            kurangInfoEl.classList.remove('d-none');
            kembalianDisplayEl.classList.add('is-invalid');
        } else {
            // Cukup atau lebih, tampilkan kembalian normal
            kembalianInputEl.value = selisih;
            kembalianDisplayEl.value = 'Rp ' + formatRupiah(selisih);

            kurangInfoEl.textContent = '';
            kurangInfoEl.classList.add('d-none');
            kembalianDisplayEl.classList.remove('is-invalid');
        }
    }

    // Format otomatis sambil ngetik di kolom Uang Dibayar
    function handleUangDibayarInput() {
        const cursorPosBefore = uangDibayarEl.selectionStart;
        const rawBefore = uangDibayarEl.value;
        const digitsBeforeCursor = rawBefore.slice(0, cursorPosBefore).replace(/\D/g, '').length;

        const angka = getRawNumber(uangDibayarEl.value);
        const formatted = angka > 0 ? formatRupiah(angka) : '';

        uangDibayarEl.value = formatted;

        // Kembalikan posisi kursor mendekati posisi semula (hitung berdasarkan jumlah digit sebelum kursor)
        let digitCount = 0;
        let newPos = formatted.length;
        for (let i = 0; i < formatted.length; i++) {
            if (/\d/.test(formatted[i])) {
                digitCount++;
                if (digitCount === digitsBeforeCursor) {
                    newPos = i + 1;
                    break;
                }
            }
        }
        uangDibayarEl.setSelectionRange(newPos, newPos);

        hitungKembalian();
    }

    paymentMethodEl.addEventListener('change', toggleCashInput);
    uangDibayarEl.addEventListener('input', handleUangDibayarInput);

    // Saat submit, pastikan value yang dikirim ke server adalah angka murni (tanpa titik)
    checkoutForm.addEventListener('submit', function () {
        uangDibayarEl.value = getRawNumber(uangDibayarEl.value);
    });

    // Jalankan saat load, jaga-jaga kalau ada old('uang_dibayar') setelah validasi gagal
    if (uangDibayarEl.value) {
        uangDibayarEl.value = formatRupiah(getRawNumber(uangDibayarEl.value));
    }
    toggleCashInput();
    hitungKembalian();
});