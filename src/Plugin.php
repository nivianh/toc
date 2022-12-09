<?php

namespace Plugin\ToC;

use Botble\Base\Models\MetaBox;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\Setting\Models\Setting;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Setting::whereIn('key', ['plugin_toc_settings'])->delete();
        MetaBox::whereIn('meta_key', ['show_toc_in_content'])->delete();
    }
}
