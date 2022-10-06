<?php

use App\Model\AdminSetting;
use Illuminate\Database\Seeder;

class AdminSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdminSetting::firstOrCreate(['slug'=>'exchange_url'],['value'=>'']);
        AdminSetting::firstOrCreate(['slug'=>'coin_price'],['value'=>'2.50']);
        AdminSetting::firstOrCreate(['slug'=>'coin_name'],['value'=>'TradexPro']);
        AdminSetting::firstOrCreate(['slug'=>'app_title'],['value'=>'TradexPro Admin']);
        AdminSetting::firstOrCreate(['slug'=>'maximum_withdrawal_daily'],['value'=>'3']);
        AdminSetting::firstOrCreate(['slug'=>'mail_from'],['value'=>'noreply@cpoket.com']);
        AdminSetting::firstOrCreate(['slug'=>'admin_coin_address'],['value'=>'address']);
        AdminSetting::firstOrCreate(['slug'=>'base_coin_type'],['value'=>'BTC']);
        AdminSetting::firstOrCreate(['slug'=>'minimum_withdrawal_amount'],['value'=>.005]);
        AdminSetting::firstOrCreate(['slug'=>'maximum_withdrawal_amount'],['value'=>12]);

        AdminSetting::firstOrCreate(['slug' => 'maintenance_mode'],['value' => 'no']);
        AdminSetting::firstOrCreate(['slug' => 'logo'],['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'login_logo'],['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'landing_logo'],['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'favicon'], ['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'copyright_text'], ['value' => 'Copyright@2020']);
        AdminSetting::firstOrCreate(['slug' => 'pagination_count'], ['value' => '10']);
        AdminSetting::firstOrCreate(['slug' => 'point_rate'], ['value' => '1']);

        //General Settings
        AdminSetting::firstOrCreate(['slug' => 'currency'],[ 'value' => 'USD']);
        AdminSetting::firstOrCreate(['slug' => 'lang'], ['value' => 'en']);
        AdminSetting::firstOrCreate(['slug' => 'company_name'], ['value' => 'Test Company']);
        AdminSetting::firstOrCreate(['slug' => 'primary_email'], ['value' => 'test@email.com']);

        AdminSetting::firstOrCreate(['slug' => 'sms_getway_name'], ['value' => 'twillo']);
        AdminSetting::firstOrCreate(['slug' => 'twillo_secret_key'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'twillo_auth_token'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'twillo_number'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'ssl_verify'], ['value' => '']);

        AdminSetting::firstOrCreate(['slug' => 'mail_driver'], ['value' => 'SMTP']);
        AdminSetting::firstOrCreate(['slug' => 'mail_host'], ['value' => 'smtp.mailtrap.io']);
        AdminSetting::firstOrCreate(['slug' => 'mail_port'], ['value' => 2525]);
        AdminSetting::firstOrCreate(['slug' => 'mail_username'], ['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'mail_password'], ['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'mail_encryption'], ['value' => 'null']);
        AdminSetting::firstOrCreate(['slug' => 'mail_from_address'], ['value' => '']);


        AdminSetting::firstOrCreate(['slug' => 'braintree_client_token'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'braintree_environment'], ['value' => 'sandbox']);
        AdminSetting::firstOrCreate(['slug' => 'braintree_merchant_id'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'braintree_public_key'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'braintree_private_key'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'sms_getway_name'], ['value' => 'twillo']);
        AdminSetting::firstOrCreate(['slug' => 'clickatell_api_key'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'number_of_confirmation'], ['value' => '6']);
        AdminSetting::firstOrCreate(['slug' => 'referral_commission_percentage'], ['value' => '10']);
        AdminSetting::firstOrCreate(['slug' => 'referral_signup_reward'], ['value' => 10]);
        AdminSetting::firstOrCreate(['slug' => 'max_affiliation_level'], ['value' => 3]);

        // Coin Api
        AdminSetting::firstOrCreate(['slug' => 'coin_api_user'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'coin_api_pass'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'coin_api_host'], ['value' => 'test5']);
        AdminSetting::firstOrCreate(['slug' => 'coin_api_port'], ['value' => 'test']);

        //coin payment
        AdminSetting::firstOrCreate(['slug' => 'COIN_PAYMENT_PUBLIC_KEY'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'COIN_PAYMENT_PRIVATE_KEY'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'COIN_PAYMENT_CURRENCY'], ['value' => 'BTC']);
        AdminSetting::firstOrCreate(['slug' => 'ipn_merchant_id'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'ipn_secret'], ['value' => 'test']);

        AdminSetting::firstOrCreate(['slug' => 'payment_method_coin_payment'], ['value' => 1]);
        AdminSetting::firstOrCreate(['slug' => 'payment_method_bank_deposit'], ['value' => 1]);

        // kyc setting
        AdminSetting::firstOrCreate(['slug' => 'kyc_enable_for_withdrawal'], ['value' => 0]);
        AdminSetting::firstOrCreate(['slug' => 'kyc_nid_enable_for_withdrawal'], ['value' => 0]);
        AdminSetting::firstOrCreate(['slug' => 'kyc_passport_enable_for_withdrawal'], ['value' => 0]);
        AdminSetting::firstOrCreate(['slug' => 'kyc_driving_enable_for_withdrawal'], ['value' => 0]);

        AdminSetting::firstOrCreate(['slug' => 'trade_limit_1'], ['value' => 0]);
        AdminSetting::firstOrCreate(['slug' => 'maker_1'], ['value' => 0]);
        AdminSetting::firstOrCreate(['slug' => 'taker_1'], ['value' => 0]);

        AdminSetting::firstOrCreate(['slug' => 'NOCAPTCHA_SECRET'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'NOCAPTCHA_SITEKEY'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'google_recapcha'], ['value' => 0]);

        AdminSetting::firstOrCreate(['slug' => 'landing_title'], ['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'landing_description'], ['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'footer_description'], ['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'landing_page_logo'], ['value' => '']);

        AdminSetting::firstOrCreate(['slug' => 'landing_feature_title'], ['value' => '']);

        AdminSetting::firstOrCreate(['slug' => 'market_trend_title'], ['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'trade_anywhere_title'], ['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'trade_anywhere_left_img'], ['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'secure_trade_title'], ['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'secure_trade_left_img'], ['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'customization_title'], ['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'customization_details'], ['value' => '']);

        AdminSetting::firstOrCreate(['slug' => 'apple_store_link'], ['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'android_store_link'], ['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'google_store_link'], ['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'macos_store_link'], ['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'windows_store_link'], ['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'linux_store_link'], ['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'api_link'], ['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'trading_price_tolerance'], ['value' => 10]);

        // bitgo setting
        AdminSetting::firstOrCreate(['slug' => 'bitgo_api'], ['value' => 'https://app.bitgo-test.com/api/v2']);
        AdminSetting::firstOrCreate(['slug' => 'bitgoExpess'], ['value' => 'http://localhost:3080/api/v2']);
        AdminSetting::firstOrCreate(['slug' => 'BITGO_ENV'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'bitgo_token'], ['value' => 'test']);

        AdminSetting::firstOrCreate(['slug' => 'CRYPTOCOMPARE_API_KEY'], ['value' => '']);

        AdminSetting::firstOrCreate(['slug' => 'erc20_app_url'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'erc20_app_key'], ['value' => 'test']);
        AdminSetting::firstOrCreate(['slug' => 'erc20_app_port'], ['value' => 'test']);

        AdminSetting::firstOrCreate(['slug' => 'contract_decimal'], ['value' => 18]);
        AdminSetting::firstOrCreate(['slug' => 'gas_limit'], ['value' => 43000]);
        AdminSetting::firstOrCreate(['slug' => 'contract_coin_name'], ['value' => 'BNB']);

        AdminSetting::firstOrCreate(['slug' => 'cookie_status'], ['value' => 1]);
        AdminSetting::firstOrCreate(['slug' => 'cookie_header'], ['value' => 'Cookies Constent']);
        AdminSetting::firstOrCreate(['slug' => 'cookie_text'], ['value' => 'This website use cookies to ensure you get the best experience']);
        AdminSetting::firstOrCreate(['slug' => 'cookie_button_text'], ['value' => 'Privacy Policy']);
        AdminSetting::firstOrCreate(['slug' => 'cookie_image'], ['value' => '']);
        AdminSetting::firstOrCreate(['slug' => 'cookie_page_key'], ['value' => 'privacy-policy']);

        AdminSetting::firstOrCreate(['slug' => 'live_chat_status'], ['value' => 1]);
        AdminSetting::firstOrCreate(['slug' => 'live_chat_key'], ['value' => 'encrypt']);
    }
}
