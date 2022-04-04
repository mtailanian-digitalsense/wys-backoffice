<?php

namespace App\Console\Commands;

use App\Models\Building;
use App\Models\Category;
use App\Models\Country;
use App\Models\Floor;
use App\Models\Region;
use App\Models\Subcategory;
use App\Models\Zone;
use App\Wrappers\ContractApiWrapper;
use Illuminate\Console\Command;

class BuildingsCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'buildings:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all buildings from the API';

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
        $api = new ContractApiWrapper;
        $zones = $api->get_zones();
        $updatedIds = collect([]);
        foreach ($zones as $zone) {
            $buildings = $api->get_buildings_by_zone($zone->id);
            foreach ($buildings as $building) {
                $updatedIds->push($building->id);
                $matchThese = ['id' => $building->id];
                Building::updateOrCreate($matchThese, [
                        'id' => $building->id,
                        'active' => $building->active,
                        'address_number' => $building->address_number,
                        'building_year' => $building->building_year,
                        'category' => $building->category,
                        'gps_location' => $building->gps_location,
                        'infrastructure_lvl' => $building->infrastructure_lvl,
                        'name' => $building->name,
                        'parking_lvl' => $building->parking_lvl,
                        'parking_number' => $building->parking_number,
                        'public_transport_lvl' => $building->public_transport_lvl,
                        'security_lvl' => $building->security_lvl,
                        'services_lvl' => $building->services_lvl,
                        'street' => $building->street,
                        'sustainability_lvl' => $building->sustainability_lvl,
                        'total_floors' => $building->total_floors,
                        'view_lvl' => $building->view_lvl,
                        'zone_id' => $building->zone_id,
                        'planta_tipo' => $building->planta_tipo,
                        'adm_agility' => $building->adm_agility,
                    ]
                );
                $this->addFloors($building->floors, $building->id);
            }
        }

        //Delete from the database elements that are not longer returned from the API
        Building::whereNotIn('id', $updatedIds)->delete();
        $this->info('Buildings:Cron Command is working fine!');
    }

    /**
     * Add floors to database
     *
     * @param $floors
     * @param $buildingId
     */
    private function addFloors($floors, $buildingId)
    {
        foreach ($floors as $floor) {
            if ($floor->active) {
                $matchThese = ['id' => $floor->id];
                Floor::updateOrCreate($matchThese, [
                        "id" => $floor->id,
                        "elevators_number" => $floor->elevators_number,
                        "image_link" => $floor->image_link,
                        "m2" => $floor->m2,
                        "rent_value" => $floor->rent_value,
                        "wys_id" => $floor->wys_id,
                        "building_id" => $buildingId,
                    ]
                );
            }
        }
    }
}
