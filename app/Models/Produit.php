<?php

namespace App\Models;

use App\Models\Categorie;
use App\Models\Commercant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_produit',
        'prix',
        'quantite',
        'image',
        'description',
        'commercant_id',
        'categorie_id'
    ];

    public function commercant()
    {
        return $this->belongsTo(Commercant::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function paniers() {
        return $this->belongsToMany(Panier::class, 'panier_produits')->withPivot('quantite');
    }

    public function decrementerQuantite($quantite) {
        $this->quantite -= $quantite;
        $this->save();
    }

    // public function incrementerQuantite($quantite) {
    //     $this->quantite += $quantite;
    //     $this->save();
    // }
}
