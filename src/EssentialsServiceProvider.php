<?php

namespace Webbundels\Essentials;

use Illuminate\Support\ServiceProvider;

class EssentialsServiceProvider extends ServiceProvider
{
  public function register()
  {
    //
  }

  public function boot()
  {
      $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
      $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
      $this->loadViewsFrom(__DIR__.'/../resources/views', 'EssentialsPackage');
  }
}