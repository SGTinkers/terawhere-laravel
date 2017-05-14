<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Offer;

class DeleteOldOffers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DeleteOldOffers:deleteoffers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Offers where meetup_time is older than now.';

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
        $now = Carbon::now();
        Offer::where('meetup_time', '<=', $now)->delete();
    }
}
