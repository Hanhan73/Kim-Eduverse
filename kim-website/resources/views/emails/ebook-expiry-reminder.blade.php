<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminder: Akses E-Book Akan Berakhir</title>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f5f7fa;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f7fa; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">

                    <!-- Header -->
                    <tr>
                        <td
                            style="background: linear-gradient(135deg, #ffa500 0%, #ff6347 100%); padding: 40px 30px; text-align: center;">
                            <div style="font-size: 60px; margin-bottom: 15px;">⚠️</div>
                            <h1 style="margin: 0; color: #ffffff; font-size: 26px; font-weight: 700;">
                                Akses E-Book Anda Akan Berakhir!
                            </h1>
                            <p style="margin: 10px 0 0; color: #ffffff; font-size: 16px; opacity: 0.9;">
                                Segera baca sebelum terlambat
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px; color: #2d3748; font-size: 16px; line-height: 1.6;">
                                Halo! 👋
                            </p>
                            <p style="margin: 0 0 30px; color: #2d3748; font-size: 16px; line-height: 1.6;">
                                Kami ingin mengingatkan bahwa akses Anda ke e-book berikut akan segera berakhir:
                            </p>

                            <!-- E-Book Info -->
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="background-color: #fff3cd; border-radius: 10px; padding: 20px; margin-bottom: 30px; border-left: 4px solid #ffc107;">
                                <tr>
                                    <td>
                                        <h2
                                            style="margin: 0 0 10px; color: #856404; font-size: 20px; font-weight: 700;">
                                            {{ $access->product->name }}
                                        </h2>
                                        <div style="margin-bottom: 15px;">
                                            <table width="100%" cellpadding="5" cellspacing="0">
                                                <tr>
                                                    <td style="color: #856404; font-size: 14px; width: 40%;">
                                                        <strong>Akan Berakhir:</strong>
                                                    </td>
                                                    <td style="color: #ff6347; font-size: 16px; font-weight: 700;">
                                                        {{ $access->expires_at->format('d F Y, H:i') }} WIB
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="color: #856404; font-size: 14px;">
                                                        <strong>Sisa Waktu:</strong>
                                                    </td>
                                                    <td style="color: #ff6347; font-size: 16px; font-weight: 700;">
                                                        {{ $access->days_remaining }} hari lagi
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 30px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $access->access_url }}"
                                            style="display: inline-block; background: linear-gradient(135deg, #ffa500 0%, #ff6347 100%); color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 50px; font-size: 16px; font-weight: 700; box-shadow: 0 4px 12px rgba(255, 99, 71, 0.4);">
                                            📖 Baca Sekarang
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Important Notes -->
                            <div
                                style="background-color: #f0fff4; border-left: 4px solid #48bb78; padding: 20px; border-radius: 6px; margin-bottom: 30px;">
                                <h3 style="margin: 0 0 15px; color: #22543d; font-size: 16px; font-weight: 700;">
                                    💡 Tips
                                </h3>
                                <ul
                                    style="margin: 0; padding-left: 20px; color: #22543d; font-size: 14px; line-height: 1.8;">
                                    <li>Manfaatkan waktu yang tersisa untuk menyelesaikan bacaan</li>
                                    <li>Catat poin-poin penting sebelum akses berakhir</li>
                                    <li>Hubungi kami jika Anda ingin memperpanjang akses</li>
                                </ul>
                            </div>

                            <!-- Support Section -->
                            <div
                                style="text-align: center; padding: 20px; background-color: #f8f9fa; border-radius: 8px;">
                                <p style="margin: 0 0 5px; color: #4a5568; font-size: 14px;">
                                    Ingin memperpanjang akses?
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
                                Terima kasih telah menggunakan KIM Digital
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