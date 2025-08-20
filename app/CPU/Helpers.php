<?php

namespace App\CPU;

use App\Models\ActivityLog;
use Exception;
use App\Models\Admin;
use App\Models\Brand;
use App\Models\Country;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Helpers
{

    /**
     * Log batch activity.
     *
     * @param string $subject
     * @param string $status
     * @param string $errorMessage
     * @return void
     */
    public static function logBatchActivity(string $subject, string $status, string $errorMessage = '',$id=null)
    {
        switch ($status) {
            case 'start':
                self::activity(
                    null,
                    null,
                    null,
                    'system',
                    'Batch creation started for sending emails with subject <strong>' . e($subject) . '</strong>.',
                    'default'
                );
                break;

            case 'progress':
                self::activity(
                    null,
                    null,
                    null,
                    'system',
                    'Progress updated for emails with subject <strong>' . e($subject) . '</strong>.',
                    'default'
                );
                break;

            case 'success':
                self::activity(
                    null,
                    null,
                    null,
                    'system',
                    'Batch emails sent successfully to all active users with subject <strong>' . e($subject) . '</strong>.',
                    'default'
                );
                break;

            case 'failure':
                self::activity(
                    null,
                    null,
                    $id,
                    'system',
                    'Batch email sending failed with subject <strong>' . e($subject) . '</strong>. Error: ' . e($errorMessage),
                    'default'
                );
                break;

            case 'complete':
                self::activity(
                    null,
                    null,
                    null,
                    'system',
                    'Batch email process completed for subject <strong>' . e($subject) . '</strong>.',
                    'default'
                );
                break;
        }
    }


    public static function percentage($total, $value)
    {
        return ($total > 0) ? round(($value / $total) * 100) : 0;
    }

    public static function logout($guard)
    {
        try {

            Auth::guard($guard)->logout();
            return 'Logged Out';

        } catch (Exception $e) {

            return $e->getMessage();
        }
    }


    public function profile($guard)
    {
        return Auth::guard($guard)->user();
    }

    public static function tounderscore($text) {
        $text = str_replace(' ', '_', $text);
        $text = str_replace(' ', '_', $text);
        return $text;
    }

    public static function toSpan($data) {
        $per = explode('.', $data);

        $data = $per[0];
        if(isset($per[1])) {
            $data .= ' - '. $per[1];
        }

        return Helpers::toWord($data);
    }
    public static function extractIconClassName($icon) {
        preg_match('/class="([^"]+)"/', $icon, $matches);
        return isset($matches[1]) ? $matches[1] : null;
    }

    public static function activity($user,$admin,$activity_id,$type,$msg,$action)
    {
        ActivityLog::create([
            'user_id' => $user??null,
            'admin_id' => $admin??null,
            'activity_id' => $activity_id??null,
            'activity_type' => $type??'default',
            'activity' => $msg,
            'action' => $action??'default',
        ]);
        return 0;
    }

    public static function split_name($name) {
        $data = [];
        foreach ($name as $value) {
            $per = explode('.', $value->name);
            $data[Helpers::toWord($per[0])][] = $value->name;
        }
        return $data;
    }

    public static function toWord($word) {
        $word = str_replace('_', ' ', $word);
        $word = str_replace('-', ' ', $word);
        $word = ucwords($word);

        return $word;
    }

    public static function icon($class){
        return '<i class="'.$class.'"></i>';
    }
    public static function adminName($id){
        return Admin::find($id)->name;
    }
    public static function categoryParent($id){
       return Category::find($id)->name;
    }

    public static function productAverageRating($productId)
    {
        return 1;
    }

    public function SourceLink($type, $id) {
        $slug = '';

        if ($type == 'product') {
            $slug = Product::find($id)->slug ?? '';
            return '#';
        } elseif ($type == 'category') {
            $slug = Category::find($id)->slug ?? '';
            return '#';

        } elseif ($type == 'brand') {
            $slug = Brand::find($id)->slug ?? '';
            return '#';

        }

        return '#';
    }


    // public static function make_online()
    // {
    //     $active = Auth::guard('api_users')->user();
    //     $active->last_seen = Carbon::now();
    //     $active->save();
    // }

    // public static function getPaginateSetting()
    // {
    //     if (session()->has('paginate')) {
    //         return session('paginate');
    //     } else {
    //         $setting = AppSetting::where('key', 'paginate')->first();
    //         if ($setting) {
    //             session(['paginate' => $setting->value]);
    //             return $setting->value;
    //         }
    //         return null; // or a default value
    //     }
    // }

    // public static function getAppLogoSetting()
    // {
    //     if (session()->has('app_logo')) {
    //         return asset('storage/images/settings/' . session('app_logo'));
    //     } else {
    //         $setting = AppSetting::where('key', 'app_logo')->first();
    //         if ($setting) {
    //             session(['app_logo' => $setting->value]);
    //             return asset('storage/images/settings/' . $setting->value);
    //         }
    //         return null; // or a default image path
    //     }
    // }


    // public static function getEnvCredentials()
    // {
    //     $envCredentials = Session::get('env_credentials');

    //     if (!$envCredentials) {
    //         // Fetch credentials from the database
    //         $envCredentials = AppSetting::whereIn('key', [
    //             'MAIL_DRIVER',
    //             'MAIL_HOST',
    //             'MAIL_PORT',
    //             'MAIL_USERNAME',
    //             'MAIL_PASSWORD',
    //             'MAIL_ENCRYPTION',
    //             'PUSHER_APP_ID',
    //             'PUSHER_APP_KEY',
    //             'PUSHER_APP_SECRET',
    //             'PUSHER_APP_CLUSTER',
    //         ])->pluck('value', 'key')->toArray();

    //         if (empty($envCredentials)) {
    //             // If credentials are still empty, fetch from environment variables
    //             $envCredentials = [
    //                 'MAIL_DRIVER' => env('MAIL_DRIVER'),
    //                 'MAIL_HOST' => env('MAIL_HOST'),
    //                 'MAIL_PORT' => env('MAIL_PORT'),
    //                 'MAIL_USERNAME' => env('MAIL_USERNAME'),
    //                 'MAIL_PASSWORD' => env('MAIL_PASSWORD'),
    //                 'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION'),
    //                 'PUSHER_APP_ID' => env('PUSHER_APP_ID'),
    //                 'PUSHER_APP_KEY' => env('PUSHER_APP_KEY'),
    //                 'PUSHER_APP_SECRET' => env('PUSHER_APP_SECRET'),
    //                 'PUSHER_APP_CLUSTER' => env('PUSHER_APP_CLUSTER'),
    //             ];
    //         }

    //         // Set credentials in session
    //         Session::put('env_credentials', $envCredentials);
    //     }

    //     return $envCredentials;
    // }
    // public static function updateEnvCredentials($key, $value)
    // {
    //     $envCredentials = Session::get('env_credentials');

    //     if ($envCredentials && array_key_exists($key, $envCredentials)) {
    //         $envCredentials[$key] = $value;
    //         Session::put('env_credentials', $envCredentials);
    //         // Update the .env file
    //         $envFile = base_path('.env');
    //         if (file_exists($envFile)) {
    //             file_put_contents($envFile, preg_replace(
    //                 "/^$key=.*\$/m",
    //                 "$key=$value",
    //                 file_get_contents($envFile)
    //             ));
    //         }
    //     }
    // }
}
