<?php

use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionTableSeeder extends Seeder
{
    public function run()
    {

        //DB::table('regions')->delete();

        $json = File::get("database/data/countries.json");
        $data = json_decode($json);

        foreach ($data as $key => $value) {
            $country = DB::table('countries')->where('name', $key)->first();
            if ($country != null) {
                foreach ($value as $city) {
                    Region::create(array(
                        'country_id' => $country->id,
                        'short_code' => $country->short_code,
                        'name' => $city,
                    ));
                }
            }
        }
    }
}
