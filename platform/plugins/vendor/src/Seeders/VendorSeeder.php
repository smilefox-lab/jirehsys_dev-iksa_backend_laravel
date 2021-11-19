<?php

namespace Botble\Vendor\Seeders;

use Botble\RealEstate\Models\Property;
use Botble\Vendor\Models\Vendor;
use Faker\Factory;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();

        Vendor::truncate();

        Vendor::create([
            'first_name'   => $faker->firstName,
            'last_name'    => $faker->lastName,
            'email'        => 'john.smith@botble.com',
            'password'     => bcrypt('12345678'),
            'dob'          => $faker->dateTime,
            'phone'        => $faker->phoneNumber,
            'description'  => $faker->realText(30),
            'confirmed_at' => now(),
        ]);

        for ($i = 0; $i < 10; $i++) {
            Vendor::create([
                'first_name'   => $faker->firstName,
                'last_name'    => $faker->lastName,
                'email'        => $faker->email,
                'password'     => bcrypt($faker->password),
                'dob'          => $faker->dateTime,
                'phone'        => $faker->phoneNumber,
                'description'  => $faker->realText(30),
                'confirmed_at' => now(),
            ]);
        }

        $properties = Property::get();

        foreach ($properties as $property) {
            $property->author_id = Vendor::inRandomOrder()->value('id');
            $property->author_type = Vendor::class;
            $property->save();
        }

        $this->call(PackageSeeder::class);
    }
}
