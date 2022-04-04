<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Space;
use App\Wrappers\ContractApiWrapper;
use Illuminate\Console\Command;

class SpacesCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spaces:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all spaces from API';

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
        $spaces = (new ContractApiWrapper)->get_spaces();
        $updatedIds = collect([]);
        foreach ($spaces as $space) {
            $updatedIds->push($space->id);
            $matchThese = ['id' => $space->id];
            Space::updateOrCreate($matchThese, [
                    'subcategory_id' => $space->subcategory_id,
                    'name' => $space->name,
                    'active' => $space->active,
                    'id' => $space->id,
                    'down_gap' => $space->down_gap,
                    'height' => $space->height,
                    'left_gap' => $space->left_gap,
                    'model_2d' => $space->model_2d,
                    'model_3d' => $space->model_3d,
                    'regular' => $space->regular,
                    'right_gap' => $space->right_gap,
                    'up_gap' => $space->up_gap,
                    'width' => $space->width,
                ]
            );
        }

        //Delete from the database elements that are not longer returned from the API
        Space::whereNotIn('id', $updatedIds)->delete();
        $this->info('Space:Cron Command is working fine!');
    }
}
