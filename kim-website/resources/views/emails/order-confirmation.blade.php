<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f7fa;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f4f7fa;">
        <tr>
            <td style="padding: 40px 20px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center;">
                            <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
                                <span style="font-size: 40px;">✓</span>
                            </div>
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700;">Pesanan Berhasil!</h1>
                            <p style="margin: 10px 0 0 0; color: rgba(255,255,255,0.9); font-size: 16px;">Terima kasih atas pembelian Anda</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px; color: #2d3748; font-size: 16px; line-height: 1.6;">
                                Halo <strong>{{ $order->customer_name }}</strong>,
                            </p>
                            <p style="margin: 0 0 25px; color: #4a5568; font-size: 15px; line-height: 1.6;">
                                Pembayaran Anda telah berhasil dikonfirmasi. Berikut adalah detail pesanan Anda:
                            </p>

                            <!-- Order Info Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f8f9fa; border-radius: 12px; padding: 20px; margin-bottom: 25px;">
                                <tr>
                                    <td>
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="padding: 8px 0; color: #718096; font-size: 14px;">Nomor Pesanan</td>
                                                <td style="padding: 8px 0; color: #2d3748; font-size: 14px; font-weight: 700; text-align: right;">{{ $order->order_number }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; color: #718096; font-size: 14px;">Tanggal</td>
                                                <td style="padding: 8px 0; color: #2d3748; font-size: 14px; text-align: right;">{{ $order->created_at->format('d F Y, H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; color: #718096; font-size: 14px;">Email</td>
                                                <td style="padding: 8px 0; color: #2d3748; font-size: 14px; text-align: right;">{{ $order->customer_email }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Order Items -->
                            <h3 style="margin: 0 0 15px; color: #2d3748; font-size: 18px; font-weight: 700;">Produk yang Dibeli:</h3>
                            
                            @foreach($order->items as $item)
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 15px; margin-bottom: 12px;">
                                <tr>
                                    <td style="padding-right: 15px; vertical-align: top;">
                                        <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                                            📄
                                        </div>
                                    </td>
                                    <td style="vertical-align: top;">
                                        <div style="font-weight: 700; color: #2d3748; font-size: 15px; margin-bottom: 5px;">{{ $item->product_name }}</div>
                                        <div style="color: #718096; font-size: 13px; margin-bottom: 8px;">{{ ucfirst($item->product_type) }}</div>
                                        <div style="color: #667eea; font-size: 16px; font-weight: 700;">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                    </td>
                                </tr>
                            </table>
                            @endforeach

                            <!-- Total -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background: linear-gradient(135deg, #f8f9ff, #f0f4ff); border-radius: 12px; padding: 20px; margin: 25px 0;">
                                <tr>
                                    <td>
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="padding: 6px 0; color: #4a5568; font-size: 14px;">Subtotal</td>
                                                <td style="padding: 6px 0; color: #2d3748; font-size: 14px; text-align: right;">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color: #4a5568; font-size: 14px;">Biaya Admin</td>
                                                <td style="padding: 6px 0; color: #2d3748; font-size: 14px; text-align: right;">Rp {{ number_format($order->tax, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top: 15px; border-top: 2px solid #cbd5e0; color: #2d3748; font-size: 18px; font-weight: 700;">Total</td>
                                                <td style="padding-top: 15px; border-top: 2px solid #cbd5e0; color: #667eea; font-size: 22px; font-weight: 700; text-align: right;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            @php
                                $hasQuestionnaire = $order->items->contains(function($item) {
                                    return $item->product_type === 'questionnaire';
                                });
                            @endphp

                            @if($hasQuestionnaire)
                            <!-- Next Steps for Questionnaire -->
                            <div style="background: #fff7ed; border: 2px solid #fed7aa; border-radius: 12px; padding: 20px; margin: 25px 0;">
                                <h4 style="margin: 0 0 12px; color: #9a3412; font-size: 16px; font-weight: 700;">
                                    📋 Langkah Selanjutnya:
                                </h4>
                                <ol style="margin: 0; padding-left: 20px; color: #9a3412; font-size: 14px; line-height: 1.8;">
                                    <li>Klik tombol di bawah untuk mengisi angket</li>
                                    <li>Jawab semua pertanyaan dengan jujur</li>
                                    <li>Hasil analisis akan dikirim via email</li>
                                </ol>
                            </div>

                            <!-- CTA Button -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('digital.questionnaire.show', $order->order_number) }}" style="display: inline-block; background: linear-gradient(135deg, #667eea, #764ba2); color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 50px; font-weight: 700; font-size: 16px;">
                                            📝 Isi Angket Sekarang
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <!-- Support Info -->
                            <div style="background: #f0fff4; border: 2px solid #9ae6b4; border-radius: 12px; padding: 20px; margin-top: 30px;">
                                <p style="margin: 0; color: #22543d; font-size: 14px; line-height: 1.6;">
                                    <strong>Butuh Bantuan?</strong><br>
                                    Jika ada pertanyaan tentang pesanan Anda, jangan ragu untuk menghubungi kami di:<br>
                                    📧 <a href="mailto:support@kim.co.id" style="color: #667eea; text-decoration: none;">support@kim.co.id</a><br>
                                    📱 +62 812-3456-7890
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 30px; text-align: center; border-top: 2px solid #e2e8f0;">
                            <p style="margin: 0 0 15px; color: #718096; font-size: 14px;">
                                Terima kasih telah mempercayai <strong style="color: #667eea;">KIM Digital</strong>
                            </p>
                            <div style="margin: 20px 0;">
                                <a href="#" style="display: inline-block; margin: 0 8px; color: #667eea; text-decoration: none; font-size: 20px;">📱</a>
                                <a href="#" style="display: inline-block; margin: 0 8px; color: #667eea; text-decoration: none; font-size: 20px;">📧</a>
                                <a href="#" style="display: inline-block; margin: 0 8px; color: #667eea; text-decoration: none; font-size: 20px;">🌐</a>
                            </div>
                            <p style="margin: 15px 0 0; color: #a0aec0; font-size: 12px;">
                                © {{ date('Y') }} PT KIM Eduverse. All Rights Reserved.<br>
                                Email ini dikirim otomatis, mohon tidak membalas.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
