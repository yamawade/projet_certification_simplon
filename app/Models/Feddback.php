<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feddback extends Model
{
    use HasFactory , Notifiable;
    protected $fillable = [
        'nom',
        'email',
        'message',
        'numero_tel'
    ];
}
