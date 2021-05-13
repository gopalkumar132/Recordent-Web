<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use TCG\Voyager\Facades\Voyager;
use Validator;
use Carbon\carbon;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
    * Register any application services.
    *
    * @return void
    */
    public function register()
    {
        //Voyager::useModel('Menu2', \App\Menu2::class);
        Collection::macro('customPaginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
                $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
                return new LengthAwarePaginator(
                    $this->forPage($page, $perPage)->values(),
                    $total ?: $this->count(),
                    $perPage,
                    $page,
                    [
                        'path' => LengthAwarePaginator::resolveCurrentPath(),
                        'pageName' => $pageName,
                    ]
                );
            });
    }

    /**
    * Bootstrap any application services.
    *
    * @return void
    */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Validator::extend('date_multi_format', function($attribute, $value, $formats, $validator) {
            foreach($formats as $format) {
                try{
                    $parsed = Carbon::createFromFormat($format,$value);
                    return true;
                }catch(\Exception $e){}
            }
            return false;
        });


        Validator::extend('custom_before_date_or_equal', function($attribute, $value, $ruleDates, $validator) {
            $value = str_replace("-","/",$value);
            if(empty($value)){
                return false;
            }
            foreach($ruleDates as $date) {
                try{
                    $value = Carbon::createFromFormat('d/m/Y',$value);
                    $date = Carbon::createFromFormat('d/m/Y',$date);
                    if($value <= $date){
                        return true;
                    }
                }catch(\Exception $e){}
            }
            return false;
        });


        Validator::extend('custom_date_after_or_equal', function($attribute, $value, $ruleDates, $validator) {
            $value = str_replace("-","/",$value);
            if(empty($value)){
                return false;
            }
            foreach($ruleDates as $date) {
                try{
                    $value = Carbon::createFromFormat('d/m/Y',$value);
                    $date = Carbon::createFromFormat('d/m/Y',$date);
                    if($value >= $date){
                        return true;
                    }
                }catch(\Exception $e){}
            }
            return false;
        });
        view()->composer('*',function($view) {
            $user_pricing_plan=Auth::guest()?array():Auth::user()->user_pricing_plan;
            $view->with('user_pricing_plan', $user_pricing_plan);
        });

    }
}
