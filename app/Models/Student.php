<?php

namespace App\Models;

use App\Models\payment as ModelsPayment;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'students';

    protected $fillable = [
        'name',
        'date_birth',
        'lieu_birth',
        'level_id',
        'address',
        'niveau_etude',
        'phone',
        'profession',
        'isChecked',
        'class_id',
        'la_situation',
        'name_school',
        'service_mensuel',
        'droit_annuel',
        'promotion'
       
    ];

 

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function absence()
    {
        return $this->hasMany(Absence::class, 'student_id');
    }

    public function group() 
    {
        return $this->belongsTo(group::class, 'class_id');
    }

    public function payment()
    {
        return $this->hasMany(ModelsPayment::class, 'student_id');
    }
}
