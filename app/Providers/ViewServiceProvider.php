<?php

  namespace App\Providers;

  use Illuminate\Support\Facades\Blade;
  use Illuminate\Support\ServiceProvider;

  class ViewServiceProvider extends ServiceProvider
  {
    public function register(): void
    {
      Blade::directive('comment', function ($expression) {
          return '';
      });
    }

    public function boot(): void
    {
    }
  }
