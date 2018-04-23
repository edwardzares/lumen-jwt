<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot() {
        Validator::extend("emails", function($attribute, $value, $parameters) {
            $rules = [
                'email' => 'required|email',
            ];
            foreach ($value as $email) {
                $data = [
                    'email' => ((empty($parameters))?$email:$email[$parameters[0]])
                ];
                $validator = Validator::make($data, $rules);
                if ($validator->fails()) {
                    return false;
                }
            }
            return true;
        });
        Validator::extend("phones", function($attribute, $value, $parameters) {
            $rules = [
                'phone' => 'required|numeric',
            ];
            foreach ($value as $phone) {
                $data = [
                    'phone' => $phone
                ];
                $validator = Validator::make($data, $rules);
                if ($validator->fails()) {
                    return false;
                }
            }
            return true;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
