<?php

namespace Botble\ToC\Providers;

use Botble\Base\Supports\Helper;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\ToC\Facades\ToCHelperFacade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

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
            ->publishAssets()
            ->loadAndPublishViews();

        $this->app->register(HookServiceProvider::class);
    }
}
