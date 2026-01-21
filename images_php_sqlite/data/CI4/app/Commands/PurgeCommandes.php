<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class PurgeCommandes extends BaseCommand
{
    protected $group       = 'App';
    protected $name        = 'purge:commandes';
    protected $description = 'Supprime les commandes non payées datant de plus de 24h et celles datant de plus de deux ans';

    public function run(array $params)
    {
        $db = \Config\Database::connect();

        $dateLimite = date('Y-m-d H:i:s', strtotime('-1 day'));

        CLI::write("Début de la purge des commandes antérieures à : $dateLimite", 'yellow');

        $commandesAExpirer = $db->table('Commande')
            ->select('id, contenu')
            ->where('statut', value: 'En attente du paiement')
            ->where('created_at <', $dateLimite)
            ->get()
            ->getResult();

        if (empty($commandesAExpirer)) {
            CLI::write("Aucune commande impayée à supprimer.", 'green');
            return;
        }

        $count = 0;
        foreach ($commandesAExpirer as $commande) {
            $db->table('ProduitsCommande')->where('id', $commande->contenu)->delete();
            $db->table('Commande')->where('id', $commande->id)->delete();
            $count++;
        }

        $commandesAExpirer2 = $db->table('Commande')
            ->select('id, contenu')
            ->where('created_at <', date('Y-m-d H:i:s', strtotime('-2 year')))
            ->get()
            ->getResult();

        if (empty($commandesAExpirer2)) {
            CLI::write("Aucune commande payée à supprimer.", 'green');
            return;
        }

        foreach ($commandesAExpirer2 as $commande) {
            $db->table('ProduitsCommande')->where('id', $commande->contenu)->delete();
            $db->table('Commande')->where('id', $commande->id)->delete();
            $count++;
        }

        CLI::write("Purge terminée : $count commande(s) supprimée(s).", 'green');
    }
}