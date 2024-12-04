<?php

namespace Vendor\kirhtarg\Ktranslate;

use Illuminate\Support\ServiceProvider;

class LocalizationSupportServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Автозагрузка миграций
        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');

        // Автозагрузка языковых файлов
        $this->loadTranslationsFrom(__DIR__ . '/Resources/Lang', 'Ktranslate');

        $this->publishes([
            __DIR__ . '/Config/ktranslate.php' => config_path('ktranslate.php'),
        ], 'config');
    }

}
