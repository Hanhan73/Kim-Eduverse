<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan</title>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f5f7fa;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f7fa; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">

                    <!-- Header -->
                    <td
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center;">

                        <img src="https://kimeduverse.com/images/logo.png" alt="KIM Eduverse" width="140"
                            style="display:block; margin:0 auto 20px; max-width:140px;">

                        <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700;">
                            Pembayaran Berhasil!
                        </h1>

                        <p style="margin: 10px 0 0; color: #ffffff; font-size: 16px; opacity: 0.9;">
                            Terima kasih atas pembelian Anda
                        </p>
                    </td>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px; color: #2d3748; font-size: 16px; line-height: 1.6;">
                                Halo! {{ $order->customer_name }},
                            </p>
                            <p style="margin: 0 0 30px; color: #2d3748; font-size: 16px; line-height: 1.6;">
                                Pembayaran Anda telah berhasil dikonfirmasi. Berikut detail pesanan Anda:
                            </p>

                            <!-- Order Info -->
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="background-color: #f8f9fa; border-radius: 10px; padding: 20px; margin-bottom: 30px;">
                                <tr>
                                    <td>
                                        <table width="100%" cellpadding="8" cellspacing="0">
                                            <tr>
                                                <td style="color: #718096; font-size: 14px; width: 40%;">
                                                    <strong>No. Pesanan:</strong>
                                                </td>
                                                <td style="color: #2d3748; font-size: 14px; font-weight: 600;">
                                                    {{ $order->order_number }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #718096; font-size: 14px;">
                                                    <strong>Nama:</strong>
                                                </td>
                                                <td style="color: #2d3748; font-size: 14px;">
                                                    {{ $order->customer_name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #718096; font-size: 14px;">
                                                    <strong>Email:</strong>
                                                </td>
                                                <td style="color: #2d3748; font-size: 14px;">
                                                    {{ $order->customer_email }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #718096; font-size: 14px;">
                                                    <strong>Total Pembayaran:</strong>
                                                </td>
                                                <td style="color: #667eea; font-size: 18px; font-weight: 700;">
                                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Products Purchased -->
                            <h3 style="margin: 0 0 20px; color: #2d3748; font-size: 18px; font-weight: 700;">
                                Produk yang Dibeli
                            </h3>

                            @foreach($order->items as $item)
                            <div style="background: #f8f9fa; border-radius: 8px; padding: 15px; margin-bottom: 15px;">
                                <div style="display: flex; justify-content: space-between; align-items: start;">
                                    <div style="flex: 1;">
                                        <strong
                                            style="color: #2d3748; font-size: 16px; display: block; margin-bottom: 5px;">
                                            {{ $item->product_name }}
                                        </strong>
                                        <span
                                            style="background: #e0e7ff; color: #4338ca; padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                            {{ ucfirst($item->product_type) }}
                                        </span>
                                    </div>
                                    <div style="text-align: right;">
                                        <span style="color: #667eea; font-size: 16px; font-weight: 700;">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            <!-- Separator -->
                            <div style="border-top: 2px solid #e2e8f0; margin: 30px 0;"></div>

                            <!-- Action Sections -->
                            @php
                            $hasQuestionnaire = $order->items()->whereHas('product', function($q) {
                            $q->where('type', 'questionnaire');
                            })->exists();

                            $hasEbook = $order->items()->whereHas('product', function($q) {
                            $q->where('type', 'ebook');
                            })->exists();

                            $hasSeminar = $order->items()->whereHas('product', function($q) {
                            $q->where('type', 'seminar');
                            })->exists();

                            $hasDownloadable = $order->items()->whereHas('product', function($q) {
                            $q->whereIn('type', ['module', 'template', 'video', 'other'])
                            ->whereNotNull('file_url');
                            })->exists();
                            @endphp

                            {{-- E-BOOK ACCESS --}}
                            @if($hasEbook)
                            <div
                                style="background: #f0f9ff; border-left: 4px solid #3182ce; padding: 20px; border-radius: 6px; margin-bottom: 20px;">
                                <h3 style="margin: 0 0 15px; color: #0c4a6e; font-size: 16px; font-weight: 700;">
                                    📖 Akses E-Book Anda
                                </h3>
                                <p style="margin: 0 0 15px; color: #0c4a6e; font-size: 14px; line-height: 1.6;">
                                    E-book Anda siap dibaca! Link akses khusus telah dikirim ke email terpisah dengan
                                    judul
                                    "<strong>Akses E-Book Anda</strong>". Silakan cek inbox atau folder spam Anda.
                                </p>
                                <p style="margin: 0; color: #0c4a6e; font-size: 13px;">
                                    💡 <strong>Tips:</strong> Simpan email tersebut untuk akses di kemudian hari.
                                </p>
                            </div>
                            @endif

                            {{-- QUESTIONNAIRE --}}
                            @if($hasQuestionnaire)
                            <div
                                style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 20px; border-radius: 6px; margin-bottom: 20px;">
                                <h3 style="margin: 0 0 15px; color: #856404; font-size: 16px; font-weight: 700;">
                                    📝 Isi CEKMA Anda
                                </h3>
                                <p style="margin: 0 0 15px; color: #856404; font-size: 14px; line-height: 1.6;">
                                    Anda memiliki cekma yang perlu diisi. Hasil analisis akan dikirim ke email setelah
                                    Anda menyelesaikannya.
                                </p>
                                <a href="{{ route('digital.questionnaire.show', $order->order_number) }}"
                                    style="display: inline-block; background: linear-gradient(135deg, #ffa500, #ff6347); color: white; text-decoration: none; padding: 12px 30px; border-radius: 50px; font-weight: 700; font-size: 14px;">
                                    Isi CEKMA Sekarang →
                                </a>
                            </div>
                            @endif

                            {{-- SEMINAR ACCESS --}}
                            @if($hasSeminar)
                            <div
                                style="background: #f0f9ff; border-left: 4px solid #3182ce; padding: 20px; border-radius: 6px; margin-bottom: 20px;">
                                <h3 style="margin: 0 0 15px; color: #0c4a6e; font-size: 16px; font-weight: 700;">
                                    🎥 Akses Seminar Anda
                                </h3>
                                <p style="margin: 0 0 15px; color: #0c4a6e; font-size: 14px; line-height: 1.6;">
                                    Selamat! Anda telah terdaftar dalam seminar. Klik tombol di bawah untuk mengakses
                                    materi.
                                </p>
                                <a href="{{ route('digital.seminar.learn', $order->order_number) }}"
                                    style="display: inline-block; background: linear-gradient(135deg, #667eea, #764ba2); color: white; text-decoration: none; padding: 12px 30px; border-radius: 50px; font-weight: 700; font-size: 14px;">
                                    Akses Seminar →
                                </a>
                            </div>
                            @endif

                            {{-- DOWNLOADABLE PRODUCTS --}}
                            @if($hasDownloadable)
                            <div
                                style="background: #f0fff4; border-left: 4px solid #48bb78; padding: 20px; border-radius: 6px; margin-bottom: 20px;">
                                <h3 style="margin: 0 0 15px; color: #22543d; font-size: 16px; font-weight: 700;">
                                    📥 Download Produk Digital
                                </h3>
                                <p style="margin: 0 0 15px; color: #22543d; font-size: 14px; line-height: 1.6;">
                                    Produk digital Anda siap didownload. Klik link di bawah untuk mengunduh.
                                </p>
                                <a href="{{ route('digital.payment.success', $order->order_number) }}"
                                    style="display: inline-block; background: linear-gradient(135deg, #48bb78, #38a169); color: white; text-decoration: none; padding: 12px 30px; border-radius: 50px; font-weight: 700; font-size: 14px;">
                                    Lihat & Download →
                                </a>
                            </div>
                            @endif

                            <!-- Main CTA -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('digital.payment.success', $order->order_number) }}"
                                            style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 50px; font-size: 16px; font-weight: 700; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);">
                                            📄 Lihat Detail Pesanan Lengkap
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Important Info -->
                            <div
                                style="background: #fff3cd; border: 2px solid #ffc107; border-radius: 10px; padding: 20px; margin-top: 30px;">
                                <h4 style="margin: 0 0 10px; color: #856404; font-size: 14px; font-weight: 700;">
                                    ⚠️ Penting untuk Diperhatikan
                                </h4>
                                <ul
                                    style="margin: 0; padding-left: 20px; color: #856404; font-size: 13px; line-height: 1.8;">
                                    <li>Simpan email ini sebagai bukti pembelian</li>
                                    @if($hasEbook)
                                    <li>Link akses e-book dikirim ke email terpisah (cek inbox & spam)</li>
                                    @endif
                                    @if($hasQuestionnaire)
                                    <li>Segera isi CEKMA untuk mendapatkan hasil analisis</li>
                                    @endif
                                    <li>Jika ada kendala, hubungi customer service kami</li>
                                </ul>
                            </div>

                            <!-- Support Section -->
                            <div
                                style="text-align: center; padding: 25px 20px; background-color: #f8f9fa; border-radius: 8px; margin-top: 30px;">
                                <p style="margin: 0 0 10px; color: #4a5568; font-size: 14px;">
                                    Butuh bantuan? Hubungi kami:
                                </p>
                                <p style="margin: 0; color: #667eea; font-size: 14px; font-weight: 600;">
                                    📧 support@kimeduverse.com | 📱 WhatsApp: 081234567890
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="background-color: #f8f9fa; padding: 30px; text-align: center; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0 0 10px; color: #718096; font-size: 14px;">
                                Terima kasih telah berbelanja di KIM Digital
                            </p>
                            <p style="margin: 0; color: #a0aec0; font-size: 12px;">
                                © {{ date('Y') }} KIM Eduverse. All Rights Reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>