<?php

namespace App\Notifications;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AffecterLivreur extends Notification
{
    use Queueable;

    protected $commande;

    /**
     * Create a new notification instance.
     */
    public function __construct(Commande $commande)
    {
        $this->commande = $commande;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $affectation = $this->commande->detailsCommande()->with('produit.commercant')->get();

        $informationsVendeurs = [];
        $informationsUniques = collect();

        foreach ($affectation as $detail) {
            $vendeur = $detail->produit->commercant;
            $informations = "Adresse Commercant: {$vendeur->adresse}, Numéro téléphone commercant: {$vendeur->user->numero_tel}";

            // Vérifier si les informations sont déjà présentes
            if (!$informationsUniques->contains($informations)) {
                $informationsVendeurs[] = $informations;
                $informationsUniques->push($informations);
            }
        }

        // return (new MailMessage)
        //     ->line('Bonjour, Cette commande vous est affectée')
        //     ->line('Adresse du client: ' . $this->commande->client->adresse)
        //     ->line('Nom du client: ' . $this->commande->client->user->prenom.' '.$this->commande->client->user->nom)
        //     ->line('Numéro du client: ' . $this->commande->client->user->numero_tel)
        //     ->line('Informations vendeurs:')
        //     ->line(implode(PHP_EOL, $informationsVendeurs));

        // $affectation = $this->commande->detailsCommande()->with('produit.commercant')->get()->pluck('produit.commercant.adresse')->unique()->toArray();
        // return (new MailMessage)
        //             ->line('Bonjour, Cette commande vous étes affecté')
        //             ->line('Adresse du client: ' . $this->commande->client->adresse)
        //             ->line('Nom du client: ' . $this->commande->client->user->prenom.' '.$this->commande->client->user->nom)
        //             ->line('Numéro du client: ' . $this->commande->client->user->numero_tel) 
        //             //->line('Adresses des vendeurs:')
        //             //->line(implode(PHP_EOL, $affectation));
                    //->line('Informations vendeurs:'.$affectation);
        return (new MailMessage)
                ->view('envoieMailLivreur', 
                [
                    'adresseClient' => $this->commande->client->adresse,
                    'nomClient' => $this->commande->client->user->prenom.' '.$this->commande->client->user->nom,
                    'numeroClient' => $this->commande->client->user->numero_tel,
                    'informationsVendeurs' => $informationsVendeurs
                ])
                ->subject('Commande affecté');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
