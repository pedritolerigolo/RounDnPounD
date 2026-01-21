<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAlmostAllTable extends Migration
{
    public function up()
    {
        // --- 1. TABLES INDÉPENDANTES ---

        // Table Ingredient
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'nom' => ['type' => 'VARCHAR', 'constraint' => 255],
            'quantite' => ['type' => 'INT'],
            'fournisseur' => ['type' => 'INT'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('fournisseur', 'Fournisseur', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('Ingredient');

        // Table Fournisseur
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'nom' => ['type' => 'VARCHAR', 'constraint' => 255],
            'contact' => ['type' => 'VARCHAR', 'constraint' => 255],
            'adresse' => ['type' => 'VARCHAR', 'constraint' => 255],
        ]);
        $this->forge->addKey(['id'], true);
        $this->forge->createTable('Fournisseur');

        // --- 2. TABLES AVEC DÉPENDANCES ---

        // Table Avis
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'id_client' => ['type' => 'INT'],
            'id_produit' => ['type' => 'INT'],
            'note' => ['type' => 'INT'],
            'texte' => ['type' => 'VARCHAR', 'constraint' => 255],
        ]);
        $this->forge->addKey(['id'], true);
        $this->forge->addForeignKey('id_client', 'Client', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('id_produit', 'Produit', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('Avis');

        // Table Ingredients        
        $this->forge->addField([
            'id_liste' => ['type' => 'INT'],
            'id_prod' => ['type' => 'INT'],
            'quantite' => ['type' => 'INT'],
        ]);
        $this->forge->addKey(['id_liste', 'id_prod'], true);
        $this->forge->createTable('Ingredients');

        // Table Produit
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'nom' => ['type' => 'VARCHAR', 'constraint' => 255],
            'descr' => ['type' => 'TEXT', 'null' => true],
            'prix' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'Ingredients' => ['type' => 'INT'],
            'Image' => ['type' => 'VARCHAR', 'constraint' => 255],
        ]);
        $this->forge->addKey(['id'], true);
        $this->forge->addForeignKey('Ingredients', 'Ingredients', 'id_liste', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('Produit');

        // Table ProduitsPanier (Table de groupement/jonction)
        $this->forge->addField([
            'id' => ['type' => 'INT'],
            'id_produit' => ['type' => 'INT'],
            'quantite' => ['type' => 'INT'],
        ]);
        $this->forge->addKey(['id', 'id_produit'], true);
        $this->forge->addForeignKey('id_produit', 'Produit', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('ProduitsPanier');

        // Table ProduitsWishList
        $this->forge->addField([
            'id_client' => ['type' => 'INT'],
            'id_produit' => ['type' => 'INT'],
        ]);
        $this->forge->addKey(['id_client', 'id_produit'], true);
        $this->forge->addForeignKey('id_produit', 'Produit', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('id_client', 'Client', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('ProduitsWishList');

        // Table Commande
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'commanditaire' => ['type' => 'VARCHAR', 'constraint' => 255],
            'adresseLivraison' => ['type' => 'TEXT'],
            'prixTotal' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'contenu' => ['type' => 'INT'],
            'statut' => ['type' => 'VARCHAR', 'constraint' => 100],
            'panier' => ['type' => 'BOOLEAN', 'default' => false],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('contenu', 'ProduitsCommande', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('Commande');

        // Table ProduitsCommande
        $this->forge->addField([
            'id' => ['type' => 'INT'],
            'id_produit' => ['type' => 'INT'],
            'quantite' => ['type' => 'INT'],
        ]);
        $this->forge->addKey(['id', 'id_produit'], true);
        $this->forge->createTable('ProduitsCommande');

        // Table Produitsremboursement
        $this->forge->addField([
            'id' => ['type' => 'INT'],
            'nom_produit' => ['type' => 'INT'],
            'quantite' => ['type' => 'INT'],
        ]);
        $this->forge->addKey(['id', 'nom_produit'], true);
        $this->forge->createTable('Produitsremboursement');

        // Table DemandeDeRemboursement
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'facture' => ['type' => 'INT'],
            'produits' => ['type' => 'INT'],
            'montant' => ['type' => 'INT'],
            'raison' => ['type' => 'VARCHAR', 'constraint' => 255],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('produits', 'Produitsremboursement', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('DemandeDeRemboursement');

        // Table Panier
        $this->forge->addField([
            'client' => ['type' => 'INT'],
            'prix_total' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'produits' => ['type' => 'INT'],
        ]);
        $this->forge->addKey('client', true);
        $this->forge->addForeignKey('produits', 'ProduitsPanier', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('Panier');

        // Table Adresse
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'client' => ['type' => 'INT'],
            'ville' => ['type' => 'VARCHAR', 'constraint' => 100],
            'code_postal' => ['type' => 'VARCHAR', 'constraint' => 20],
            'addresse_complete' => ['type' => 'VARCHAR', 'constraint' => 255],
            'pays' => ['type' => 'VARCHAR', 'constraint' => 100],
            'info_suppl' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('Adresse');

        // Table Allergenes
        $this->forge->addField([
            'id_ingredient' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'gluten' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'crustaces' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'oeufs' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'poissons' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'arachides' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'soja' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'lait' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'fruits_a_coque' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'celeri' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'moutarde' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'sesame' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'sulfites' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'lupin' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'mollusques' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
        ]);
        $this->forge->addKey('id_ingredient', true);
        $this->forge->addForeignKey('id_ingredient', 'Ingredient', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('Allergenes');
    }


    public function down()
    {
        $this->db->simpleQuery('PRAGMA foreign_keys = OFF');

        $this->forge->dropTable('ListeDeSouhait', true);
        $this->forge->dropTable('Panier', true);
        $this->forge->dropTable('DemandeDeRemboursement', true);
        $this->forge->dropTable('Facture', true);
        $this->forge->dropTable('Reglement', true);
        $this->forge->dropTable('Commande', true);
        $this->forge->dropTable('Produit', true);
        $this->forge->dropTable('Ingredients', true);
        $this->forge->dropTable('Fournisseur', true);
        $this->forge->dropTable('Produits', true);
        $this->forge->dropTable('CoordonneesBancaire', true);
        $this->forge->dropTable('Ingredient', true);

        $this->db->simpleQuery('PRAGMA foreign_keys = ON');
    }
}