<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicineGroup;
use App\Models\Medicine;

class DemoMedicinesSeeder extends Seeder
{
    public function run(): void
    {
        $generique = MedicineGroup::firstOrCreate([
            'nom' => 'Medecine generique'
        ]);

        $diabete = MedicineGroup::firstOrCreate([
            'nom' => 'Diabete'
        ]);

        $medicines = [
            [
                'nom' => 'Augmentin 625 Duo Comprime',
                'code_medicament' => 'D06ID232435454',
                'stock' => 350,
                'seuil_alerte' => 30,
                'photo_url' => 'images/augmentin-625-duo-comprime.webp',
                'medicine_group_id' => $generique->id,
            ],

            [
                'nom' => 'Azithral-500 Comprime',
                'code_medicament' => 'D06ID232435451',
                'stock' => 20,
                'seuil_alerte' => 30,
                'photo_url' => 'images/azithral-500-comprime.jpeg',
                'medicine_group_id' => $generique->id,
            ],

            [
                'nom' => 'Sirop Ascoril LS',
                'code_medicament' => 'D06ID232435452',
                'stock' => 85,
                'seuil_alerte' => 30,
                'photo_url' => 'images/sirop-ascoril-ls.avif',
                'medicine_group_id' => $diabete->id,
            ],

            [
                'nom' => 'Azee 500 Comprime',
                'code_medicament' => 'D06ID232435450',
                'stock' => 75,
                'seuil_alerte' => 30,
                'photo_url' => 'images/azee-500-comprime.webp',
                'medicine_group_id' => $generique->id,
            ],

            [
                'nom' => 'Allegra 120mg Comprime',
                'code_medicament' => 'D06ID232435455',
                'stock' => 44,
                'seuil_alerte' => 30,
                'photo_url' => 'images/allegra-120mg-comprime.jfif',
                'medicine_group_id' => $diabete->id,
            ],

            [
                'nom' => "Sirop d'Alex",
                'code_medicament' => 'D06ID232435456',
                'stock' => 65,
                'seuil_alerte' => 30,
                'photo_url' => 'images/sirop-d-alex.webp',
                'medicine_group_id' => $generique->id,
            ],

            [
                'nom' => 'Amoxyclav-625 Comprime',
                'code_medicament' => 'D06ID232435457',
                'stock' => 150,
                'seuil_alerte' => 30,
                'photo_url' => 'images/amoxyclav-625-comprime.avif',
                'medicine_group_id' => $generique->id,
            ],

            [
                'nom' => 'Avil-25 Tablette',
                'code_medicament' => 'D06ID232435458',
                'stock' => 270,
                'seuil_alerte' => 30,
                'photo_url' => 'images/avil-25-tablette.png',
                'medicine_group_id' => $generique->id,
            ],
        ];

        foreach ($medicines as $medicineData) {
            Medicine::updateOrCreate(
                [
                    'code_medicament' => $medicineData['code_medicament'],
                ],
                $medicineData
            );
        }
    }
}