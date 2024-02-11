<?php

namespace App\Models;

use App\Models\Produit;
use App\Models\Commande;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailCommande extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id',
        'produit_id',
        'nombre_produit',
        'montant'
    ];

    public function produit() {
        return $this->belongsTo(Produit::class);
    }

    public function commande() {
        return $this->belongsTo(Commande::class);
    }

}
