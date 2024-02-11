<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Livreur;
use App\Models\DetailCommande;
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

    public function livreur()
    {
        return $this->belongsTo(Livreur::class);
    }

    public function detailsCommande()
    {
        return $this->hasMany(DetailCommande::class);
    }
}
