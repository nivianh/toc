<?php

namespace Botble\ToC\Providers;

use Botble\Base\Models\BaseModel;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;
use Theme;
use Botble\ToC\Services\ToCService;
use Botble\Blog\Models\Post;
use ToCHelper;

class HookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        add_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, [$this, 'addToCContent'], 56, 2);
    }

    /**
     * @param string $screen
     * @param BaseModel $object
     */
    public function addToCContent($screen, $object)
    {
        if ($object && ToCHelper::isSupportedModel(get_class($object))) {
            Theme::asset()
                ->usePath(false)
                ->add('toc-css', 'vendor/core/plugins/toc/css/toc.css');

            Theme::asset()
                ->container('footer')
                ->usePath(false)
                ->add('toc-js', 'vendor/core/plugins/toc/js/toc.js', ['jquery']);

            $object->content = ToCHelper::theContent($object->content);
        }
    }
}
