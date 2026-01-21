<?php
namespace App\Models;
use CodeIgniter\Model;

class RemboursementModel extends Model
{
    protected $table = 'DemandeDeRemboursement';
    protected $primaryKey = 'id';
    protected $allowedFields = ['facture', 'produits', 'montant', 'raison'];

    public function creerDemande($factureId, $produitsSelectionnes, $montantTotal, $raisonComplete)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        //Génere un ID de groupe pour Produitsremboursement
        $idGroupeProduits = mt_rand(100000, 999999);
        while ($db->table('Produitsremboursement')->where('id', $idGroupeProduits)->countAllResults() > 0) {
            $idGroupeProduits = mt_rand(100000, 999999);
        }

        //Insere chaque produit coché
        foreach ($produitsSelectionnes as $p) {
            $db->table('Produitsremboursement')->insert([
                'id' => $idGroupeProduits,
                'nom_produit' => $p['nom_produit'],
                'quantite' => $p['quantite']
            ]);
        }

        //Creer la demande principale
        $this->insert([
            'facture' => $factureId,
            'produits' => $idGroupeProduits,
            'montant' => $montantTotal,
            'raison' => $raisonComplete
        ]);

        $db->transComplete();
        return $db->transStatus();
    }

    public function getProduitsAssocies($idGroupe) {
        return $this->db->table('Produitsremboursement')
                        ->where('id', $idGroupe)
                        ->get()
                        ->getResult();
    }

    public function nettoyerDemande($idDemande, $idGroupeProduits) {
        $this->db->table('Produitsremboursement')->where('id', $idGroupeProduits)->delete();
        return $this->delete($idDemande);
    }
}