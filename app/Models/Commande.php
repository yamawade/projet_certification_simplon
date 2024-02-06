<?php

namespace App\Models;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_commande',
        'client_id',
        'etat_commande',
        'livreur_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
