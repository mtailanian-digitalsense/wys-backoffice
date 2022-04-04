<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Country;
use App\Models\Region;
use App\Models\Subcategory;
use App\Models\Zone;
use App\Wrappers\ContractApiWrapper;
use Illuminate\Console\Command;

class ZonesCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zones:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all zones from the API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $zones = (new ContractApiWrapper)->get_zones();
        $updatedIds = collect([]);
        foreach ($zones as $zone) {
            $updatedIds->push($zone->id);
            $country = Country::updateOrCreate(['name' => ucfirst($zone->country)],
                [
                    'name' => ucfirst($zone->country),
                    'short_code' => strtolower(substr($zone->country, 0, 2)),
                ]);


            $region = Region::updateOrCreate(['name' => ucfirst($zone->region)],
                [
                    'name' => ucfirst($zone->region),
                    'country_id' => $country->id,
                    'short_code' => strtolower(substr($zone->region, 0, 3)),
                ]);

            $matchThese = ['id' => $zone->id];

            Zone::updateOrCreate($matchThese, [
                'id' => $zone->id,
                'active' => $zone->active,
                'name' => $zone->name,
                'region_id' => $region->id,
                'mun_agility' => $zone->mun_agility]);
        }


        //Delete from the database elements that are not longer returned from the API
        Zone::whereNotIn('id', $updatedIds)->delete();
        $this->info('Zones:Cron Command is working fine!');
    }
}
