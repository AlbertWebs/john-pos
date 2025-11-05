<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // Kenyan first names (common in Kenya)
        $firstNames = ['James', 'John', 'Peter', 'Joseph', 'David', 'Michael', 'Paul', 'Simon', 'Daniel', 'Stephen', 
                      'Mary', 'Grace', 'Faith', 'Sarah', 'Joyce', 'Ruth', 'Esther', 'Mercy', 'Jane', 'Ann', 
                      'Catherine', 'Lucy', 'Rose', 'Hannah', 'Patience', 'Elizabeth', 'Susan', 'Naomi', 'Rebecca', 'Margaret'];
        
        // Kenyan last names (common in Kenya) - unique names from various Kenyan tribes
        $lastNames = [
            // Kikuyu names
            'Mwangi', 'Kariuki', 'Kamau', 'Njoroge', 'Maina', 'Wanjiru', 'Wanjala', 'Wanjiku', 'Wambui', 'Waweru',
            // Luo names
            'Ochieng', 'Onyango', 'Omondi', 'Owuor', 'Otieno', 'Okoth', 'Opiyo', 'Onyango', 'Ochieng', 'Omondi',
            // Luhya names
            'Wanyama', 'Wanyonyi', 'Wanjala', 'Wanyonyi', 'Wanjala', 'Wanyonyi', 'Wanjala', 'Wanyonyi', 'Wanjala', 'Wanyonyi',
            // Kalenjin names
            'Kipchoge', 'Chebet', 'Kiprotich', 'Cheruiyot', 'Kipchoge', 'Chebet', 'Kiprotich', 'Cheruiyot', 'Kipchoge', 'Chebet',
            // Kamba names
            'Muthoka', 'Mutua', 'Muthoka', 'Mutua', 'Muthoka', 'Mutua', 'Muthoka', 'Mutua', 'Muthoka', 'Mutua',
            // Meru names
            'Mwirigi', 'Muthomi', 'Mwirigi', 'Muthomi', 'Mwirigi', 'Muthomi', 'Mwirigi', 'Muthomi', 'Mwirigi', 'Muthomi',
        ];

        // Kenyan cities and areas
        $areas = [
            'Nairobi' => ['Westlands', 'Parklands', 'Kilimani', 'Lavington', 'Karen', 'Kasarani', 'Eastleigh', 'Kibera', 'Kawangware', 'Mathare'],
            'Mombasa' => ['Nyali', 'Bamburi', 'Mtwapa', 'Likoni', 'Kisauni', 'Mikindani', 'Changamwe', 'Mombasa CBD'],
            'Kisumu' => ['Milimani', 'Nyalenda', 'Manyatta', 'Kondele', 'Kisumu CBD'],
            'Nakuru' => ['Milimani', 'Lanet', 'Shabab', 'Nakuru CBD'],
            'Eldoret' => ['Milimani', 'Kapsoya', 'Kipkaren', 'Eldoret CBD'],
            'Thika' => ['Makongeni', 'Thika CBD', 'Gatuanyaga'],
            'Machakos' => ['Machakos CBD', 'Katoloni', 'Mavoko'],
        ];

        for ($i = 0; $i < 35; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $name = $firstName . ' ' . $lastName;
            
            // Generate Kenyan phone number (254XXXXXXXXX) - Kenyan mobile numbers start with 7
            $phone = '2547' . rand(10000000, 99999999);
            
            $email = strtolower($firstName . '.' . $lastName . '@gmail.com');

            // Get random city and area
            $city = array_rand($areas);
            $area = $areas[$city][array_rand($areas[$city])];
            $street = rand(1, 500);
            $address = $street . ' ' . $area . ', ' . $city . ', Kenya';

            Customer::create([
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'address' => $address,
                'loyalty_points' => rand(0, 5000),
            ]);
        }
    }
}
