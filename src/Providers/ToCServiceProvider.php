<?php

namespace Plugin\ToC\Providers;

use Botble\Base\Traits\LoadAndPublishDataTrait;
use Plugin\ToC\Facades\ToCHelper;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

class ToCServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        AliasLoader::getInstance()->alias('ToCHelper', ToCHelper::class);
    }

    public function boot()
    {
        $this
            ->setNamespace('plugins/toc')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['general'])
            ->loadAndPublishTranslations()
            ->publishAssets()
            ->loadRoutes()
            ->loadAndPublishViews();

        $this->app->register(HookServiceProvider::class);

        $this->app['events']->listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id' => 'cms-plugins-toc-settings',
                    'priority' => 99,
                    'parent_id' => 'cms-core-settings',
                    'name' => 'plugins/toc::toc.settings.menu',
                    'icon' => null,
                    'url' => route('plugins.toc.settings'),
                    'permissions' => ['setting.options'],
                ]);
        });
    }
}
