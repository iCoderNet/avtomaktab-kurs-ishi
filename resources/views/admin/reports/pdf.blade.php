<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Hisobot {{ $startDate->format('d.m.Y') }} — {{ $endDate->format('d.m.Y') }}</title>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            src: url('{{ storage_path("fonts/DejaVuSans.ttf") }}') format('truetype');
            font-weight: normal;
        }
        @font-face {
            font-family: 'DejaVu Sans';
            src: url('{{ storage_path("fonts/DejaVuSans-Bold.ttf") }}') format('truetype');
            font-weight: bold;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1e293b; background: #fff; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #4f46e5; padding-bottom: 12px; margin-bottom: 16px; }
        .header h1 { font-size: 18px; color: #4f46e5; font-weight: bold; }
        .header p { font-size: 10px; color: #64748b; margin-top: 4px; }
        .period { background: #f1f5f9; border-left: 3px solid #4f46e5; padding: 8px 12px; margin-bottom: 16px; font-size: 10px; }
        .section-title { font-size: 12px; font-weight: bold; color: #0f172a; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px; margin: 16px 0 8px; }
        table { width: 100%; border-collapse: collapse; font-size: 9px; }
        th { background: #4f46e5; color: #fff; padding: 6px 8px; text-align: left; font-weight: bold; }
        td { padding: 5px 8px; border-bottom: 1px solid #e2e8f0; }
        tr:nth-child(even) td { background: #f8fafc; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 8px; font-weight: bold; }
        .badge-active { background: #dcfce7; color: #16a34a; }
        .badge-inactive { background: #fef9c3; color: #ca8a04; }
        .badge-completed { background: #dbeafe; color: #2563eb; }
        .badge-expelled { background: #fee2e2; color: #dc2626; }
        .badge-completed-pay { background: #dcfce7; color: #16a34a; }
        .badge-pending { background: #fef9c3; color: #ca8a04; }
        .badge-cancelled { background: #fee2e2; color: #dc2626; }
        .footer { margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 8px; text-align: center; color: #94a3b8; font-size: 8px; }
        .amount { text-align: right; font-family: monospace; }
    </style>
</head>
<body>

<div class="header">
    <h1>Avtomaktab Boshqaruv Tizimi</h1>
    <p>Umumiy Hisobot</p>
</div>

<div class="period">
    <strong>Davr:</strong> {{ $startDate->format('d.m.Y') }} — {{ $endDate->format('d.m.Y') }}
    &nbsp;&nbsp;|&nbsp;&nbsp;
    <strong>Chiqarish sanasi:</strong> {{ now()->format('d.m.Y H:i') }}
</div>

{{-- O'QUVCHILAR JADVALI --}}
<div class="section-title">O'quvchilar ro'yxati (Jami: {{ $students->count() }})</div>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Raqam</th>
            <th>Ism Familiya</th>
            <th>Toifa</th>
            <th>Instruktor</th>
            <th>Jami to'lov</th>
            <th>To'langan</th>
            <th>Qarz</th>
            <th>Holat</th>
        </tr>
    </thead>
    <tbody>
        @foreach($students as $i => $student)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $student->student_number }}</td>
            <td>{{ $student->user?->name ?? '—' }}</td>
            <td>{{ $student->license_category }}</td>
            <td>{{ $student->instructor?->user?->name ?? '—' }}</td>
            <td class="amount">{{ number_format($student->total_fee, 0, '.', ' ') }}</td>
            <td class="amount">{{ number_format($student->paid_amount, 0, '.', ' ') }}</td>
            <td class="amount">{{ number_format(max(0, $student->total_fee - $student->paid_amount), 0, '.', ' ') }}</td>
            <td>
                @php $statusMap = ['active'=>'Faol','inactive'=>'Nofaol','completed'=>'Tugatdi','expelled'=>'Chiqarildi']; @endphp
                <span class="badge badge-{{ $student->status }}">{{ $statusMap[$student->status] ?? $student->status }}</span>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- TO'LOVLAR JADVALI --}}
@if($payments->isNotEmpty())
<div class="section-title">To'lovlar ({{ $startDate->format('d.m.Y') }} — {{ $endDate->format('d.m.Y') }}, Jami: {{ $payments->count() }})</div>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>O'quvchi</th>
            <th>Summa</th>
            <th>To'lov turi</th>
            <th>Sana</th>
            <th>Holat</th>
        </tr>
    </thead>
    <tbody>
        @foreach($payments as $i => $payment)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $payment->student?->user?->name ?? '—' }}</td>
            <td class="amount">{{ number_format($payment->amount, 0, '.', ' ') }} so'm</td>
            <td>{{ $payment->type_name }}</td>
            <td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d.m.Y') : '—' }}</td>
            <td>
                @php $payMap = ['completed'=>'Bajarildi','pending'=>'Kutilmoqda','cancelled'=>"Bekor qilindi"]; @endphp
                <span class="badge badge-{{ $payment->status === 'completed' ? 'completed-pay' : ($payment->status === 'pending' ? 'pending' : 'cancelled') }}">
                    {{ $payMap[$payment->status] ?? $payment->status }}
                </span>
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" style="text-align:right; font-weight:bold; padding: 6px 8px;">Jami to'lovlar:</td>
            <td class="amount" style="font-weight:bold;">{{ number_format($payments->where('status','completed')->sum('amount'), 0, '.', ' ') }} so'm</td>
            <td colspan="3"></td>
        </tr>
    </tfoot>
</table>
@endif

<div class="footer">
    Avtomaktab Boshqaruv Tizimi &copy; {{ date('Y') }} — Ushbu hujjat avtomatik tarzda yaratilgan
</div>

</body>
</html>
