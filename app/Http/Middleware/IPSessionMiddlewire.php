<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Stevebauman\Location\Facades\Location;
use Symfony\Component\HttpFoundation\Response;
use Torann\GeoIP\Facades\GeoIP;

class IPSessionMiddlewire
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currencyMap = [
            'AF' => 'AFN', // Afghanistan
            'AL' => 'ALL', // Albania
            'DZ' => 'DZD', // Algeria
            'AS' => 'USD', // American Samoa
            'AD' => 'EUR', // Andorra
            'AO' => 'AOA', // Angola
            'AR' => 'ARS', // Argentina
            'AM' => 'AMD', // Armenia
            'AU' => 'AUD', // Australia
            'AT' => 'EUR', // Austria
            'AZ' => 'AZN', // Azerbaijan
            'BS' => 'BSD', // Bahamas
            'BH' => 'BHD', // Bahrain
            'BD' => 'BDT', // Bangladesh
            'BB' => 'BBD', // Barbados
            'BY' => 'BYN', // Belarus
            'BE' => 'EUR', // Belgium
            'BZ' => 'BZD', // Belize
            'BJ' => 'XOF', // Benin
            'BT' => 'BTN', // Bhutan
            'BO' => 'BOB', // Bolivia
            'BA' => 'BAM', // Bosnia and Herzegovina
            'BW' => 'BWP', // Botswana
            'BR' => 'BRL', // Brazil
            'BN' => 'BND', // Brunei
            'BG' => 'BGN', // Bulgaria
            'BF' => 'XOF', // Burkina Faso
            'BI' => 'BIF', // Burundi
            'KH' => 'KHR', // Cambodia
            'CM' => 'XAF', // Cameroon
            'CA' => 'CAD', // Canada
            'CV' => 'CVE', // Cape Verde
            'CF' => 'XAF', // Central African Republic
            'TD' => 'XAF', // Chad
            'CL' => 'CLP', // Chile
            'CN' => 'CNY', // China
            'CO' => 'COP', // Colombia
            'KM' => 'KMF', // Comoros
            'CG' => 'XAF', // Congo
            'CR' => 'CRC', // Costa Rica
            'CI' => 'XOF', // Côte d'Ivoire
            'HR' => 'HRK', // Croatia
            'CU' => 'CUP', // Cuba
            'CY' => 'EUR', // Cyprus
            'CZ' => 'CZK', // Czech Republic
            'DK' => 'DKK', // Denmark
            'DJ' => 'DJF', // Djibouti
            'DM' => 'XCD', // Dominica
            'DO' => 'DOP', // Dominican Republic
            'EC' => 'USD', // Ecuador
            'EG' => 'EGP', // Egypt
            'SV' => 'USD', // El Salvador
            'GQ' => 'XAF', // Equatorial Guinea
            'ER' => 'ERN', // Eritrea
            'EE' => 'EUR', // Estonia
            'ET' => 'ETB', // Ethiopia
            'FJ' => 'FJD', // Fiji
            'FI' => 'EUR', // Finland
            'FR' => 'EUR', // France
            'GA' => 'XAF', // Gabon
            'GM' => 'GMD', // Gambia
            'GE' => 'GEL', // Georgia
            'DE' => 'EUR', // Germany
            'GH' => 'GHS', // Ghana
            'GR' => 'EUR', // Greece
            'GD' => 'XCD', // Grenada
            'GT' => 'GTQ', // Guatemala
            'GN' => 'GNF', // Guinea
            'GW' => 'XOF', // Guinea-Bissau
            'GY' => 'GYD', // Guyana
            'HT' => 'HTG', // Haiti
            'HN' => 'HNL', // Honduras
            'HK' => 'HKD', // Hong Kong
            'HU' => 'HUF', // Hungary
            'IS' => 'ISK', // Iceland
            'IN' => 'INR', // India
            'ID' => 'IDR', // Indonesia
            'IR' => 'IRR', // Iran
            'IQ' => 'IQD', // Iraq
            'IE' => 'EUR', // Ireland
            'IL' => 'ILS', // Israel
            'IT' => 'EUR', // Italy
            'JM' => 'JMD', // Jamaica
            'JP' => 'JPY', // Japan
            'JO' => 'JOD', // Jordan
            'KZ' => 'KZT', // Kazakhstan
            'KE' => 'KES', // Kenya
            'KI' => 'AUD', // Kiribati
            'KP' => 'KPW', // North Korea
            'KR' => 'KRW', // South Korea
            'KW' => 'KWD', // Kuwait
            'KG' => 'KGS', // Kyrgyzstan
            'LA' => 'LAK', // Laos
            'LV' => 'EUR', // Latvia
            'LB' => 'LBP', // Lebanon
            'LS' => 'LSL', // Lesotho
            'LR' => 'LRD', // Liberia
            'LY' => 'LYD', // Libya
            'LI' => 'CHF', // Liechtenstein
            'LT' => 'EUR', // Lithuania
            'LU' => 'EUR', // Luxembourg
            'MG' => 'MGA', // Madagascar
            'MW' => 'MWK', // Malawi
            'MY' => 'MYR', // Malaysia
            'MV' => 'MVR', // Maldives
            'ML' => 'XOF', // Mali
            'MT' => 'EUR', // Malta
            'MH' => 'USD', // Marshall Islands
            'MR' => 'MRU', // Mauritania
            'MU' => 'MUR', // Mauritius
            'MX' => 'MXN', // Mexico
            'FM' => 'USD', // Micronesia
            'MD' => 'MDL', // Moldova
            'MC' => 'EUR', // Monaco
            'MN' => 'MNT', // Mongolia
            'ME' => 'EUR', // Montenegro
            'MA' => 'MAD', // Morocco
            'MZ' => 'MZN', // Mozambique
            'MM' => 'MMK', // Myanmar
            'NA' => 'NAD', // Namibia
            'NR' => 'AUD', // Nauru
            'NP' => 'NPR', // Nepal
            'NL' => 'EUR', // Netherlands
            'NZ' => 'NZD', // New Zealand
            'NI' => 'NIO', // Nicaragua
            'NE' => 'XOF', // Niger
            'NG' => 'NGN', // Nigeria
            'NO' => 'NOK', // Norway
            'OM' => 'OMR', // Oman
            'PK' => 'PKR', // Pakistan
            'PW' => 'USD', // Palau
            'PA' => 'PAB', // Panama
            'PG' => 'PGK', // Papua New Guinea
            'PY' => 'PYG', // Paraguay
            'PE' => 'PEN', // Peru
            'PH' => 'PHP', // Philippines
            'PL' => 'PLN', // Poland
            'PT' => 'EUR', // Portugal
            'QA' => 'QAR', // Qatar
            'RO' => 'RON', // Romania
            'RU' => 'RUB', // Russia
            'RW' => 'RWF', // Rwanda
            'WS' => 'WST', // Samoa
            'SM' => 'EUR', // San Marino
            'ST' => 'STN', // Sao Tome and Principe
            'SA' => 'SAR', // Saudi Arabia
            'SN' => 'XOF', // Senegal
            'RS' => 'RSD', // Serbia
            'SC' => 'SCR', // Seychelles
            'SL' => 'SLL', // Sierra Leone
            'SG' => 'SGD', // Singapore
            'SK' => 'EUR', // Slovakia
            'SI' => 'EUR', // Slovenia
            'SB' => 'SBD', // Solomon Islands
            'SO' => 'SOS', // Somalia
            'ZA' => 'ZAR', // South Africa
            'ES' => 'EUR', // Spain
            'LK' => 'LKR', // Sri Lanka
            'SD' => 'SDG', // Sudan
            'SR' => 'SRD', // Suriname
            'SE' => 'SEK', // Sweden
            'CH' => 'CHF', // Switzerland
            'SY' => 'SYP', // Syria
            'TW' => 'TWD', // Taiwan
            'TJ' => 'TJS', // Tajikistan
            'TZ' => 'TZS', // Tanzania
            'TH' => 'THB', // Thailand
            'TL' => 'USD', // Timor-Leste
            'TG' => 'XOF', // Togo
            'TO' => 'TOP', // Tonga
            'TT' => 'TTD', // Trinidad and Tobago
            'TN' => 'TND', // Tunisia
            'TR' => 'TRY', // Turkey
            'TM' => 'TMT', // Turkmenistan
            'TV' => 'AUD', // Tuvalu
            'UG' => 'UGX', // Uganda
            'UA' => 'UAH', // Ukraine
            'AE' => 'AED', // United Arab Emirates
            'GB' => 'GBP', // United Kingdom
            'US' => 'USD', // United States
            'UY' => 'UYU', // Uruguay
            'UZ' => 'UZS', // Uzbekistan
        ];

        $countryMap = [
            'AF' => 'Afghanistan',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia and Herzegovina',
            'BW' => 'Botswana',
            'BR' => 'Brazil',
            'BN' => 'Brunei',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CV' => 'Cape Verde',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CO' => 'Colombia',
            'KM' => 'Comoros',
            'CG' => 'Congo',
            'CR' => 'Costa Rica',
            'CI' => 'Côte d\'Ivoire',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GR' => 'Greece',
            'GD' => 'Grenada',
            'GT' => 'Guatemala',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KP' => 'North Korea',
            'KR' => 'South Korea',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => 'Laos',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'MX' => 'Mexico',
            'FM' => 'Micronesia',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar (Burma)',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestine',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'QA' => 'Qatar',
            'RO' => 'Romania',
            'RU' => 'Russia',
            'RW' => 'Rwanda',
            'KN' => 'Saint Kitts and Nevis',
            'LC' => 'Saint Lucia',
            'VC' => 'Saint Vincent and the Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'São Tomé and Príncipe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'SS' => 'South Sudan',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syria',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TO' => 'Tonga',
            'TT' => 'Trinidad and Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VA' => 'Vatican City',
            'VE' => 'Venezuela',
            'VN' => 'Vietnam',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe',
        ];

        // $ip = request()->ip() == '127.0.0.1' ? '221.120.227.235' : request()->ip();
        $ip = request()->ip() == '127.0.0.1' ? '118.67.220.118' : request()->ip();
        if (!Session::has('user_country') || !Session::has('user_city') || (Session::get('ip') != $ip)) {
            
            $location = Location::get($ip);

            $currency_code = "USD";
            if ($location) {
                $country = $location->countryCode;
                $city = $location->cityName;
                $currency_code = $currencyMap[$location->countryCode] ?? 'BDT';

                $currency = Currency::where('status', 1)->where('code', $currency_code)->select('id', 'symbol')->first();
                if (isset($currency)) {
                    Session::put('currency_id', $currency->id);
                    Session::put('currency_symbol', $currency->symbol);
                    Session::put('currency_code', $currency_code ?? "USD");
                }

                Session::put('country_flag', 'https://flagsapi.com/' . $location->countryCode . '/flat/64.png');
                Session::put('user_country', $countryMap[$location->countryCode] ?? 'Bangladesh');
                Session::put('country', $countryMap[$location->countryCode] ?? 'Bangladesh');
                Session::put('user_city', $city);
                Session::put('ip', $ip);
            }
        }

        return $next($request);
    }
}
