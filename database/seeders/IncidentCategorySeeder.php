<?php

namespace Database\Seeders;

use App\Models\IncidentCategory;
use Illuminate\Database\Seeder;

class IncidentCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['slug' => 'vehicular-accident', 'en' => 'Vehicular accident', 'fil' => 'Aksidente sa sasakyan', 'hil' => 'Aksidente sa salakyan', 'krb' => 'Aksidente sa salakyan', 'sort' => 10],
            ['slug' => 'fire-incident', 'en' => 'Fire incident', 'fil' => 'Sunog', 'hil' => 'Sunog', 'krb' => 'Sunog', 'sort' => 20],
            ['slug' => 'flooding-water', 'en' => 'Flooding or water-related', 'fil' => 'Baha o kaugnay sa tubig', 'hil' => 'Baha ukon may kaangtanan sa tubig', 'krb' => 'Baha ukon may kaangtanan sa tubig', 'sort' => 30],
            ['slug' => 'landslide-natural-hazard', 'en' => 'Landslide or natural hazard', 'fil' => 'Pagguho o likas na panganib', 'hil' => 'Pagdahili sang duta ukon kalamidad', 'krb' => 'Pagdahili it duta ukon kalamidad', 'sort' => 40],
            ['slug' => 'medical-emergency', 'en' => 'Medical emergency', 'fil' => 'Medikal na emerhensiya', 'hil' => 'Emerhensya medikal', 'krb' => 'Emerhensya medikal', 'sort' => 50],
            ['slug' => 'rescue-missing-person', 'en' => 'Rescue or missing person', 'fil' => 'Pagsagip o nawawalang tao', 'hil' => 'Pagluwas ukon tawo nga nadula', 'krb' => 'Pagluwas ukon tawo nga nadula', 'sort' => 60],
            ['slug' => 'structural-collapse', 'en' => 'Structural collapse or accident', 'fil' => 'Pagbagsak ng istruktura', 'hil' => 'Pagkaguba sang estruktura ukon aksidente', 'krb' => 'Pagkaguba it estruktura ukon aksidente', 'sort' => 70],
            ['slug' => 'other-emergency', 'en' => 'Other emergency', 'fil' => 'Iba pang emerhensiya', 'hil' => 'Iban nga emerhensya', 'krb' => 'Iban pa nga emerhensya', 'sort' => 99],
        ];

        foreach ($categories as $cat) {
            IncidentCategory::query()->updateOrCreate(
                ['slug' => $cat['slug']],
                [
                    'name_translations' => [
                        'en'  => $cat['en'],
                        'fil' => $cat['fil'],
                        'hil' => $cat['hil'],
                        'krb' => $cat['krb'],
                    ],
                    'active'     => true,
                    'sort_order' => $cat['sort'],
                ]
            );
        }
    }
}
