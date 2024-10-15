<?php

namespace PhpJunior\LaravelXml;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class LaravelXmlServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        Arr::macro('toXml', function (array $array, string $rootElement = 'root', array|string $cdataKeys = []) {
            return LaravelXml::transformArrayToXml(
                data: $array,
                rootElement: $rootElement,
                cdataKeys: $cdataKeys
            );
        });

        Arr::macro('fromXml', function (string $xml) {
            return LaravelXml::transformXmlToArray($xml);
        });
    }
}
