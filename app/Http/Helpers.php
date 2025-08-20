<?php

use App\CPU\Helpers;
use App\Models\Category;
use App\Models\City;
use App\Models\Currency;
use App\Models\HomepageSettings;
use App\Models\ConfigurationSetting;
use App\Models\Country;
use App\Models\PricingTier;
use App\Models\Product;
use App\Models\UserNegetiveBalanceWallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

if (!function_exists('randomColor')) {
    function randomColor()
    {
        $colors = ['#FF5733', '#33FF57', '#3357FF', '#F3FF33', '#FF33FB', '#33FFF5'];
        return $colors[array_rand($colors)];
    }
}

if (!function_exists('get_setting')) {
    function get_settings($key, $default = null)
    {
        // if (Session::has('settings.' . $key)) {
        //     return Session::get('settings.' . $key, $default);
        // }

        $value = false;

        if ($key === 'primary_color') {
            $setting = Cache::remember('primary_color', now()->addHours(24), function () use ($key) {
                return ConfigurationSetting::where('type', $key)->first();
            });
        } else {
            $setting = ConfigurationSetting::where('type', $key)->first();
        }
        if ($setting) {
            $value = $setting->value;
        }
        // if ($setting !== null) {
        //     Session::put('settings.' . $key, $setting->value);
        //     return $setting->value;
        // }

        return $value;
    }
}

// HomepageSettings
if (!function_exists('homepage_setting')) {
    function homepage_setting($key)
    {
        if (Session::has('homepage_setting.' . $key)) {

            return Session::get('homepage_setting.' . $key);
        }

        $settings = HomepageSettings::first();

        if ($settings) {
            $data = [
                "bannerSection" => $settings->bannerSection,
                "sliderSection" => $settings->sliderSection,
                "midBanner" => $settings->midBanner,
                "dealOfTheDay" => $settings->dealOfTheDay,
                "trending" => $settings->trending,
                "brands" => $settings->brands,
                "popularANDfeatured" => $settings->popularANDfeatured,
                "newslatter" => $settings->newslatter,
                "last_updated_by" => Helpers::adminName($settings->last_updated_by),
                "last_updated_at" => $settings->updated_at,
            ];

            foreach ($data as $k => $setting) {
                Session::put('homepage_setting.' . $k, $setting);
            }

            return Session::get('homepage_setting.' . $key);
        } else {
            $new = new HomepageSettings();
            $new->last_updated_by = Auth::guard('admin')->id() ?? 1;
            $new->save();

            return false;
        }

        return false;
    }
}

if (!function_exists('get_product_price')) {
    function get_product_price($product)
    {
        // Determine if the product is discounted
        $isDiscounted = $product->discount_type && $product->discount > 0;
        $discountedPrice = $product->unit_price;
        $taxPrice = 0;

        if ($isDiscounted) {
            $discountAmount = $product->discount_type == 'amount'
                ? $product->discount
                : ($product->unit_price * ($product->discount / 100));

            $discountedPrice = $product->unit_price - $discountAmount;
        }

        if ($product->taxes) {
            foreach ($product->taxes as $product_tax) {
                if ($product_tax->tax_type == 'percent') {
                    $discountedPrice += ($discountedPrice * $product_tax->tax) / 100;
                    $taxPrice += ($discountedPrice * $product_tax->tax) / 100;
                } elseif ($product_tax->tax_type == 'amount') {
                    $discountedPrice += $product_tax->tax;
                    $taxPrice += $product_tax->tax;
                }
            }
        }

        return [
            'unit_price' => $product->unit_price,
            'discounted_price' => $discountedPrice,
            'discount' => $product->discount,
            'discount_type' => $isDiscounted ? $product->discount_type : null,
            'tax' => $taxPrice,
        ];
    }
}

// format date
if (!function_exists('get_system_date')) {
    function get_system_date($date)
    {

        $dateObj = Carbon\Carbon::parse($date);
        $dateObj->setTimezone(get_settings('system_timezone') ? get_settings('system_timezone') : 'Asia/Dhaka');

        $dateFormat = get_settings('system_date_format') ?? 'Y-m-d';

        if ($dateFormat) {
            return $dateObj->format($dateFormat);
        } else {
            return $dateObj->format('Y-m-d');
        }
    }
}

if (!function_exists('limit_str')) {
    function limit_str($str, $wordLimit = 30)
    {
        if (empty($str)) {
            return '';
        }

        $words = explode(" ", $str);

        $words = array_slice($words, 0, 60);

        $line1 = implode(" ", array_slice($words, 0, $wordLimit));
        $line2 = implode(" ", array_slice($words, $wordLimit, $wordLimit));

        return $line1 . (strlen($line2) > 0 ? ' <br> ' . $line2 : '');
    }
}
if (!function_exists('add_line_breaks')) {
    function add_line_breaks($text, $wordsPerLine = 30)
    {
        $words = explode(' ', $text);
        $chunks = array_chunk($words, $wordsPerLine);

        $lines = array_map(function ($chunk) {
            return implode(' ', $chunk);
        }, $chunks);

        return implode('<br>', $lines);
    }
}

if (!function_exists('mailChecker')) {
    /**
     * Check the validity of an email.
     *
     * @param string $email
     * @return bool
     */
    function mailChecker(string $email): bool
    {
        if (!isset($email)) {
            return false;
        }

        $parts = explode('_', $email);

        if (isset($parts[0]) && $parts[0] === '%') {
            return false;
        }
        return true;
    }
}


if (!function_exists('getApplicablePricingTier')) {
    function getApplicablePricingTier($currency_id, $models, $totalPrice = null, $totalTax = null)
    {
        // Calculate total price and tax
        if ($totalPrice == null) {
            $totalPrice = array_reduce($models, function ($carry, $item) {
                return $carry + ($item['price'] * $item['quantity']);
            }, 0);
            $totalPrice = round(convert_price($totalPrice), 2);
        }

        if ($totalTax == null) {
            $totalTax = array_reduce($models, function ($carry, $item) {
                return $carry + ($item['tax'] * $item['quantity']);
            }, 0);
            $totalTax = round(convert_price($totalTax), 2);

        }


        // Query PricingTier model with all conditions
        $pricingTier = PricingTier::where('currency_id', $currency_id)
            ->where('status', 1) // Check active status
            ->where(function ($query) use ($totalPrice, $totalTax) {
                $query->where(function ($q) use ($totalPrice, $totalTax) {
                    // Check with product tax
                    $q->where('with_product_tax', 'yes')
                        ->where('threshold', '<=', ($totalPrice + $totalTax));
                })->orWhere(function ($q) use ($totalPrice) {
                    // Check without product tax
                    $q->where('with_product_tax', 'no')
                        ->where('threshold', '<=', $totalPrice);
                });
            })
            ->where(function ($query) {
                // Check if start_date and end_date exist
                $query->whereNull('start_date')
                    ->orWhere(function ($q) {
                        $q->where('start_date', '<=', now())
                            ->where(function ($q2) {
                                $q2->whereNull('end_date')
                                    ->orWhere('end_date', '>=', now());
                            });
                    });
            })
            ->where(function ($query) {
                // Check usage limits
                $query->whereNull('usage_limit')
                    ->orWhereRaw('usage_count < usage_limit');
            })
            ->orderByDesc('threshold') // Get the highest threshold match
            ->first();

        // dd(vsprintf(
        //     str_replace('?', '%s', $pricingTier->toSql()),
        //     array_map(fn($binding) => is_string($binding) ? "'$binding'" : $binding, $pricingTier->getBindings())
        // ));

        return $pricingTier;
    }
}

// format time
if (!function_exists('get_system_time')) {
    function get_system_time($time, $timezone = null)
    {
        $dateObj = Carbon\Carbon::parse($time);

        if ($timezone) {
            $dateObj->setTimezone($timezone);
        } else {
            $dateObj->setTimezone(get_settings('system_timezone') ? get_settings('system_timezone') : 'Asia/Dhaka');
        }

        $timeFormat = get_settings('system_time_format') ?? 'H:i:s A';

        return $dateObj->format($timeFormat);
    }
}

if (!function_exists('tz_list')) {
    function tz_list()
    {
        $zones_array = array();
        $timestamp = time();
        foreach (timezone_identifiers_list() as $key => $zone) {
            date_default_timezone_set($zone);
            $zones_array[$key]['zone'] = $zone;
            $zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
        }
        return $zones_array;
    }
}

//formats currency
if (!function_exists('format_price')) {
    function format_price($price, $isMinimize = false, $isAdmin = false)
    {
        if (get_settings('system_decimal_separator') == 1) {
            $format_price = number_format($price, intval(get_settings('system_no_of_decimals')));
        } else {
            $format_price = number_format($price, intval(get_settings('system_no_of_decimals')), ',', '.');
        }

        // Minimize the price
        if ($isMinimize) {
            $temp = number_format($price / 1000000000, get_settings('system_no_of_decimals'), ".", "");

            if ($temp >= 1) {
                $format_price = $temp . "B";
            } else {
                $temp = number_format($price / 1000000, get_settings('system_no_of_decimals'), ".", "");
                if ($temp >= 1) {
                    $format_price = $temp . "M";
                }
            }
        }

        if (get_settings('system_symbol_format') == '[Symbol][Amount]') {
            return currency_symbol($isAdmin) . $format_price;
        } else if (get_settings('system_symbol_format') == "[Symbol] [Amount]") {
            return currency_symbol($isAdmin) . ' ' . $format_price;
        } else if (get_settings('system_symbol_format') == "[Amount] [Symbol]") {
            return $format_price . ' ' . currency_symbol($isAdmin);
        }

        return $format_price . currency_symbol($isAdmin);
    }
}

function covert_to_usd($price)
{
    if ((get_system_default_currency() !== null && get_system_default_currency()->code != "USD")) {

        $exchangeRate = get_exchange_rate(get_system_default_currency()->code);

        if ($exchangeRate == null) {
            return $price / 1;
        } else {
            return ($price / $exchangeRate);
        }
    }
    return $price;
}

function covert_to_defalut_currency($price)
{
    if ((get_system_default_currency() !== null && get_system_default_currency()->code != "USD")) {
        return ($price * get_exchange_rate(get_system_default_currency()->code));
    }
    return number_format($price, 2);
}

if (!function_exists('reportCurrency')) {
    function reportCurrency($price, $code = "USD")
    {
        if ($code !== "USD") {
            $price = ($price / get_exchange_rate($code));

        }
        return covert_to_defalut_currency($price);

    }
}

// premium user price
if (!function_exists('premium_user_price')) {
    function premium_user_price($price)
    {
        if (get_settings('premium_option_module_enabled') == 1 && Auth::guard('customer')->check() && Auth::guard('customer')->user()->is_premium == 1) {
            if (get_settings('premium_user_discount_type') == 'percent') {
                $price = $price - (($price * (int)get_settings('premium_user_discount_amount')) / 100);
            } else {
                $price = $price - (int)get_settings('premium_user_discount_amount');
            }
        }

        return $price;
    }
}

// get Shipping Fee
if (!function_exists('get_shipping_cost')) {
    function get_shipping_cost()
    {
        $shippingCost = 0;
        switch (get_settings('shipping_cost_type')) {
            case 'free_shipping':
                $shippingCost = 0;
                break;
            case 'flat_rate':
                $shippingCost = get_settings('system_default_delivery_charge');
                break;
            case 'area_wise':
                if (get_settings('shipping_area_type') == 'zone_wise') {
                    if (!Session::has('user_country') || !Country::where('name', Session::get('user_country'))->exists()) {
                        $shippingCost = 0;
                    }

                    $userCountry = Country::where('name', Session::get('user_country'))->first();
                    if (!$userCountry) {
                        $shippingCost = 0;
                    }

                    $userZone = $userCountry->zone;
                    if (!$userZone) {
                        $shippingCost = 0;
                    }

                    $shippingCost = $userZone->cost;
                } elseif (get_settings('shipping_area_type') == 'country_wise') {
                    if (!Session::has('user_country') || !Country::where('name', Session::get('user_country'))->exists()) {
                        $shippingCost = 0;
                    }

                    $userCountry = Country::where('name', Session::get('user_country'))->first();
                    if (!$userCountry) {
                        $shippingCost = 0;
                    }

                    $shippingCost = $userCountry->cost;
                } else {
                    if (!Session::has('user_city') || !City::where('name', Session::get('user_city'))->exists()) {
                        $shippingCost = 0;
                    }

                    $userCity = City::where('name', Session::get('user_city'))->first();
                    if (!$userCity) {
                        $shippingCost = 0;
                    }

                    $shippingCost = $userCity->cost;
                }
                break;
        }

        return $shippingCost;
    }
}

// converts currency to home default currency
if (!function_exists('convert_price')) {
    function convert_price($price)
    {
        if (Session::has('currency_code') && (Session::get('currency_code') != "USD" && $price > 0)) {
            $currency_code = Session::get('currency_code');
            $exchange_rate = get_exchange_rate($currency_code);

            if ($exchange_rate !== null) {
                $price = floatval($price) * $exchange_rate;
            }
        }
        return $price;
    }
}

// converts currency to USD Frontend
if (!function_exists('convert_price_to_usd')) {
    function convert_price_to_usd($price)
    {
        if (Session::has('currency_code') && (Session::get('currency_code') != "USD")) {
            $currency_code = Session::get('currency_code');
            $exchange_rate = get_exchange_rate($currency_code);

            if ($exchange_rate !== null) {
                $price = floatval($price) / $exchange_rate;
            }
        }
        return $price;
    }
}

if (!function_exists('negative_balance')) {
    function negative_balance(): float
    {
        $user = Auth::guard('customer')->user();

        if (Session::has('currency_code') && $user) {
            $currency_code = Session::get('currency_code');

            $balance = $user->negetiveBalanceWallets()
                ->whereHas('currency', function ($query) use ($currency_code) {
                    $query->where('code', $currency_code);
                })
                ->first();

            return $balance ? $balance->current_balance : 0;
        }

        return 0;
    }


}


function fetch_exchange_rate($currency_code)
{
    $api_url = "https://api.currencybeacon.com/v1/convert?api_key=tisDcm3hOvaLnnbrZPP1I5UMgF2JSzkL&from=USD&to=" . strtoupper($currency_code) . "&amount=1";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

function store_exchange_rate($currency_code, $rate)
{
    // Try to find the existing currency entry
    $currency = Currency::where('code', $currency_code)->first();

    if ($currency) {
        // If it exists, update the exchange rate
        $currency->exchange_rate = $rate;
        $currency->save();
    }

    // Store in cache as well
    Cache::put("exchange_rate_{$currency_code}", $rate, get_settings('currency_api_fetch_time') ?? 3600);
}

function checkStockAvailability($stock, $qty, $minusStock)
{
    if ($stock->stock >= $qty) {
        return ['status' => true, 'stock' => $stock->stock];
    }
    if ($minusStock > 0) {
        if ($qty <= ($stock->stock + $minusStock)) {
            return ['status' => true, 'stock' => $stock->stock + $minusStock];
        } else {
            return ['status' => false, 'message' => 'Requested quantity exceeds available stock'];
        }
    }

    return ['status' => false, 'message' => 'Insufficient stock for the requested quantity'];
}

function getProductStock($productId, $qty = 1)
{
    $product = Product::find($productId);
    if (!$product) {
        return ['status' => false, 'message' => 'Product Not Found'];
    }

    if ($product->status == 0) {
        return ['status' => false, 'message' => 'Product is currently not in sale'];
    }

    $stockType = $product->stock_types;

    if (!$product->details) {
        return ['status' => false, 'message' => 'Product Information Not Found'];
    }

    if ($product->in_stock == 0 && $product->minus_stock == 0) {
        return ['status' => false, 'message' => 'Product is currently out of stock'];
    }

    if ($product->details->current_stock < $qty && $product->minus_stock == 0) {
        return ['status' => false, 'message' => 'This product is not available in the desired quantity or not in stock'];
    }

    if (!$product->stock) {
        return ['status' => false, 'message' => 'Product Stock Not Found'];
    }

    switch ($stockType) {
        case 'globally':
            $stock = $product->stock->where('stock', '>', 0)->first();

            if (!$stock) {
                return ['status' => false, 'message' => 'Product is out of stock'];
            }

            return checkStockAvailability($stock, $qty, $product->minus_stock);

        case 'country_wise':
            if (!Session::has('user_country')) {
                return ['status' => false, 'message' => 'Please select your country to buy this product'];
            }

            if (Session::get('user_country') != '') {
                $userCountry = Session::get('user_country');
                $country = Country::where('name', $userCountry)->first();

                if (!$country) {
                    return ['status' => false, 'message' => 'This product has no stock in your country. Please change your country.'];
                }

                $countryId = $country->id;
                $stock = $product->stock->where('country_id', $countryId)->first();

                if (!$stock) {
                    return ['status' => false, 'message' => 'This product has no stock in your country. Please change your country.'];
                }

                return checkStockAvailability($stock, $qty, $product->minus_stock);
            }
            break;

        case 'city_wise':
            if (!Session::has('user_city')) {
                return ['status' => false, 'message' => 'Please select your city to buy this product'];
            }

            if (Session::get('user_city') != '') {
                $userCity = Session::get('user_city');

                $city = City::where('name', $userCity)->first();
                if (!$city) {
                    return ['status' => true, 'in_city' => false, 'message' => 'This product has no stock in your city. Please change your city.'];
                }

                $cityId = $city->id;
                $stock = $product->stock->where('city_id', $cityId)->first();

                if (!$stock) {
                    return ['status' => true, 'in_city' => false, 'message' => 'This product has no stock in your city. Please change your city.'];
                }

                return checkStockAvailability($stock, $qty, $product->minus_stock);
            }

            return ['status' => true, 'stock' => 0];
            break;
    }

    return ['status' => false, 'message' => 'Stock check failed'];
}


function get_exchange_rate($currency_code)
{
    $fetch_time = 3600;
    if (get_settings('currency_api_fetch_time') > 0) {
        $fetch_time = get_settings('currency_api_fetch_time');
    }
    return Cache::remember("exchange_rate_{$currency_code}", $fetch_time, function () use ($currency_code) {
        // Check if the currency exists in the database
        $currency = Currency::where('code', $currency_code)->first();

        // If the currency exists, fetch the exchange rate from the API
        if ($currency) {
            $exchange_rate_data = fetch_exchange_rate($currency_code);
            if ($exchange_rate_data && $exchange_rate_data['meta']['code'] == 200) {
                $rate = $exchange_rate_data['response']['value'];
                // Store in the database
                store_exchange_rate($currency_code, $rate);
                return $rate; // Return the newly fetched rate
            }
        }

        // If currency is not found or API call fails, return null
        return null;
    });
}


// Shows Price on page based on low to high
if (!function_exists('home_price')) {
    function home_price($product, $formatted = true)
    {
        $lowest_price = $product->unit_price;
        $highest_price = $product->unit_price;

        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $lowest_price += ($lowest_price * $product_tax->tax) / 100;
                $highest_price += ($highest_price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'flat') {
                $lowest_price += $product_tax->tax;
                $highest_price += $product_tax->tax;
            }
        }

        if ($formatted) {
            if ($lowest_price == $highest_price) {
                return format_price(convert_price($lowest_price));
            } else {
                return format_price(convert_price($lowest_price)) . ' - ' . format_price(convert_price($highest_price));
            }
        } else {
            return $lowest_price . ' - ' . $highest_price;
        }
    }
}
if (!function_exists('encode')) {
    function encode($value)
    {
        return base64_encode(urlencode(base64_encode($value))); // Encode
    }
}

if (!function_exists('renderFile')) {
    /**
     * Render file based on type (PDF, DOCX, PNG, etc.) - Istiyak
     *
     * @param string $filePath
     * @return string
     */
    function renderFile($filePath)
    {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        if (in_array($extension, ['pdf'])) {
            return '<iframe src="' . asset($filePath) . '" width="100%" height="600px"></iframe>';
        }

        if (in_array($extension, ['png', 'jpg', 'jpeg', 'gif', 'PNG', 'JPEG', 'JPG'])) {
            return '<img src="' . asset($filePath) . '" alt="Image" style="max-width: 100%; height: auto;">';
        }

        if (in_array($extension, ['docx', 'doc'])) {
            return '<a href="' . asset($filePath) . '" target="_blank">Download Document</a>';
        }

        return '<p>Unsupported file type.</p>';
    }
}


function decode($encoded)
{
    return base64_decode(urldecode(base64_decode($encoded)));
}

// Shows Price on page based on low to high with discount
if (!function_exists('home_discounted_price')) {
    function home_discounted_price($product, $formatted = true)
    {
        $lowest_price = $product->unit_price;
        $highest_price = $product->unit_price;

        $discount_applicable = false;

        if ($product->is_discounted == 1) {
            if ($product->discount_start_date == null) {
                $discount_applicable = true;
            } elseif (
                strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
            ) {
                $discount_applicable = true;
            }
        } else {
            $discount_applicable = false;
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percentage') {
                $lowest_price -= ($lowest_price * $product->discount) / 100;
                $highest_price -= ($highest_price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $lowest_price -= $product->discount;
                $highest_price -= $product->discount;
            }
        }

        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $lowest_price += ($lowest_price * $product_tax->tax) / 100;
                $highest_price += ($highest_price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'flat') {
                $lowest_price += $product_tax->tax;
                $highest_price += $product_tax->tax;
            }
        }

        if ($formatted) {
            if ($lowest_price == $highest_price) {
                return format_price(convert_price($lowest_price));
            } else {
                return format_price(convert_price($lowest_price)) . ' - ' . format_price(convert_price($highest_price));
            }
        } else {
            return $lowest_price . ' - ' . $highest_price;
        }
    }
}

//gets currency symbol
if (!function_exists('currency_symbol')) {
    function currency_symbol($isAdmin = false)
    {
        if ($isAdmin) {
            return isset(get_system_default_currency()->symbol) ? get_system_default_currency()->symbol : '$';
        }
        if (Session::has('currency_symbol')) {
            return Session::get('currency_symbol');
        }

        return isset(get_system_default_currency()->symbol) ? get_system_default_currency()->symbol : '$';
    }
}

if (!function_exists('get_system_default_currency')) {
    function get_system_default_currency()
    {
        $currency = Currency::find(get_settings('system_default_currency'));
        if (!$currency) {
            $currency = Currency::where('name', 'US Dollar')->first();
        }
        return $currency;
    }
}

if (!function_exists('get_immediate_children_ids')) {
    function get_immediate_children_ids($id, $with_trashed = false)
    {
        $children = get_immediate_children($id, $with_trashed, true);

        return !empty($children) ? array_column($children, 'id') : array();
    }
}

if (!function_exists('get_immediate_children_count')) {
    function get_immediate_children_count($id, $with_trashed = false)
    {
        return $with_trashed ? Category::where('status', 1)->where('parent_id', $id)->count() : Category::where('status', 1)->where('parent_id', $id)->count();
    }
}

if (!function_exists('get_immediate_children')) {
    function get_immediate_children($id, $with_trashed = false, $as_array = false)
    {
        $children = $with_trashed ? Category::where('status', 1)->where('parent_id', $id)->orderBy('name', 'ASC')->get() : Category::where('status', 1)->where('parent_id', $id)->orderBy('name', 'ASC')->get();
        $children = $as_array && !is_null($children) ? $children->toArray() : $children;

        return $children;
    }
}
