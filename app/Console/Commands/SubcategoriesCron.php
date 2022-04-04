<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Subcategory;
use App\Wrappers\ContractApiWrapper;
use Illuminate\Console\Command;

class SubcategoriesCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subcategories:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all subcategories from the API';

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
        $subcategories = (new ContractApiWrapper)->get_subcategories();
        $updatedIds = collect([]);
        foreach ($subcategories as $subcategory) {
            $updatedIds->push($subcategory->id);
            $matchThese = ['id' => $subcategory->id];
            Subcategory::updateOrCreate($matchThese, [
                'id' => $subcategory->id,
                'name' => $subcategory->name,
                'category_id' => $subcategory->category_id]);
        }

        //Delete from the database elements that are not longer returned from the API
        Subcategory::whereNotIn('id', $updatedIds)->delete();
        $this->info('Subcategory:Cron Command is working fine!');
    }
}
