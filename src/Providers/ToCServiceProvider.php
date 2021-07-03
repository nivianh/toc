<?php

namespace Botble\ToC\Providers;

use Illuminate\Support\ServiceProvider;
use Botble\Base\Supports\Helper;
use Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Foundation\AliasLoader;
use Botble\ToC\Facades\ToCHelperFacade;

class ToCServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        Helper::autoload(__DIR__ . '/../../helpers');

        AliasLoader::getInstance()->alias('ToCHelper', ToCHelperFacade::class);
    }

    public function boot()
    {
        $this->setNamespace('plugins/toc')
            ->loadAndPublishConfigurations(['general'])
            ->loadAndPublishTranslations()
            ->loadAndPublishViews();

        $this->app->register(HookServiceProvider::class);
    }
}
