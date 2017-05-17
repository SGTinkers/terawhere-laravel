<?php

namespace App\Providers;

use App\Notifications\Channels\FcmChannel;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\ServiceProvider;

/**
 * Class FcmNotificationServiceProvider
 * @package App\Providers
 */
class FcmNotificationServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap the application services.
   *
   * @return void
   */
  public function boot()
  {
  }

  /**
   * Register
   */
  public function register()
  {
    $app = $this->app;
    $this->app->make(ChannelManager::class)->extend('fcm', function() use ($app) {
      return $app->make(FcmChannel::class);
    });
  }
}