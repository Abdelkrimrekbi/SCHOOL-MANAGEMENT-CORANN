<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'amount',
        'date_payment',
        'type',
        'month',
        'rest',
        'promotion',
        'comments',
        'student_id',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    
    public function paymentByMonth()
    {
      
            $configuration = Configuration::orderBy('created_at', 'desc')->first();
            if ($configuration) {
                return $this->selectRaw('MONTHNAME(created_at) as month, SUM(amount) as total')
                    ->where('promotion', $configuration->promotion)
                    ->groupByRaw('MONTHNAME(created_at)')
                    ->orderByRaw('MONTHNAME(created_at)')
                    ->get();
            }
            else {
                return collect();
            }
    }

    public static function totalIncomeAndPaymentAmount()
    {

        $configuration = Configuration::orderBy('created_at', 'desc')->first();
        if ($configuration) {
            $totalIncome = Income::where('promotion', $configuration->promotion)->sum('amount');
            $totalePayment = Payment::where('promotion', $configuration->promotion)->sum('amount');
            return $totalePayment + $totalIncome;
        }
        else {
            return 0;
        }
    }

}
