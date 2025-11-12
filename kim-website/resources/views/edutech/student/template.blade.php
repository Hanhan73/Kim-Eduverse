<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Certificate of Completion</title>
    <style>
        @page { 
            size: A4 landscape;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            background: #f9fafb;
            font-family: 'Times New Roman', serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .certificate {
            width: 95%;
            height: 90%;
            background: #fff;
            border: 10px double #6D28D9;
            padding: 40px 60px;
            box-sizing: border-box;
            position: relative;
            box-shadow: 0 0 25px rgba(0,0,0,0.15);
        }

        .certificate-inner {
            border: 2px solid #C4B5FD;
            padding: 40px;
            height: 100%;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .logo {
            font-size: 52px;
            color: #6D28D9;
            margin-bottom: 10px;
        }

        .title {
            font-size: 46px;
            color: #1F2937;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 8px;
            text-shadow: 0 2px 3px rgba(0,0,0,0.15);
        }

        .subtitle {
            font-size: 18px;
            color: #6B7280;
            margin-bottom: 30px;
            font-style: italic;
        }

        .name {
            font-size: 40px;
            color: #1F2937;
            font-weight: bold;
            border-bottom: 3px solid #6D28D9;
            display: inline-block;
            padding: 5px 40px 10px;
            margin: 10px 0 25px;
            text-transform: capitalize;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .completion-text {
            font-size: 18px;
            color: #4B5563;
            margin-bottom: 10px;
        }

        .course-name {
            font-size: 30px;
            color: #6D28D9;
            font-weight: bold;
            margin: 20px 0 30px;
        }

        .details {
            display: inline-block;
            margin-bottom: 25px;
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            color: #374151;
            margin-bottom: 6px;
        }

        .detail-label {
            font-weight: bold;
            color: #6B7280;
            min-width: 130px;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding: 0 60px;
        }

        .signature {
            text-align: center;
            width: 250px;
        }

        .signature-line {
            border-top: 2px solid #1F2937;
            width: 200px;
            margin: 0 auto 10px;
        }

        .signature-name {
            font-size: 16px;
            font-weight: bold;
            color: #1F2937;
        }

        .signature-title {
            font-size: 12px;
            color: #6B7280;
            font-style: italic;
        }

        .certificate-number {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 11px;
            color: #9CA3AF;
            font-family: 'Courier New', monospace;
        }

        .seal {
            position: absolute;
            top: 50%;
            right: 70px;
            transform: translateY(-50%);
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 3px solid #6D28D9;
            color: #6D28D9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            text-align: center;
            font-size: 14px;
            line-height: 1.2;
            box-shadow: 0 0 10px rgba(109,40,217,0.3);
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="certificate-inner">
            <div class="logo">🎓</div>

            <div class="title">Certificate of Completion</div>
            <div class="subtitle">This is to certify that</div>

            <div class="name">{{ $certificate->student->name }}</div>

            <div class="completion-text">has successfully completed the course</div>

            <div class="course-name">{{ $certificate->course->title }}</div>

            <div class="details">
                <div class="detail-row">
                    <div class="detail-label">Completion Date:</div>
                    <div class="detail-value">{{ $certificate->certificate_issued_at->format('F d, Y') }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Course Category:</div>
                    <div class="detail-value">{{ $certificate->course->category }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Instructor:</div>
                    <div class="detail-value">{{ $certificate->course->instructor->name }}</div>
                </div>
            </div>

            <div class="signatures">
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-name">{{ $certificate->course->instructor->name }}</div>
                    <div class="signature-title">Course Instructor</div>
                </div>
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-name">KIM Edutech</div>
                    <div class="signature-title">Platform Administrator</div>
                </div>
            </div>

            <div class="seal">VERIFIED<br>CERTIFICATE</div>
            <div class="certificate-number">
                Certificate No: {{ $certificate->certificate_number }}
            </div>
        </div>
    </div>
</body>
</html>
