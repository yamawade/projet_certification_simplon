<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commercant extends Model
{
    use HasFactory;

    protected $fillable = [
        'ninea',
        'adresse',
        'nin',
        'date_naiss'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
