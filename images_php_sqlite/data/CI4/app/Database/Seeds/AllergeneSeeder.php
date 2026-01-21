<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AllergeneSeeder extends Seeder
{
    public function run()
    {
        $data = [
            1  => ['nom' => 'jambon', 'tags' => []],
            2  => ['nom' => 'salade', 'tags' => []],
            3  => ['nom' => 'pain de mie', 'tags' => ['gluten']],
            4  => ['nom' => 'fromage', 'tags' => ['lait']],
            5  => ['nom' => 'tomate', 'tags' => []],
            6  => ['nom' => 'oeuf', 'tags' => ['oeufs']],
            7  => ['nom' => 'mayonnaise', 'tags' => ['oeufs', 'moutarde']],
            8  => ['nom' => 'beurre', 'tags' => ['lait']],
            9  => ['nom' => 'moutarde', 'tags' => ['moutarde']],
            10 => ['nom' => 'cornichon', 'tags' => ['sulfites']],
            11 => ['nom' => 'thon', 'tags' => ['poissons']],
            12 => ['nom' => 'oignon', 'tags' => []],
            13 => ['nom' => 'poivron', 'tags' => []],
            14 => ['nom' => 'courgette', 'tags' => []],
            15 => ['nom' => 'carotte', 'tags' => []],
            16 => ['nom' => 'pomme de terre', 'tags' => []],
            17 => ['nom' => 'laitue', 'tags' => []],
            18 => ['nom' => 'avocat', 'tags' => []],
            19 => ['nom' => 'poulet', 'tags' => []],
            20 => ['nom' => 'bacon', 'tags' => []],
            21 => ['nom' => 'steak haché', 'tags' => []],
            22 => ['nom' => 'champignon', 'tags' => []],
            23 => ['nom' => 'épinard', 'tags' => []],
            24 => ['nom' => 'feta', 'tags' => ['lait']],
            25 => ['nom' => 'olive', 'tags' => []],
            26 => ['nom' => 'câpre', 'tags' => []],
            27 => ['nom' => 'sauce tomate', 'tags' => []],
            28 => ['nom' => 'ketchup', 'tags' => ['celeri']],
            29 => ['nom' => 'sauce barbecue', 'tags' => ['soja', 'moutarde']],
            30 => ['nom' => 'jalapeño', 'tags' => []],
            31 => ['nom' => 'ananas', 'tags' => []],
            32 => ['nom' => 'poulet pané', 'tags' => ['gluten', 'oeufs']],
            33 => ['nom' => 'cheddar', 'tags' => ['lait']],
            34 => ['nom' => 'pain burger', 'tags' => ['gluten', 'sesame']],
            35 => ['nom' => 'sauce secrete', 'tags' => ['oeufs', 'moutarde', 'soja']],
            36 => ['nom' => 'pain baguette', 'tags' => ['gluten']],
            37 => ['nom' => 'pain hot-dog', 'tags' => ['gluten']],
            38 => ['nom' => 'merguez', 'tags' => []],
            39 => ['nom' => 'pain pita', 'tags' => ['gluten']],
            40 => ['nom' => 'boeuf', 'tags' => []],
            41 => ['nom' => 'fromage vegan', 'tags' => ['soja']],
            42 => ['nom' => 'Aubergine', 'tags' => []],
        ];

        $finalData = [];

        foreach ($data as $id => $info) {
            $row = [
                'id_ingredient'  => $id,
                'gluten'         => in_array('gluten', $info['tags']) ? 1 : 0,
                'crustaces'      => in_array('crustaces', $info['tags']) ? 1 : 0,
                'oeufs'          => in_array('oeufs', $info['tags']) ? 1 : 0,
                'poissons'       => in_array('poissons', $info['tags']) ? 1 : 0,
                'arachides'      => in_array('arachides', $info['tags']) ? 1 : 0,
                'soja'           => in_array('soja', $info['tags']) ? 1 : 0,
                'lait'           => in_array('lait', $info['tags']) ? 1 : 0,
                'fruits_a_coque' => in_array('fruits_a_coque', $info['tags']) ? 1 : 0,
                'celeri'         => in_array('celeri', $info['tags']) ? 1 : 0,
                'moutarde'       => in_array('moutarde', $info['tags']) ? 1 : 0,
                'sesame'         => in_array('sesame', $info['tags']) ? 1 : 0,
                'sulfites'       => in_array('sulfites', $info['tags']) ? 1 : 0,
                'lupin'          => in_array('lupin', $info['tags']) ? 1 : 0,
                'mollusques'     => in_array('mollusques', $info['tags']) ? 1 : 0,
            ];
            $finalData[] = $row;
        }

        $this->db->table('Allergenes')->insertBatch($finalData);
    }
}