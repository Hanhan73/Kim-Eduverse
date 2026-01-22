<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Degree Certificate</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            padding: 40px;
        }

        .certificate {
            background: white;
            padding: 60px;
            border: 15px double #c7254e;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .certificate::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 3px solid #f093fb;
            border-radius: 10px;
        }

        .ornament {
            position: absolute;
            font-size: 60px;
            color: #f093fb;
            opacity: 0.2;
        }

        .ornament.top-left {
            top: 30px;
            left: 30px;
        }

        .ornament.top-right {
            top: 30px;
            right: 30px;
        }

        .ornament.bottom-left {
            bottom: 30px;
            left: 30px;
        }

        .ornament.bottom-right {
            bottom: 30px;
            right: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo {
            font-size: 60px;
            margin-bottom: 15px;
        }

        .institution {
            font-size: 32px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 10px;
        }

        .title {
            font-size: 52px;
            font-weight: bold;
            color: #c7254e;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin: 25px 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .subtitle {
            font-size: 22px;
            color: #718096;
            font-style: italic;
        }

        .content {
            text-align: center;
            margin: 50px 0;
            line-height: 2;
        }

        .proclamation {
            font-size: 18px;
            color: #4a5568;
            margin-bottom: 20px;
        }

        .student-name {
            font-size: 44px;
            font-weight: bold;
            color: #c7254e;
            margin: 25px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .degree-title {
            font-size: 32px;
            font-weight: bold;
            color: #2d3748;
            margin: 30px 0;
            font-style: italic;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .description {
            font-size: 16px;
            color: #4a5568;
            margin: 25px 80px;
        }

        .course-name {
            font-size: 24px;
            font-weight: bold;
            color: #2d3748;
            margin: 20px 0;
        }

        .footer {
            margin-top: 70px;
            display: table;
            width: 100%;
        }

        .signature-block {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 0 20px;
        }

        .signature-line {
            border-top: 2px solid #2d3748;
            margin: 70px auto 10px;
            width: 200px;
        }

        .signature-name {
            font-size: 16px;
            font-weight: bold;
            color: #2d3748;
        }

        .signature-title {
            font-size: 13px;
            color: #718096;
            font-style: italic;
        }

        .certificate-details {
            margin-top: 50px;
            text-align: center;
            font-size: 11px;
            color: #a0aec0;
        }

        .seal {
            position: absolute;
            bottom: 120px;
            left: 90px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 6px solid #c7254e;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            font-size: 16px;
            font-weight: bold;
            color: #c7254e;
            text-align: center;
            line-height: 1.3;
        }

        .ribbon {
            position: absolute;
            top: 80px;
            right: 80px;
            background: #c7254e;
            color: white;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: bold;
            transform: rotate(15deg);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <div class="certificate">
        <div class="ornament top-left">❦</div>
        <div class="ornament top-right">❦</div>
        <div class="ornament bottom-left">❦</div>
        <div class="ornament bottom-right">❦</div>

        <div class="ribbon">DEGREE</div>

        <div class="header">
            <div class="logo">👨‍🎓</div>
            <div class="institution">KIM EDUTECH UNIVERSITY</div>
            <div class="title">Degree Certificate</div>
            <div class="subtitle">Be it known to all that</div>
        </div>

        <div class="content">
            <div class="student-name">{{ $student->name }}</div>

            <div class="proclamation">
                has successfully fulfilled all requirements and<br>
                is hereby conferred the degree of
            </div>

            <div class="degree-title">{{ $degree_title }}</div>

            <div class="description">
                in recognition of completing the advanced course program
            </div>

            <div class="course-name">"{{ $course->title }}"</div>

            <div class="description">
                with distinction, demonstrating exceptional knowledge and skill<br>
                Completed on {{ $completed_date->format('F d, Y') }}
            </div>
        </div>

        <div class="footer">
            <div class="signature-block">
                <div class="signature-line"></div>
                <div class="signature-name">{{ $instructor->name }}</div>
                <div class="signature-title">Course Director</div>
            </div>

            <div class="signature-block">
                <div class="signature-line"></div>
                <div class="signature-name">Dean of Education</div>
                <div class="signature-title">KIM Edutech</div>
            </div>

            <div class="signature-block">
                <div class="signature-line"></div>
                <div class="signature-name">University Chancellor</div>
                <div class="signature-title">KIM Edutech</div>
            </div>
        </div>

        <div class="certificate-details">
            Degree Certificate Number: {{ $certificate_number }}<br>
            Date of Conferral: {{ $issued_date->format('F d, Y') }}<br>
            Verification: https://edutech.example.com/verify/degree/{{ $certificate_number }}
        </div>

        <div class="seal">
            OFFICIAL<br>UNIVERSITY<br>SEAL
        </div>
    </div>
</body>

</html>