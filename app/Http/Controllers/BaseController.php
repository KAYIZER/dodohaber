<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Setting;

/**
 * Tüm frontend controller'lar için ortak view-sharing mantığını barındırır.
 * FrontendController ve PharmacyController buradan extend eder.
 */
class BaseController extends Controller
{
    public function __construct()
    {
        $themeSettings = Setting::getGroup('theme');
        $generalSettings = Setting::getGroup('general');
        $seoSettings = Setting::getGroup('seo');

        view()->share('theme', $themeSettings);
        view()->share('site', $generalSettings);
        view()->share('seo', $seoSettings);

        $headerCategories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->with('children')
            ->orderBy('order')
            ->get();

        view()->share('headerCategories', $headerCategories);
    }
}
