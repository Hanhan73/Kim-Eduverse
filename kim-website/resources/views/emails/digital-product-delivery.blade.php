<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk Digital Anda</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f7fa;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f4f7fa;">
        <tr>
            <td style="padding: 40px 20px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); padding: 40px 30px; text-align: center;">
                            <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); border-radius: 50%; margin: 0 auto 20px; line-height: 80px;">
                                <span style="font-size: 40px;">📥</span>
                            </div>
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700;">Produk Digital Siap!</h1>
                            <p style="margin: 10px 0 0 0; color: rgba(255,255,255,0.9); font-size: 16px;">Download produk Anda sekarang</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px; color: #2d3748; font-size: 16px; line-height: 1.6;">
                                Halo <strong>{{ $order->customer_name }}</strong>,
                            </p>
                            <p style="margin: 0 0 25px; color: #4a5568; font-size: 15px; line-height: 1.6;">
                                Terima kasih atas pembelian Anda! Produk digital Anda sudah siap untuk didownload.
                            </p>

                            <!-- Order Info -->
                            <div style="background: #f8f9fa; border-radius: 12px; padding: 20px; margin-bottom: 25px;">
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                    <tr>
                                        <td style="padding: 8px 0; color: #6c757d; font-size: 14px;">No. Pesanan</td>
                                        <td style="padding: 8px 0; color: #2d3748; font-size: 14px; font-weight: 600; text-align: right;">{{ $order->order_number }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; color: #6c757d; font-size: 14px;">Tanggal</td>
                                        <td style="padding: 8px 0; color: #2d3748; font-size: 14px; font-weight: 600; text-align: right;">{{ $order->created_at->format('d M Y, H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; color: #6c757d; font-size: 14px;">Total</td>
                                        <td style="padding: 8px 0; color: #48bb78; font-size: 16px; font-weight: 700; text-align: right;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Download Section -->
                            <div style="background: #f0fff4; border: 2px solid #9ae6b4; border-radius: 12px; padding: 25px; margin-bottom: 25px;">
                                <h3 style="margin: 0 0 20px; color: #22543d; font-size: 18px;">
                                    📥 Download Produk Anda
                                </h3>

                                @foreach($downloadableProducts as $item)
                                <div style="background: #ffffff; border-radius: 10px; padding: 15px; margin-bottom: 12px; display: table; width: 100%; box-sizing: border-box;">
                                    <div style="display: table-cell; vertical-align: middle; width: 50px;">
                                        <div style="width: 45px; height: 45px; background: linear-gradient(135deg, #48bb78, #38a169); border-radius: 10px; text-align: center; line-height: 45px;">
                                            @if($item->product_type === 'ebook')
                                                <span style="font-size: 20px;">📚</span>
                                            @elseif($item->product_type === 'template')
                                                <span style="font-size: 20px;">📄</span>
                                            @elseif($item->product_type === 'worksheet')
                                                <span style="font-size: 20px;">📝</span>
                                            @else
                                                <span style="font-size: 20px;">📁</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div style="display: table-cell; vertical-align: middle; padding-left: 15px;">
                                        <strong style="color: #2d3748; font-size: 15px; display: block; margin-bottom: 3px;">{{ $item->product_name }}</strong>
                                        <span style="color: #6c757d; font-size: 13px; text-transform: capitalize;">{{ $item->product_type }}</span>
                                    </div>
                                    <div style="display: table-cell; vertical-align: middle; text-align: right; width: 120px;">
                                        <a href="{{ route('digital.product.download', [$order->order_number, $item->product_id]) }}" 
                                           style="display: inline-block; background: linear-gradient(135deg, #48bb78, #38a169); color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px;">
                                            Download
                                        </a>
                                    </div>
                                </div>
                                @endforeach

                                <p style="margin: 15px 0 0; color: #22543d; font-size: 13px;">
                                    <strong>💡 Tips:</strong> Simpan file di tempat yang aman. Link download ini dapat digunakan kapan saja.
                                </p>
                            </div>

                            <!-- Questionnaire Section (if any) -->
                            @php
                                $hasQuestionnaire = $order->items->contains(function($item) {
                                    return $item->product_type === 'questionnaire';
                                });
                            @endphp

                            @if($hasQuestionnaire)
                            <div style="background: #fef3c7; border: 2px solid #fcd34d; border-radius: 12px; padding: 25px; margin-bottom: 25px;">
                                <h3 style="margin: 0 0 15px; color: #92400e; font-size: 18px;">
                                    📋 Angket Menunggu Anda
                                </h3>
                                <p style="margin: 0 0 20px; color: #92400e; font-size: 14px; line-height: 1.6;">
                                    Anda juga memiliki angket yang perlu diisi. Hasil analisis akan dikirim ke email Anda setelah selesai.
                                </p>
                                <a href="{{ route('digital.questionnaire.show', $order->order_number) }}" 
                                   style="display: inline-block; background: linear-gradient(135deg, #f59e0b, #d97706); color: #ffffff; text-decoration: none; padding: 14px 30px; border-radius: 8px; font-weight: 600; font-size: 15px;">
                                    📝 Isi Angket Sekarang
                                </a>
                            </div>
                            @endif

                            <!-- Invoice Download -->
                            <div style="text-align: center; margin: 30px 0;">
                                <a href="{{ route('digital.invoice.download', $order->order_number) }}" 
                                   style="display: inline-block; background: #e2e8f0; color: #4a5568; text-decoration: none; padding: 12px 25px; border-radius: 8px; font-weight: 600; font-size: 14px;">
                                    📄 Download Invoice
                                </a>
                            </div>

                            <!-- Support Info -->
                            <div style="background: #f0f9ff; border: 2px solid #bae6fd; border-radius: 12px; padding: 20px; margin-top: 25px;">
                                <p style="margin: 0; color: #0c4a6e; font-size: 14px; line-height: 1.6;">
                                    <strong>Butuh Bantuan?</strong><br>
                                    Jika ada pertanyaan tentang produk Anda, jangan ragu untuk menghubungi kami di:<br>
                                    📧 <a href="/cdn-cgi/l/email-protection#b3c0c6c3c3dcc1c7f3d8dade9dd0dc9ddad7" style="color: #667eea; text-decoration: none;"><span class="__cf_email__" data-cfemail="fe8d8b8e8e918c8abe959793d09d91d0979a">[email&#160;protected]</span></a><br>
                                    📱 +62 812-3456-7890
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 30px; text-align: center; border-top: 2px solid #e2e8f0;">
                            <p style="margin: 0 0 15px; color: #718096; font-size: 14px;">
                                Terima kasih telah mempercayai <strong style="color: #48bb78;">KIM Digital</strong>
                            </p>
                            <p style="margin: 15px 0 0; color: #a0aec0; font-size: 12px;">
                                © {{ date('Y') }} PT KIM Eduverse. All Rights Reserved.<br>
                                Email ini dikirim otomatis, mohon tidak membalas.
                            </p>
            