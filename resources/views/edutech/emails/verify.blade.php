<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email</title>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f7fafc;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f7fafc; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center; border-radius: 12px 12px 0 0;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px;">KIM Edutech</h1>
                            <p style="margin: 10px 0 0 0; color: rgba(255,255,255,0.9); font-size: 16px;">Platform
                                Pembelajaran Online</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="margin: 0 0 20px 0; color: #2d3748; font-size: 24px;">Halo, {{ $user->name }}!
                            </h2>

                            <p style="margin: 0 0 20px 0; color: #4a5568; font-size: 16px; line-height: 1.6;">
                                Terima kasih telah mendaftar di KIM Edutech. Untuk mengaktifkan akun Anda, silakan
                                verifikasi email Anda dengan mengklik tombol di bawah ini:
                            </p>

                            <!-- Button -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $verificationUrl }}"
                                            style="display: inline-block; padding: 16px 40px; background: linear-gradient(135deg, #667eea, #764ba2); color: #ffffff; text-decoration: none; border-radius: 8px; font-size: 16px; font-weight: 600;">
                                            Verifikasi Email Saya
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 20px 0; color: #4a5568; font-size: 14px; line-height: 1.6;">
                                Atau copy dan paste link berikut ke browser Anda:
                            </p>

                            <p
                                style="margin: 0 0 20px 0; padding: 15px; background-color: #f7fafc; border-radius: 6px; word-break: break-all; font-size: 13px; color: #667eea;">
                                {{ $verificationUrl }}
                            </p>

                            <p style="margin: 20px 0 0 0; color: #718096; font-size: 14px; line-height: 1.6;">
                                <strong>Catatan:</strong> Link verifikasi ini hanya berlaku untuk satu kali penggunaan.
                                Jika Anda tidak mendaftar akun di KIM Edutech, abaikan email ini.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="background-color: #f7fafc; padding: 30px; text-align: center; border-radius: 0 0 12px 12px; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0 0 10px 0; color: #718096; font-size: 14px;">
                                © 2024 KIM Edutech. All rights reserved.
                            </p>
                            <p style="margin: 0; color: #a0aec0; font-size: 12px;">
                                Email ini dikirim otomatis, mohon tidak membalas email ini.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>