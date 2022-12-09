<?php

namespace Plugin\ToC\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Setting\Supports\SettingStore;
use Plugin\ToC\Http\Requests\ToCSettingsRequest;
use Plugin\ToC\Plugin;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;

class ToCController extends BaseController
{
    protected SettingStore $settingStore;

    public function __construct(SettingStore $settingStore)
    {
        $this->settingStore = $settingStore;
    }

    public function settings(): Factory|View
    {
        page_title()->setTitle(trans('plugins/toc::toc.settings.title'));

        return view('plugins/toc::settings');
    }

    public function postSettings(ToCSettingsRequest $request, BaseHttpResponse $response): BaseHttpResponse
    {
        $validated = $request->validated();
        foreach ($validated as $key => $value) {
            if (config('plugins.toc.general.' . $key) == $value) {
                Arr::forget($validated, $key);
            }
        }

        $this->settingStore->set('plugin_toc_settings', $validated ? json_encode($validated) : '');

        $this->settingStore->save();

        return $response
            ->setPreviousUrl(route('plugins.toc.settings'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function restoreFactory(BaseHttpResponse $response): BaseHttpResponse
    {
        Plugin::remove();

        return $response
            ->setPreviousUrl(route('plugins.toc.settings'))
            ->setMessage(trans('plugins/toc::toc.settings.restore_factory.successfully'));
    }
}
