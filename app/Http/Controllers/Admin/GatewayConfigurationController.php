<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class GatewayConfigurationController extends Controller
{

    public function configuration(Request $request)
    {
        // dd($request->all());

        $typesarray = ['paypal', 'sslcommerz'];
        foreach ($request->all() as $key => $value) {
            if (($key != '_token' && $key != 'type') && isset($request->type) && in_array($request->type, $typesarray)) {

                $envFile = base_path('.env');
                if (file_exists($envFile)) {
                    $content = file_get_contents($envFile);

                    if (preg_match("/^$key=.*\$/m", $content)) {
                        $content = preg_replace("/^$key=.*\$/m", "$key=$value", $content);
                    } else {
                        $content .= "\n$key=$value";
                    }

                    file_put_contents($envFile, $content);

                    Log::info("$request->type Updated .env: $key=$value");
                }
            }
        }

        Artisan::call('config:clear');

        return response()->json(['message' => 'Configuration updated successfully.', 'status' => true, 'load' => true]);
    }
}
