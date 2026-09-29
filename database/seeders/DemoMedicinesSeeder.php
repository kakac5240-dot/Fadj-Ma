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

                'composition' => 'Amoxycillin-500MG + Clavulanic Acid-122MG',
                'fabricant' => 'GlaxoSmithKlin Pharmaceutical ltd',
                'type_consommation' => 'Oral',
                'date_expiration' => '2027-01-25',

                'description' => "Augmentin 625 Duo Comprimé est utilisé pour traiter les infections bactériennes du corps qui affectent la peau, les tissus mous, les poumons, les oreilles, les voies urinaires et les sinus nasaux. Il convient de mentionner que les infections virales comme la grippe et le rhume ne sont pas traitées par ce médicament.

Augmentin 625 Duo Tablet se compose de deux médicaments : l’amoxicilline et l’acide clavulanique. L’amoxicilline agit en détruisant la couche protéique externe, tuant ainsi les bactéries. L’acide clavulanique inhibe l’enzyme bêta-lactamase, qui empêche les bactéries de détruire l’efficacité de l’amoxicilline. En conséquence, l’action de l’acide clavulanique permet à l’amoxicilline de tuer les bactéries.

La dose d’Augmentin 625 Duo Tablet peut varier en fonction de votre état et de la gravité de l’infection. Il est recommandé de terminer le traitement selon la prescription médicale, même si vous vous sentez mieux. Les effets secondaires courants peuvent comprendre des vomissements, des nausées et de la diarrhée. En cas d’inconfort, parlez-en à un médecin.

Avant de commencer Augmentin 625 Duo Tablet, veuillez informer votre médecin si vous avez une allergie aux antibiotiques ou des problèmes rénaux ou hépatiques. Ne prenez pas Augmentin 625 Duo Tablet en automédication. Pour les enfants, le médicament doit être utilisé selon la prescription d’un médecin, avec une dose adaptée notamment au poids et à la gravité de l’infection."
            ],

            [
                'nom' => 'Azithral-500 Comprime',
                'code_medicament' => 'D06ID232435451',
                'stock' => 20,
                'seuil_alerte' => 30,
                'photo_url' => 'images/azithral-500-comprime.jpeg',
                'medicine_group_id' => $generique->id,

                'composition' => 'Azithromycine 500 MG',
                'fabricant' => 'Alembic Pharmaceuticals',
                'type_consommation' => 'Oral',
                'date_expiration' => '2027-06-15',

                'description' => 'Médicament antibiotique utilisé selon prescription médicale pour certaines infections bactériennes.'
            ],

            [
                'nom' => 'Sirop Ascoril LS',
                'code_medicament' => 'D06ID232435452',
                'stock' => 85,
                'seuil_alerte' => 30,
                'photo_url' => 'images/sirop-ascoril-ls.avif',
                'medicine_group_id' => $diabete->id,

                'composition' => 'Ambroxol + Levosalbutamol + Guaiphenesin',
                'fabricant' => 'Glenmark Pharmaceuticals',
                'type_consommation' => 'Oral',
                'date_expiration' => '2027-08-20',

                'description' => 'Sirop utilisé dans certaines affections respiratoires, selon les indications et la prescription d’un professionnel de santé.'
            ],

            [
                'nom' => 'Azee 500 Comprime',
                'code_medicament' => 'D06ID232435450',
                'stock' => 75,
                'seuil_alerte' => 30,
                'photo_url' => 'images/azee-500-comprime.webp',
                'medicine_group_id' => $generique->id,

                'composition' => 'Azithromycine 500 MG',
                'fabricant' => 'Cipla Ltd',
                'type_consommation' => 'Oral',
                'date_expiration' => '2027-04-10',

                'description' => 'Antibiotique utilisé pour certaines infections bactériennes selon prescription médicale.'
            ],

            [
                'nom' => 'Allegra 120mg Comprime',
                'code_medicament' => 'D06ID232435455',
                'stock' => 44,
                'seuil_alerte' => 30,
                'photo_url' => 'images/allegra-120mg-comprime.jfif',
                'medicine_group_id' => $diabete->id,

                'composition' => 'Fexofénadine 120 MG',
                'fabricant' => 'Sanofi',
                'type_consommation' => 'Oral',
                'date_expiration' => '2027-05-18',

                'description' => 'Médicament antihistaminique utilisé pour soulager certains symptômes allergiques, selon les indications médicales.'
            ],

            [
                'nom' => "Sirop d'Alex",
                'code_medicament' => 'D06ID232435456',
                'stock' => 65,
                'seuil_alerte' => 30,
                'photo_url' => 'images/sirop-d-alex.webp',
                'medicine_group_id' => $generique->id,

                'composition' => 'Formule expectorante',
                'fabricant' => 'Laboratoire pharmaceutique',
                'type_consommation' => 'Oral',
                'date_expiration' => '2027-09-12',

                'description' => 'Sirop destiné à être utilisé selon les indications figurant sur son conditionnement et les conseils d’un professionnel de santé.'
            ],

            [
                'nom' => 'Amoxyclav-625 Comprime',
                'code_medicament' => 'D06ID232435457',
                'stock' => 150,
                'seuil_alerte' => 30,
                'photo_url' => 'images/amoxyclav-625-comprime.avif',
                'medicine_group_id' => $generique->id,

                'composition' => 'Amoxicilline + Acide clavulanique',
                'fabricant' => 'Laboratoire pharmaceutique',
                'type_consommation' => 'Oral',
                'date_expiration' => '2027-07-22',

                'description' => 'Antibiotique utilisé dans le traitement de certaines infections bactériennes, sur prescription médicale.'
            ],

            [
                'nom' => 'Avil-25 Tablette',
                'code_medicament' => 'D06ID232435458',
                'stock' => 270,
                'seuil_alerte' => 30,
                'photo_url' => 'images/avil-25-tablette.png',
                'medicine_group_id' => $generique->id,

                'composition' => 'Pheniramine Maleate 25 MG',
                'fabricant' => 'Sanofi',
                'type_consommation' => 'Oral',
                'date_expiration' => '2027-03-30',

                'description' => 'Médicament antihistaminique utilisé dans certaines manifestations allergiques selon les indications médicales.'
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