<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mpociot\BotMan\BotMan;
use Mpociot\BotMan\Middleware\Wit;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');
        $botman->verifyServices(env('FB_MESSENGER_TOKEN'));

        // Wit.ai
        $botman->middleware(Wit::create('WIT_AI_TOKEN'));
        $botman->hears('emotion', function($bot) {
            $extras = $bot->getMessage()->getExtras();
            // Access extra information
            $entities = $extras['entities'];
        });
    }

}