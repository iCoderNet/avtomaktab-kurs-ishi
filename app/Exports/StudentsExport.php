<?php
namespace App\Exports;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Student::with('user', 'instructor.user')
            ->get()
            ->map(fn($s) => [
                $s->student_number,
                $s->user->name,
                $s->user->phone,
                $s->user->email,
                $s->license_category . ' toifa',
                $s->instructor?->user->name ?? '—',
                match($s->status) {
                    'active' => 'Faol',
                    'completed' => 'Tugatdi',
                    'inactive' => 'Nofaol',
                    'expelled' => 'Chiqarildi',
                    default => $s->status
                },
                $s->enrollment_date->format('d.m.Y'),
                number_format($s->total_fee, 0, '.', ' ') . ' so\'m',
                number_format($s->paid_amount, 0, '.', ' ') . ' so\'m',
                number_format($s->debt, 0, '.', ' ') . ' so\'m',
            ]);
    }

    public function headings(): array
    {
        return [
            'Raqami', 'Ism Familiya', 'Telefon', 'Email', 'Toifa',
            'Instruktor', 'Holat', 'Ro\'yxatga olingan', 'Jami to\'lov',
            'To\'langan', 'Qarz',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
