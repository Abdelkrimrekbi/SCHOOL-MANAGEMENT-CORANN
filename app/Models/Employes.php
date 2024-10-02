<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employes extends Model
{
    use HasFactory;

    protected $table = 'employes';

    protected $fillable = [
        'CIN',
        'first_name',
        'last_name',
        'phone',
        'address',
        'amount',
        'job',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
