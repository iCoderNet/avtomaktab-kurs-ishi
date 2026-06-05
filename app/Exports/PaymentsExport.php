<?php
namespace App\Exports;
use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaymentsExport implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return Payment::with('student.user')
            ->when($this->startDate, fn($q) => $q->whereBetween('payment_date', [$this->startDate, $this->endDate]))
            ->get()
            ->map(fn($p) => [
                $p->receipt_number,
                $p->student->user->name,
                $p->type_name,
                number_format($p->amount, 0, '.', ' ') . ' so\'m',
                $p->payment_method,
                $p->payment_date->format('d.m.Y'),
                match($p->status) { 'completed' => "To'liq", 'pending' => "Kutilmoqda", default => $p->status },
            ]);
    }

    public function headings(): array
    {
        return ['Kvitansiya', 'O\'quvchi', 'Tur', 'Miqdor', 'Usul', 'Sana', 'Holat'];
    }
}
