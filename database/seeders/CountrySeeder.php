<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['code' => 'US', 'name' => ['en' => 'United States', 'ka' => 'ამერიკის შეერთებული შტატები']],
            ['code' => 'GB', 'name' => ['en' => 'United Kingdom', 'ka' => 'გაერთიანებული სამეფო']],
            ['code' => 'DE', 'name' => ['en' => 'Germany', 'ka' => 'გერმანია']],
            ['code' => 'FR', 'name' => ['en' => 'France', 'ka' => 'ფრანგეაია']],
            ['code' => 'ES', 'name' => ['en' => 'Spain', 'ka' => 'ესპანეთი']],
            ['code' => 'IT', 'name' => ['en' => 'Italy', 'ka' => 'იტალია']],
            ['code' => 'PT', 'name' => ['en' => 'Portugal', 'ka' => 'პორტუგალია']],
            ['code' => 'BR', 'name' => ['en' => 'Brazil', 'ka' => 'ბრაზილია']],
            ['code' => 'AR', 'name' => ['en' => 'Argentina', 'ka' => 'არგენტინა']],
            ['code' => 'GE', 'name' => ['en' => 'Georgia', 'ka' => 'საქართველო']],
            ['code' => 'RU', 'name' => ['en' => 'Russia', 'ka' => 'რუსეთი']],
            ['code' => 'UA', 'name' => ['en' => 'Ukraine', 'ka' => 'უკრაინა']],
            ['code' => 'NL', 'name' => ['en' => 'Netherlands', 'ka' => 'ჰოლანდია']],
            ['code' => 'BE', 'name' => ['en' => 'Belgium', 'ka' => 'ბელგია']],
            ['code' => 'PL', 'name' => ['en' => 'Poland', 'ka' => 'პოლონეთი']],
        ];

        foreach ($data as $country) {
            Country::create($country);
        }
    }
}
