<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\CPU\Images;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ConfigurationSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ConfigurationSettingController extends Controller
{
    public function general()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('settings.general-settings') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $currencies = Currency::where('status', 1)->get();
        return view('backend.settings.general', compact('currencies'));
    }

    public function seo($slug)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('website-setup.seo') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $page = null;
        switch($slug) {
            case 'home':
                $page = 'Home Page';
            break;
            case 'login':
                $page = 'Login Page';
            break;
            case 'register':
                $page = 'Register Page';
            break;
            case 'forget':
                $page = 'Forget Password Page';
            break;
            case 'contact':
                $page = 'Contact Page';
            break;
            case 'pc_builder':
                $page = 'PC Builder Page';
            break;
            case 'all_categories':
                $page = 'All Categories Page';
            break;
            case 'all_brand':
                $page = 'All Brand Page';
            break;
            case 'laptop_buying_guide':
                $page = 'Laptop Buying Guide Page';
            break;
            case 'track_order':
                $page = 'Track Order Page';
            break;
            case 'track_order_result':
                $page = 'Track Order Result Page';
            break;
            case 'compare':
                $page = 'Compare Page';
            break;
            case 'order_confirmation':
                $page = 'Order Confirmation Page';
            break;
            case 'flash_deals':
                $page = 'Flash Deals Page';
            break;
            case 'cart':
                $page = 'Cart Page';
            break;
            case 'checkout':
                $page = 'Checkout Page';
            break;
        }

        return view('backend.settings.seo', compact('page', 'slug'));
    }

    public function laptopOfferPage()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.offer-page') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('backend.settings.laptop-offer');
    }

    public function laptopFinderPage()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.finder-page') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('backend.settings.laptop-finder');
    }

    public function websiteHeader()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('website-setup.header') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('backend.settings.website.header');
    }

    public function websiteFooter()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('website-setup.footer') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('backend.settings.website.footer');
    }

    public function websiteAppearance()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('website-setup.appearance') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('backend.settings.website.appearance');
    }

    public function otp()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('settings.sms-templates') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('backend.settings.otp');
    }

    public function shippingConfiguration()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.update') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('backend.settings.shipping-configuration');
    }

    public function email()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('settings.email-templates') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('backend.settings.email');
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'system_logo_white' => 'mimes:jpeg,bmp,png,jpg,svg|max:2000',
            'system_logo_dark' => 'mimes:jpeg,bmp,png,jpg,svg|max:2000',
            'system_favicon' => 'mimes:jpeg,bmp,png,jpg,svg|max:2000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }
        Cache::forget('primary_color');
        $input = $request->all();
        $config_type = $request->config_type;
        $boolean_system_setting = config('system.boolean.' . $config_type);

        if ($boolean_system_setting) {
            foreach ($boolean_system_setting as $v) {
                $config = ConfigurationSetting::firstOrCreate(['type' => $v]);
                $config->value = 0;
                $config->save();
            }
        }

        foreach ($_POST as $key => $value) {
            if ($key == "_token") {
                continue;
            }

            $data = array();
            if ($key == 'header_menu_labels' || $key == 'header_menu_links' || $key == 'footer_menu_one_labels' || $key == 'footer_menu_one_links' || $key == 'footer_menu_two_labels' || $key == 'footer_menu_two_links' || $key == 'footer_menu_three_labels' || $key == 'footer_menu_three_links') {
                $data['value'] = json_encode($value);
            } elseif ($key == 'system_default_delivery_charge' || $key =='premium_user_discount_amount') {
                $data['value'] = covert_to_usd($value);
            } else {
                $data['value'] = $value;
            }

            $data['updated_at'] = Carbon::now();
            if (ConfigurationSetting::where('type', $key)->exists()) {
                ConfigurationSetting::where('type', '=', $key)->update($data);
            } else {
                $data['type'] = $key;
                $data['created_at'] = Carbon::now();

                ConfigurationSetting::insert($data);
            }

            Session::put('settings.' . $key, $value);
        }

        if ($request->hasFile('system_logo_white')) {
            $fileName = Images::upload('system', $request->system_logo_white);
            $logo['type'] = 'system_logo_white';
            $logo['value'] = $fileName;

            if (ConfigurationSetting::where('type', "system_logo_white")->exists()) {
                ConfigurationSetting::where('type', '=', "system_logo_white")->update($logo);
            } else {
                $logo['created_at'] = Carbon::now();
                ConfigurationSetting::insert($logo);
            }

            Session::put('settings.system_logo_white', $fileName);
        }

        if ($request->hasFile('system_logo_dark')) {
            $fileName = Images::upload('system', $request->system_logo_dark);
            $logo['type'] = 'system_logo_dark';
            $logo['value'] = $fileName;

            if (ConfigurationSetting::where('type', "system_logo_dark")->exists()) {
                ConfigurationSetting::where('type', '=', "system_logo_dark")->update($logo);
            } else {
                $logo['created_at'] = Carbon::now();
                ConfigurationSetting::insert($logo);
            }

            Session::put('settings.system_logo_dark', $fileName);
        }

        if ($request->hasFile('system_favicon')) {
            $fileName = Images::upload('system', $request->system_favicon);
            $logo['type'] = 'system_favicon';
            $logo['value'] = $fileName;

            if (ConfigurationSetting::where('type', "system_favicon")->exists()) {
                ConfigurationSetting::where('type', '=', "system_favicon")->update($logo);
            } else {
                $logo['created_at'] = Carbon::now();
                ConfigurationSetting::insert($logo);
            }

            Session::put('settings.system_favicon', $fileName);
        }
        
        if ($request->hasFile('star_image')) {
            $fileName = Images::upload('system', $request->star_image);
            $logo['type'] = 'star_image';
            $logo['value'] = $fileName;

            if (ConfigurationSetting::where('type', "star_image")->exists()) {
                ConfigurationSetting::where('type', '=', "star_image")->update($logo);
            } else {
                $logo['created_at'] = Carbon::now();
                ConfigurationSetting::insert($logo);
            }

            Session::put('settings.star_image', $fileName);
        }

        if ($request->hasFile('system_topbar_banner')) {
            $fileName = Images::upload('system', $request->system_topbar_banner);
            $logo['type'] = 'system_topbar_banner';
            $logo['value'] = $fileName;

            if (ConfigurationSetting::where('type', "system_topbar_banner")->exists()) {
                ConfigurationSetting::where('type', '=', "system_topbar_banner")->update($logo);
            } else {
                $logo['created_at'] = Carbon::now();
                ConfigurationSetting::insert($logo);
            }

            Session::put('settings.system_topbar_banner', $fileName);
        }

        if ($request->hasFile('system_payment_method_photo')) {
            $fileName = Images::upload('system', $request->system_payment_method_photo);
            $logo['type'] = 'system_payment_method_photo';
            $logo['value'] = $fileName;

            if (ConfigurationSetting::where('type', "system_payment_method_photo")->exists()) {
                ConfigurationSetting::where('type', '=', "system_payment_method_photo")->update($logo);
            } else {
                $logo['created_at'] = Carbon::now();
                ConfigurationSetting::insert($logo);
            }

            Session::put('settings.system_payment_method_photo', $fileName);
        }

        if ($request->hasFile('system_icon')) {
            $fileName = Images::upload('system', $request->system_icon);
            $logo['type'] = 'system_icon';
            $logo['value'] = $fileName;

            if (ConfigurationSetting::where('type', "system_icon")->exists()) {
                ConfigurationSetting::where('type', '=', "system_icon")->update($logo);
            } else {
                $logo['created_at'] = Carbon::now();
                ConfigurationSetting::insert($logo);
            }

            Session::put('settings.system_icon', $fileName);
        }

        if ($request->hasFile('system_global_meta_image')) {
            $fileName = Images::upload('system', $request->system_global_meta_image);
            $logo['type'] = 'system_global_meta_image';
            $logo['value'] = $fileName;

            if (ConfigurationSetting::where('type', "system_global_meta_image")->exists()) {
                ConfigurationSetting::where('type', '=', "system_global_meta_image")->update($logo);
            } else {
                $logo['created_at'] = Carbon::now();
                ConfigurationSetting::insert($logo);
            }

            Session::put('settings.system_global_meta_image', $fileName);
        }

        return response()->json([
            'status' => true,
            'message' => 'Configuration updated',
            'load' => true
        ]);
    }
}
