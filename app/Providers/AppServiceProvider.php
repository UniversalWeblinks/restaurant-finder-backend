<?php

namespace App\Providers;

use App\Helpers\CustomHelper;
use App\Models\SiteConfig;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

ini_set('memory_limit', -1);
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        try {
            $web = SiteConfig::all();
            $settings = CustomHelper::getSettings($web, 'colors');
            $data = json_decode($settings['value'], true);
            $web_config = [
                'primary_color' => $data['primary'],
                'secondary_color' => $data['secondary'],
                'name' => CustomHelper::getSettings($web, 'company_name'),
                'phone' => CustomHelper::getSettings($web, 'company_phone'),
                'web_logo' => CustomHelper::getSettings($web, 'company_web_logo'),
                'fav_icon' => CustomHelper::getSettings($web, 'company_fav_icon'),
                'hero_banner' => CustomHelper::getSettings($web, 'hero_banner'),
                'hero_text_1' => CustomHelper::getSettings($web, 'hero_text_1'),
                'hero_text_2' => CustomHelper::getSettings($web, 'hero_text_2'),
                'email' => CustomHelper::getSettings($web, 'company_email'),
                'about' => CustomHelper::getSettings($web, 'about_us'),
                'footer_logo' => CustomHelper::getSettings($web, 'company_footer_logo'),
                'copyright_text' => CustomHelper::getSettings($web, 'company_copyright_text'),
            ]; 

            //language
            $language = SiteConfig::where('type', 'language')->first();

            //currency
            \App\Helpers\CustomHelper::currencyLoad();

            View::share(['web_config' => $web_config, 'language' => $language]);

            Schema::defaultStringLength(191);
        } catch (\Exception $ex) {
            // throw exception
        }   
    }
}
