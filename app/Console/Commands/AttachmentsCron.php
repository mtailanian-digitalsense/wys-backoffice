<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Wrappers\ContractApiWrapper;
use Illuminate\Console\Command;
use Orchid\Attachment\Models\Attachment;

class AttachmentsCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attachments:cron';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attachment Command Executed Successfully!';
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
        $attachments = Attachment::all();
        foreach ($attachments as $attachment) {
            $attachment->delete();
        }
        $this->info('Category:Cron Command is working fine!');
    }
}
