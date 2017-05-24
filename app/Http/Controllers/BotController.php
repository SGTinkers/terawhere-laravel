<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mpociot\BotMan\BotMan;
use Mpociot\BotMan\Middleware\Wit;

class BotController extends Controller
{
    public function handle()
    {
        $botman = app('botman');
        $botman->verifyServices(env('FB_PAGE_ACCESS_TOKEN'));
        // Simple respond method
        $botman->middleware(Wit::create('WIT_AI_TOKEN'));
        $botman->hears('test_action', function (BotMan $bot) {
            $bot->types(); //typing
            $bot->reply('Hi there :)');
        });
        $botman->listen();
    }
    public function fbWebhook(Request $request){
        $local_verify_token = env('FB_WEBHOOK_VERIFY_TOKEN');
        $hub_verify_token = $request->get('hub_verify_token');
        if($local_verify_token ==  $hub_verify_token){
            return $request->get('hub_challenge');
        }
        else return "Bad token";
    }
}