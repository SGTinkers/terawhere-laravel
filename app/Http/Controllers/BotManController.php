<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mpociot\BotMan\BotMan;
class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');
        $botman->verifyServices(env('FB_PAGE_ACCESS_TOKEN'));
        // Simple respond method
        $botman->middleware(Wit::create('WIT_AI_TOKEN'));
        $botman->hears('test_action', function (BotMan $bot) {
            $bot->reply('Hi there :)');
        });
        $botman->listen();
    }
}