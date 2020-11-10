<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use \RobotKudos\RKDB\Options;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $options = new Options();
        $options->set('homepage_headline1', 'Create a Property Website', 'homepage_options', 'Homepage Options');
        $options->set('homepage_headline2', 'In Less than 20 Minutes', 'homepage_options', 'Homepage Options');
        $options->set('homepage_cta_button', 'Create a Property Website', 'homepage_options', 'Homepage Options');
        $options->set('homepage_text', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy', 'homepage_options', 'Homepage Options');

        $options->set('homepage_box2_icon', 'fa-smile', 'homepage_options', 'Homepage Options');
        $options->set('homepage_box2_title', 'Fast and Easy', 'homepage_options', 'Homepage Options');
        $options->set('homepage_box2_text', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit commodi veniam expedita minus facere voluptatem dignissimos sit ab aperiam maxime, porro voluptatibus omnis nostrum laudantium nisi quae recusandae, vero illum.', 'homepage_options', 'Homepage Options');

        $options->set('homepage_box3_icon', 'fa-smile', 'homepage_options', 'Homepage Options');
        $options->set('homepage_box3_title', 'Fast and Easy', 'homepage_options', 'Homepage Options');
        $options->set('homepage_box3_text', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit commodi veniam expedita minus facere voluptatem dignissimos sit ab aperiam maxime, porro voluptatibus omnis nostrum laudantium nisi quae recusandae, vero illum.', 'homepage_options', 'Homepage Options');

        $options->set('homepage_box4_icon', 'fa-smile', 'homepage_options', 'Homepage Options');
        $options->set('homepage_box4_title', 'Fast and Easy', 'homepage_options', 'Homepage Options');
        $options->set('homepage_box4_text', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit commodi veniam expedita minus facere voluptatem dignissimos sit ab aperiam maxime, porro voluptatibus omnis nostrum laudantium nisi quae recusandae, vero illum.', 'homepage_options', 'Homepage Options');

        $options->set('listing_price', '19.99', 'pricing', 'Pricing');

        DB::table('property_types')->insert([
            [ 'property_type' => 'Farm/Ranch' ],
            [ 'property_type' => 'Multi-Family Home' ],
            [ 'property_type' => 'Single-Family Home' ],
            [ 'property_type' => 'Income/Investment' ],
            [ 'property_type' => 'Condo' ],
            [ 'property_type' => 'Lot/Land' ],
            [ 'property_type' => 'Townhome' ],
            [ 'property_type' => 'Mobile Home' ],
            [ 'property_type' => 'Loft' ]
        ]);

        DB::table('listing_statuses')->insert([
            [ 'listing_status' => 'Active' ],
            [ 'listing_status' => 'Pending' ],
            [ 'listing_status' => 'Sold' ],
        ]);

        DB::table('users')->insert([
            'email' => 'nhejazi@gmail.com',
            'password' => Hash::make('asdf1234'),
            'api_token' => Str::random(60),
            'stripe_customer_id' => 'cus_IFImmNb5SZ8CWQ'
        ]);

        DB::table('payment_statuses')->insert([
            ['name' => 'paid'],
            ['name' => 'pending'],
        ]);
    }
}
