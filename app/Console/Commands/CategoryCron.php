<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Wrappers\ContractApiWrapper;
use Illuminate\Console\Command;

class CategoryCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'category:cron';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Category Command Executed Successfully!';
    //Description will be shown when the php artisan list command is executed.

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
     * @return mixed
     */
    public function handle()
    {
        $categories = (new ContractApiWrapper)->get_categories();
        $updatedIds = collect([]);
        foreach ($categories as $category) {
            $updatedIds->push($category->id);
            $matchThese = ['id' => $category->id];
            Category::updateOrCreate($matchThese, ['name' => $category->name, 'id' => $category->id]);
        }

        //Delete from the database elements that are not longer returned from the API
        Category::whereNotIn('id', $updatedIds)->delete();


        $this->info('Category:Cron Command is working fine!');
    }
}
