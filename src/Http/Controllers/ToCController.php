<?php

namespace Plugin\ToC\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Setting\Supports\SettingStore;
use Plugin\ToC\Http\Requests\ToCSettingsRequest;
use Plugin\ToC\Plugin;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

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
        $data = $request->validated();
        $this->settingStore->set('plugin_toc_settings', json_encode($data));

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
