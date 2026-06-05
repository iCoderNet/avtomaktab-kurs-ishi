<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; width: 297mm; height: 210mm; padding: 20mm; background: white; }
        .cert-border { border: 8px double #4F46E5; padding: 30px; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; }
        .logo { font-size: 40px; margin-bottom: 10px; }
        .org-name { font-size: 16px; font-weight: bold; color: #4F46E5; text-transform: uppercase; letter-spacing: 3px; }
        .cert-title { font-size: 32px; font-weight: bold; color: #0F172A; margin: 20px 0 10px; }
        .cert-text { font-size: 14px; color: #475569; margin: 5px 0; }
        .student-name { font-size: 28px; font-weight: bold; color: #0F172A; border-bottom: 2px solid #4F46E5; padding-bottom: 5px; margin: 15px 0; }
        .category { font-size: 18px; color: #4F46E5; font-weight: bold; background: #EEF2FF; padding: 6px 24px; border-radius: 20px; display: inline-block; margin: 10px 0; }
        .cert-num { font-size: 11px; color: #94a3b8; margin-top: 20px; }
        .footer { display: flex; justify-content: space-between; width: 100%; margin-top: 30px; }
        .sign-block { text-align: center; }
        .sign-line { width: 120px; border-bottom: 1px solid #0F172A; margin: 0 auto 4px; }
        .sign-text { font-size: 10px; color: #64748b; }
        .date { font-size: 12px; color: #475569; }
    </style>
</head>
<body>
    <div class="cert-border">
        <div class="logo">🚗</div>
        <div class="org-name">Avtomaktab O'quv Markazi</div>
        <div class="cert-title">SERTIFIKAT</div>
        <div class="cert-text">Ushbu sertifikat</div>
        <div class="student-name">{{ $certificate->student->user->name }}</div>
        <div class="cert-text">haydovchilik kursini muvaffaqiyatli tamomladi va</div>
        <div class="category">{{ $certificate->license_category }} TOIFALI HAYDOVCHILIK GUVOHNOMASI</div>
        <div class="cert-text">olishga haqli ekanligini tasdiqlaymiz.</div>

        <div class="footer">
            <div class="sign-block">
                <div class="sign-line"></div>
                <div class="sign-text">Rahbar imzosi</div>
            </div>
            <div style="text-align: center">
                <div class="date">{{ $certificate->issued_date->format('d.m.Y') }}</div>
                <div class="cert-num">Seriya: {{ $certificate->certificate_number }}</div>
            </div>
            <div class="sign-block">
                <div class="sign-line"></div>
                <div class="sign-text">Muhr</div>
            </div>
        </div>
    </div>
</body>
</html>
