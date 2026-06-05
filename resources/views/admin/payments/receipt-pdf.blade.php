<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #1e293b; padding: 30px; }
        .header { text-align: center; border-bottom: 2px solid #4F46E5; padding-bottom: 20px; margin-bottom: 20px; }
        .title { font-size: 22px; font-weight: bold; color: #0F172A; }
        .subtitle { font-size: 11px; color: #64748b; margin-top: 4px; }
        .receipt-num { font-size: 14px; font-weight: bold; color: #4F46E5; margin-top: 8px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin: 20px 0; }
        .info-item { background: #f8fafc; border-radius: 8px; padding: 12px; }
        .info-label { font-size: 10px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
        .info-value { font-size: 13px; font-weight: 600; color: #0F172A; margin-top: 2px; }
        .amount-box { background: #4F46E5; color: white; border-radius: 12px; padding: 20px; text-align: center; margin: 20px 0; }
        .amount-label { font-size: 11px; opacity: 0.8; }
        .amount { font-size: 28px; font-weight: bold; }
        .footer { text-align: center; font-size: 10px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 16px; margin-top: 20px; }
        .stamp { border: 2px solid #4F46E5; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; }
        .paid { font-size: 11px; font-weight: bold; color: #4F46E5; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">🚗 AVTOMAKTAB</div>
        <div class="subtitle">O'quv Jarayonlarini Boshqarish Tizimi</div>
        <div class="receipt-num">KVITANSIYA #{{ $payment->receipt_number }}</div>
    </div>

    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">O'quvchi</div>
            <div class="info-value">{{ $payment->student->user->name }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Telefon</div>
            <div class="info-value">{{ $payment->student->user->phone }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">To'lov turi</div>
            <div class="info-value">{{ $payment->type_name }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">To'lov usuli</div>
            <div class="info-value">{{ match($payment->payment_method) { 'cash'=>'Naqd pul', 'card'=>'Karta', 'transfer'=>"O'tkazma", 'online'=>'Online', default=>$payment->payment_method } }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Sana</div>
            <div class="info-value">{{ $payment->payment_date->format('d.m.Y') }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Qabul qildi</div>
            <div class="info-value">{{ $payment->receivedBy?->name ?? '—' }}</div>
        </div>
    </div>

    <div class="amount-box">
        <div class="amount-label">TO'LOV MIQDORI</div>
        <div class="amount">{{ number_format($payment->amount, 0, '.', ' ') }} so'm</div>
    </div>

    <div class="footer">
        <div class="stamp">
            <span class="paid">TO'LANDI</span>
        </div>
        <p>Ushbu kvitansiya avtomaktab boshqaruv tizimi orqali avtomatik yaratilgan.</p>
        <p style="margin-top: 4px;">Yaratilgan: {{ now()->format('d.m.Y H:i') }}</p>
    </div>
</body>
</html>
