<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Angket</title>
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
                                <span style="font-size: 40px;">📊</span>
                            </div>
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700;">Hasil Angket Anda</h1>
                            <p style="margin: 10px 0 0 0; color: rgba(255,255,255,0.9); font-size: 16px;">Analisis Lengkap Sudah Siap!</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px; color: #2d3748; font-size: 16px; line-height: 1.6;">
                                Halo <strong>{{ $response->respondent_name }}</strong>,
                            </p>
                            <p style="margin: 0 0 25px; color: #4a5568; font-size: 15px; line-height: 1.6;">
                                Terima kasih telah menyelesaikan <strong>{{ $response->questionnaire->name }}</strong>. 
                                Hasil analisis lengkap sudah kami siapkan untuk Anda!
                            </p>

                            <!-- Summary Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f8f9fa; border-radius: 12px; padding: 20px; margin-bottom: 25px;">
                                <tr>
                                    <td>
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="padding: 8px 0; color: #718096; font-size: 14px;">Nama Angket</td>
                                                <td style="padding: 8px 0; color: #2d3748; font-size: 14px; font-weight: 700; text-align: right;">{{ $response->questionnaire->name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; color: #718096; font-size: 14px;">Tanggal Pengisian</td>
                                                <td style="padding: 8px 0; color: #2d3748; font-size: 14px; text-align: right;">{{ $response->completed_at->format('d F Y, H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; color: #718096; font-size: 14px;">Jumlah Pertanyaan</td>
                                                <td style="padding: 8px 0; color: #2d3748; font-size: 14px; text-align: right;">{{ $response->questionnaire->questions->count() }} pertanyaan</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Results Preview -->
                            <h3 style="margin: 0 0 20px; color: #2d3748; font-size: 18px; font-weight: 700;">📈 Ringkasan Hasil:</h3>
                            
                            @foreach($response->result_summary as $dimensionCode => $result)
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 15px;">
                                <tr>
                                    <td>
                                        <div style="font-weight: 700; color: #2d3748; font-size: 16px; margin-bottom: 5px;">
                                            {{ $result['dimension_name'] }}
                                        </div>
                                        <div style="margin: 10px 0;">
                                            <span style="display: inline-block; background: #e0e7ff; color: #667eea; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 700;">
                                                Skor: {{ $result['score'] }} poin
                                            </span>
                                            <span style="display: inline-block; margin-left: 10px; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 700; 
                                                @if(isset($result['interpretation']['class']))
                                                    @if($result['interpretation']['class'] === 'level-rendah')
                                                        background: #d4edda; color: #155724;
                                                    @elseif($result['interpretation']['class'] === 'level-sedang')
                                                        background: #fff3cd; color: #856404;
                                                    @else
                                                        background: #f8d7da; color: #721c24;
                                                    @endif
                                                @endif
                                            ">
                                                {{ $result['interpretation']['level'] ?? 'Sedang' }}
                                            </span>
                                        </div>
                                        <div style="margin-top: 12px; color: #4a5568; font-size: 14px; line-height: 1.6;">
                                            {{ Str::limit($result['interpretation']['description'] ?? '', 150) }}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            @endforeach

                            <!-- PDF Attachment Info -->
                            <div style="background: linear-gradient(135deg, #f0fff4, #c6f6d5); border: 2px solid #9ae6b4; border-radius: 12px; padding: 25px; margin: 30px 0; text-align: center;">
                                <div style="font-size: 48px; margin-bottom: 15px;">📄</div>
                                <h4 style="margin: 0 0 10px; color: #22543d; font-size: 18px; font-weight: 700;">
                                    Hasil Lengkap (PDF)
                                </h4>
                                <p style="margin: 0 0 20px; color: #276749; font-size: 14px;">
                                    File PDF berisi analisis lengkap, interpretasi detail, dan saran tindakan sudah dilampirkan dalam email ini.
                                </p>
                                <div style="color: #2f855a; font-size: 13px;">
                                    📎 <strong>{{ basename($response->result_pdf_path) }}</strong>
                                </div>
                            </div>

                            <!-- Important Notes -->
                            <div style="background: #fff7ed; border: 2px solid #fed7aa; border-radius: 12px; padding: 20px; margin: 25px 0;">
                                <h4 style="margin: 0 0 12px; color: #9a3412; font-size: 16px; font-weight: 700;">
                                    ⚠️ Catatan Penting:
                                </h4>
                                <ul style="margin: 0; padding-left: 20px; color: #9a3412; font-size: 14px; line-height: 1.8;">
                                    <li>Hasil ini bersifat <strong>informatif dan edukatif</strong></li>
                                    <li>Simpan file PDF dengan baik untuk referensi Anda</li>
                                    <li>Jika memerlukan bantuan profesional, konsultasikan dengan ahli terkait</li>
                                    <li>File ini bersifat pribadi dan rahasia</li>
                                </ul>
                            </div>

                            <!-- Next Steps -->
                            <h3 style="margin: 30px 0 15px; color: #2d3748; font-size: 18px; font-weight: 700;">🎯 Langkah Selanjutnya:</h3>
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #e2e8f0;">
                                        <div style="display: flex; align-items: start;">
                                            <span style="display: inline-block; width: 30px; height: 30px; background: #667eea; color: white; border-radius: 50%; text-align: center; line-height: 30px; font-weight: 700; margin-right: 12px;">1</span>
                                            <div style="flex: 1;">
                                                <strong style="color: #2d3748; font-size: 14px;">Baca Hasil Lengkap</strong><br>
                                                <span style="color: #718096; font-size: 13px;">Download dan baca file PDF yang terlampir</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #e2e8f0;">
                                        <div style="display: flex; align-items: start;">
                                            <span style="display: inline-block; width: 30px; height: 30px; background: #667eea; color: white; border-radius: 50%; text-align: center; line-height: 30px; font-weight: 700; margin-right: 12px;">2</span>
                                            <div style="flex: 1;">
                                                <strong style="color: #2d3748; font-size: 14px;">Terapkan Saran</strong><br>
                                                <span style="color: #718096; font-size: 13px;">Ikuti saran tindakan yang diberikan dalam laporan</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0;">
                                        <div style="display: flex; align-items: start;">
                                            <span style="display: inline-block; width: 30px; height: 30px; background: #667eea; color: white; border-radius: 50%; text-align: center; line-height: 30px; font-weight: 700; margin-right: 12px;">3</span>
                                            <div style="flex: 1;">
                                                <strong style="color: #2d3748; font-size: 14px;">Konsultasi Lanjutan</strong><br>
                                                <span style="color: #718096; font-size: 13px;">Jika perlu, hubungi profesional untuk bantuan lebih lanjut</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- Support Info -->
                            <div style="background: #f0fff4; border: 2px solid #9ae6b4; border-radius: 12px; padding: 20px; margin-top: 30px;">
                                <p style="margin: 0; color: #22543d; font-size: 14px; line-height: 1.6;">
                                    <strong>Butuh Bantuan atau Ada Pertanyaan?</strong><br>
                                    Tim kami siap membantu Anda!<br>
                                    📧 <a href="mailto:support@kim.co.id" style="color: #667eea; text-decoration: none;">support@kim.co.id</a><br>
                                    📱 +62 812-3456-7890
                                </p>
                            </div>

                            <!-- Feedback Request -->
                            <div style="text-align: center; margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 12px;">
                                <p style="margin: 0 0 15px; color: #4a5568; font-size: 14px;">
                                    Bagaimana pengalaman Anda dengan <strong>KIM Digital</strong>?
                                </p>
                                <div>
                                    <a href="#" style="display: inline-block; margin: 0 5px; font-size: 24px; text-decoration: none;">⭐</a>
                                    <a href="#" style="display: inline-block; margin: 0 5px; font-size: 24px; text-decoration: none;">⭐</a>
                                    <a href="#" style="display: inline-block; margin: 0 5px; font-size: 24px; text-decoration: none;">⭐</a>
                                    <a href="#" style="display: inline-block; margin: 0 5px; font-size: 24px; text-decoration: none;">⭐</a>
                                    <a href="#" style="display: inline-block; margin: 0 5px; font-size: 24px; text-decoration: none;">⭐</a>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 30px; text-align: center; border-top: 2px solid #e2e8f0;">
                            <p style="margin: 0 0 15px; color: #718096; font-size: 14px;">
                                Terima kasih telah menggunakan <strong style="color: #667eea;">KIM Digital</strong>
                            </p>
                            <p style="margin: 15px 0; color: #a0aec0; font-size: 12px; line-height: 1.6;">
                                Email ini berisi informasi pribadi dan rahasia.<br>
                                Mohon jangan forward email ini ke pihak lain.
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
