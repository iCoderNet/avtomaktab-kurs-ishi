<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'receipt_number', 'amount', 'type', 'payment_method',
        'status', 'payment_date', 'month_for', 'notes', 'received_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    // To'lov turini o'zbekchaga tarjima qilish
    public function getTypeNameAttribute(): string
    {
        return match($this->type) {
            'tuition' => "O'quv to'lovi",
            'exam_fee' => "Imtihon to'lovi",
            'certificate' => "Sertifikat",
            'penalty' => "Jarima",
            default => "Boshqa",
        };
    }
}
