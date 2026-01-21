<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\ProduitEntity;

class ProduitModel extends Model
{

    protected $table = 'Produit';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = ProduitEntity::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'nom',
        'descr',
        'prix',
        'Ingredients',
        'Image',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $validationRules = [
        'nom' => 'required|min_length[3]|max_length[255]',
        'prix' => 'required|numeric|greater_than[0]',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Récupère tous les produits sous forme de tableau d'objets
     */
    public function getAllProduits()
    {
        return $this->findAll();
    }

    /**
     * Liste des produits pour l'admin
     */
    public function getProduitsForAdmin()
    {
        return $this->select('id, nom, prix')
            ->orderBy('id', 'DESC')
            ->findAll();
    }

    /**
     * Supprime un produit et ses dépendances
     */
    public function deleteProduitWithDependencies(int $id): bool
    {
        $produit = $this->asArray()->find($id);

        if (!$produit) {
            return false;
        }

        // Supprimer les ingrédients liés
        $this->db->table('Ingredients')->where('id_liste', $id)->delete();

        // Supprimer l'image si elle existe
        if (!empty($produit['Image']) && file_exists(FCPATH . 'assets/images/produits/' . $produit['Image'])) {
            unlink(FCPATH . 'assets/images/produits/' . $produit['Image']);
        }

        // Supprimer le produit
        return $this->delete($id);
    }

    /**
     * Crée un produit avec ses ingrédients
     */
    public function createProduitWithIngredients(array $dataProduit, array $ingredients = []): ?int
    {
        // Insérer le produit
        $dataProduit['Ingredients'] = 1;
        $this->insert($dataProduit);
        $newProduitId = $this->getInsertID();

        if (!$newProduitId) {
            return null;
        }

        // Mettre à jour les ingrédients
        if (!empty($ingredients)) {
            $builderPivot = $this->db->table('Ingredients');

            foreach ($ingredients as $idIngredient) {
                $existing = $builderPivot->where([
                    'id_liste' => $newProduitId,
                    'id_prod' => $idIngredient
                ])->get()->getRow();

                if ($existing) {
                    $builderPivot->where([
                        'id_liste' => $newProduitId,
                        'id_prod' => $idIngredient
                    ])->update([
                                'quantite' => $existing->quantite + 1
                            ]);
                } else {
                    $builderPivot->insert([
                        'id_liste' => $newProduitId,
                        'id_prod' => $idIngredient,
                        'quantite' => 1,
                    ]);
                }
            }
        }

        // Mettre à jour la colonne Ingredients avec l'ID du produit
        $this->update($newProduitId, ['Ingredients' => $newProduitId]);

        return $newProduitId;
    }

    /**
     * Recherche de produits par terme
     */
    public function rechercherProduits(string $query)
    {
        if (empty($query)) {
            return $this->findAll();
        }

        return $this->like('nom', $query)
            ->orLike('descr', $query)
            ->findAll();
    }

    /**
     * Suggestions de produits pour l'autocomplétion (retourne array)
     */
    public function getSuggestions(string $query, int $limit = 5): array
    {
        if (empty($query) || strlen($query) < 2) {
            return [];
        }

        return $this->select('id, nom, Image, prix')
            ->like('nom', $query)
            ->limit($limit)
            ->asArray()
            ->findAll();
    }

    /**
     * Récupère les allergènes par produit
     */
    public function getAllergenesParProduit()
    {
        return $this->db->table('Produit p')
            ->select('p.nom as produit_nom, 
            MAX(a.gluten) as gluten, MAX(a.crustaces) as crustaces, 
            MAX(a.oeufs) as oeufs, MAX(a.poissons) as poissons, 
            MAX(a.arachides) as arachides, MAX(a.soja) as soja, 
            MAX(a.lait) as lait, MAX(a.fruits_a_coque) as fruits_a_coque, 
            MAX(a.celeri) as celeri, MAX(a.moutarde) as moutarde, 
            MAX(a.sesame) as sesame, MAX(a.sulfites) as sulfites, 
            MAX(a.lupin) as lupin, MAX(a.mollusques) as mollusques')
            ->join('Ingredients li', 'li.id_liste = p.Ingredients')
            ->join('Allergenes a', 'a.id_ingredient = li.id_prod')
            ->groupBy('p.id')
            ->get()
            ->getResultArray();
    }

    /**
     * Recherche de produits avec leurs IDs d'ingrédients
     */
    public function rechercherProduitsAvecIngredients(string $query)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('Produit');

        if (!empty($query)) {
            $builder->like('nom', $query)
                ->orLike('descr', $query);
        }

        $produits = $builder->get()->getResult();

        // chaque produit a ses ingrédients
        foreach ($produits as $produit) {
            $ingredients = $db->table('Ingredients')
                ->select('id_prod')
                ->where('id_liste', $produit->id)
                ->get()
                ->getResultArray();

            $produit->ingredient_ids = array_column($ingredients, 'id_prod');
        }

        return $produits;
    }
}