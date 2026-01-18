{{-- File: resources/views/partials/terms-and-conditions.blade.php --}}

<div class="tnc-content">
    <p style="color: #718096; margin-bottom: 25px;">
        <strong>Terakhir diperbarui:</strong> {{ date('d F Y') }}
    </p>

    <h3>1. Penerimaan Syarat & Ketentuan</h3>
    <p>
        Dengan melakukan transaksi pembayaran melalui platform kami, Anda secara otomatis menyetujui dan terikat
        dengan seluruh syarat dan ketentuan yang tercantum dalam dokumen ini. Syarat dan ketentuan ini mengatur
        penggunaan layanan pembayaran yang disediakan oleh KIM Digital bekerja sama dengan Midtrans sebagai
        penyedia layanan payment gateway.
    </p>

    <h3>2. Layanan Pembayaran</h3>
    <p>
        KIM Digital menggunakan <strong>Midtrans</strong> sebagai payment gateway resmi untuk memproses semua
        transaksi pembayaran. Midtrans adalah platform pembayaran yang telah terdaftar dan diawasi oleh
        Bank Indonesia, menjamin keamanan dan privasi data finansial Anda.
    </p>

    <div class="highlight-box">
        <p style="margin: 0;">
            <strong><i class="fas fa-shield-alt"></i> Keamanan Terjamin:</strong><br>
            Semua transaksi dienkripsi menggunakan standar keamanan PCI DSS Level 1 dan teknologi SSL/TLS
            untuk melindungi informasi kartu kredit dan data pribadi Anda.
        </p>
    </div>

    <h3>3. Metode Pembayaran yang Tersedia</h3>
    <p>Kami menyediakan berbagai metode pembayaran untuk kemudahan Anda:</p>
    <ul>
        <li><strong>Virtual Account Bank:</strong> BCA, BNI, BRI, Mandiri, Permata, dan bank lainnya</li>
        <li><strong>Kartu Kredit/Debit:</strong> Visa, Mastercard, JCB, American Express</li>
        <li><strong>E-Wallet:</strong> GoPay</li>
        <li><strong>QRIS</strong></li>

    </ul>

    <h3>4. Proses Pembayaran</h3>
    <ol>
        <li><strong>Checkout:</strong> Setelah memilih produk, lengkapi informasi email Anda</li>
        <li><strong>Pilih Metode:</strong> Pilih metode pembayaran yang tersedia di halaman Midtrans</li>
        <li><strong>Pembayaran:</strong> Ikuti instruksi pembayaran sesuai metode yang dipilih</li>
        <li><strong>Konfirmasi:</strong> Pembayaran akan diverifikasi secara otomatis</li>
        <li><strong>Akses Produk:</strong> Setelah pembayaran berhasil, produk digital akan dikirim ke email Anda</li>
    </ol>

    <h3>5. Waktu Pemrosesan Pembayaran</h3>
    <ul>
        <li><strong>E-Wallet & Kartu Kredit:</strong> Konfirmasi instan (1-5 menit)</li>
        <li><strong>Transfer Bank Virtual Account:</strong> Konfirmasi otomatis maksimal 15 menit</li>
    </ul>

    <h3>6. Kewajiban Pembeli</h3>
    <p>Sebagai pembeli, Anda bertanggung jawab untuk:</p>
    <ul>
        <li>Memastikan informasi email yang diberikan <strong>benar dan aktif</strong></li>
        <li>Menyelesaikan pembayaran dalam waktu yang ditentukan</li>
        <li>Menjaga kerahasiaan informasi pembayaran Anda</li>
        <li>Tidak melakukan chargeback atau dispute yang tidak sah</li>
        <li>Menggunakan produk digital yang dibeli sesuai dengan lisensi yang diberikan</li>
    </ul>

    <h3>7. Kebijakan Pengembalian Dana (Refund)</h3>
    <p>
        Mengingat produk kami adalah <strong>produk digital</strong> yang langsung dapat diakses setelah pembayaran,
        kami menerapkan kebijakan berikut:
    </p>
    <ul>
        <li><strong>Tidak Ada Refund:</strong> Produk digital yang sudah dikirim tidak dapat dikembalikan</li>
        <li><strong>Kesalahan Teknis:</strong> Refund hanya diberikan jika terjadi kesalahan sistem atau produk tidak
            dapat diakses karena masalah teknis dari kami</li>
        <li><strong>Duplikasi Pembayaran:</strong> Refund otomatis untuk pembayaran ganda dalam 7-14 hari kerja</li>
        <li><strong>Proses Refund:</strong> Maksimal 14 hari kerja kembali ke rekening/kartu asal</li>
    </ul>

    <div class="highlight-box">
        <p style="margin: 0;">
            <strong><i class="fas fa-info-circle"></i> Catatan Penting:</strong><br>
            Pastikan Anda membaca deskripsi produk dengan teliti sebelum melakukan pembelian.
            Kami tidak menerima pengembalian dana karena kesalahpahaman tentang isi produk.
        </p>
    </div>

    <h3>8. Pembatalan Transaksi</h3>
    <p>Transaksi dapat dibatalkan secara otomatis dalam kondisi berikut:</p>
    <ul>
        <li>Pembayaran tidak diselesaikan dalam waktu yang ditentukan (expired)</li>
        <li>Terdeteksi aktivitas mencurigakan atau fraud</li>
        <li>Informasi pembayaran tidak valid</li>
        <li>Pembatalan dari pihak bank atau penyedia payment</li>
    </ul>

    <h3>9. Biaya Transaksi</h3>
    <p>
        Harga yang tertera sudah termasuk <strong>semua biaya</strong>. Anda tidak akan dikenakan
        biaya tambahan kecuali:
    </p>
    <ul>
        <li>Biaya admin bank (jika ada) untuk metode transfer tertentu</li>
        <li>Biaya konversi mata uang (untuk kartu kredit luar negeri)</li>
        <li>Biaya layanan khusus yang sudah diinformasikan sebelumnya</li>
    </ul>

    <h3>10. Keamanan & Privasi Data</h3>
    <p>
        Kami berkomitmen melindungi data pribadi dan informasi pembayaran Anda:
    </p>
    <ul>
        <li><strong>Enkripsi SSL/TLS:</strong> Semua data ditransmisikan dengan enkripsi tingkat tinggi</li>
        <li><strong>PCI DSS Compliant:</strong> Midtrans tersertifikasi PCI DSS Level 1</li>
        <li><strong>Tidak Menyimpan Data Kartu:</strong> Informasi kartu kredit tidak disimpan di server kami</li>
        <li><strong>Two-Factor Authentication:</strong> Dukungan 3D Secure untuk kartu kredit</li>
        <li><strong>Fraud Detection:</strong> Sistem deteksi fraud otomatis untuk mencegah transaksi mencurigakan</li>
    </ul>

    <h3>11. Tanggung Jawab</h3>

    <p><strong>KIM Digital bertanggung jawab untuk:</strong></p>
    <ul>
        <li>Menyediakan produk digital sesuai deskripsi</li>
        <li>Mengirim akses produk ke email setelah pembayaran berhasil</li>
        <li>Memberikan dukungan teknis untuk masalah akses produk</li>
    </ul>

    <p><strong>KIM Digital TIDAK bertanggung jawab atas:</strong></p>
    <ul>
        <li>Kesalahan informasi email yang diberikan oleh pembeli</li>
        <li>Keterlambatan pembayaran di pihak bank atau payment gateway</li>
        <li>Kerugian akibat kesalahan penggunaan produk</li>
        <li>Force majeure (bencana alam, perang, dll)</li>
    </ul>

    <p><strong>Midtrans bertanggung jawab untuk:</strong></p>
    <ul>
        <li>Keamanan proses pembayaran</li>
        <li>Perlindungan data kartu kredit dan informasi finansial</li>
        <li>Pemrosesan transaksi sesuai standar industri</li>
    </ul>

    <h3>12. Hak Kekayaan Intelektual</h3>
    <p>
        Semua produk digital yang dijual dilindungi oleh hak cipta dan undang-undang kekayaan intelektual.
        Pembeli <strong>DILARANG</strong>:
    </p>
    <ul>
        <li>Mendistribusikan atau menjual kembali produk tanpa izin</li>
        <li>Memodifikasi atau mengklaim sebagai karya sendiri</li>
        <li>Membagikan akses produk kepada pihak ketiga</li>
        <li>Melakukan reverse engineering atau dekompilasi</li>
    </ul>

    <h3>13. Penyelesaian Sengketa</h3>
    <p>
        Jika terjadi perselisihan atau keluhan terkait transaksi:
    </p>
    <ol>
        <li>Hubungi customer support kami di <strong>support@kimdigital.com</strong></li>
        <li>Sertakan nomor order dan bukti pembayaran</li>
        <li>Kami akan merespon dalam 1x24 jam (hari kerja)</li>
        <li>Penyelesaian akan dilakukan secara musyawarah dan kekeluargaan</li>
        <li>Jika tidak tercapai, akan diselesaikan sesuai hukum yang berlaku di Indonesia</li>
    </ol>

    <h3>14. Perubahan Syarat & Ketentuan</h3>
    <p>
        Kami berhak mengubah syarat dan ketentuan ini sewaktu-waktu. Perubahan akan:
    </p>
    <ul>
        <li>Diinformasikan melalui website dan email terdaftar</li>
        <li>Berlaku efektif 7 hari setelah pengumuman</li>
        <li>Tidak berlaku surut untuk transaksi sebelumnya</li>
    </ul>

    <h3>15. Hukum yang Berlaku</h3>
    <p>
        Syarat dan ketentuan ini diatur dan ditafsirkan sesuai dengan hukum Negara Republik Indonesia.
        Setiap perselisihan yang timbul akan diselesaikan melalui pengadilan di wilayah Jakarta, Indonesia.
    </p>

    <h3>16. Kontak & Dukungan</h3>
    <div class="highlight-box">
        <p><strong>Untuk pertanyaan atau bantuan, hubungi kami:</strong></p>
        <ul style="margin-bottom: 0;">
            <li><strong>Email:</strong> support@kimdigital.com</li>
            <li><strong>WhatsApp:</strong> +62 812-3456-7890</li>
            <li><strong>Jam Operasional:</strong> Senin - Jumat, 09.00 - 17.00 WIB</li>
            <li><strong>Respon Time:</strong> Maksimal 1x24 jam (hari kerja)</li>
        </ul>
    </div>

    <h3>17. Pernyataan Persetujuan</h3>
    <p style="background: #fff9e6; padding: 20px; border-radius: 10px; border-left: 4px solid #ffd666;">
        <strong>Dengan mencentang kotak "Saya Setuju" dan melanjutkan ke pembayaran, Anda menyatakan bahwa:</strong>
    </p>
    <ol>
        <li>Anda telah membaca dan memahami seluruh syarat dan ketentuan ini</li>
        <li>Anda setuju untuk terikat dengan semua ketentuan yang berlaku</li>
        <li>Informasi yang Anda berikan adalah benar dan akurat</li>
        <li>Anda berusia minimal 18 tahun atau memiliki persetujuan wali</li>
        <li>Anda memahami bahwa produk digital tidak dapat dikembalikan</li>
    </ol>

    <hr style="border: none; border-top: 2px solid #e2e8f0; margin: 30px 0;">

    <p style="text-align: center; color: #718096; font-size: 0.9rem;">
        <strong>KIM Digital</strong> bekerja sama dengan <strong>Midtrans (PT Midtrans)</strong><br>
        Terdaftar dan diawasi oleh Bank Indonesia<br>
        <br>
        <strong>© {{ date('Y') }} KIM Digital. All Rights Reserved.</strong>
    </p>
</div>