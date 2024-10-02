<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    use HasFactory;

    protected $table = 'guardians';

    protected $fillable = [
        'CIN',
        'first_name',
        'last_name',
        'phone',
        'address',
        'user_id',
    ];

    // public function students(){
    //     return $this->hasMany(Student::class);
    // }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
