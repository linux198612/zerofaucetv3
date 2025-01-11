<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cronjob extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database(); // Adatbázis kapcsolat
    }

    public function coingecko() {
        $currency_coingecko = 'zero'; // CoinGecko API ID

        if (!empty($currency_coingecko)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_URL, 'https://api.coingecko.com/api/v3/coins/' . $currency_coingecko . '?localization=en&sparkline=false');
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $usd_rate_data_json = curl_exec($ch);

            if (curl_errno($ch)) {
                log_message('error', 'cURL error: ' . curl_error($ch));
                curl_close($ch);
                return;
            }

            curl_close($ch);

            if (!$usd_rate_data_json) {
                log_message('error', 'Failed to fetch data from CoinGecko API. Response is empty.');
                return;
            }

            $usd_rate_data = json_decode($usd_rate_data_json, true);

            if (!isset($usd_rate_data['market_data']['current_price']['usd'])) {
                log_message('error', 'Unexpected API response structure.');
                return;
            }

            // Árfolyam lekérése és kerekítése
            $price = $usd_rate_data['market_data']['current_price']['usd'];
            $price = round($price, 5); // 5 tizedesjegyre kerekítés

            // Adatbázis frissítése
            $this->db->where('name', 'currency_value');
            if (!$this->db->update('settings', ['value' => $price])) {
                log_message('error', 'Failed to update currency value in the database.');
            } else {
                log_message('info', 'Currency value successfully updated: ' . $price);
            }
        } else {
            log_message('error', 'Currency ID is not set for CoinGecko API.');
        }
    }
    
    public function cmc() {
        $currency_symbol = 'ZER'; // CoinMarketCap API ticker symbol
        $api_key = $this->settings['cmc_api']; // Helyettesítsd a saját API kulcsoddal

        if (!empty($currency_symbol) && !empty($api_key)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_URL, 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest?symbol=' . $currency_symbol);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accepts: application/json',
                'X-CMC_PRO_API_KEY: ' . $api_key,
            ]);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                log_message('error', 'cURL error: ' . curl_error($ch));
                curl_close($ch);
                return;
            }

            curl_close($ch);

            if (!$response) {
                log_message('error', 'Failed to fetch data from CoinMarketCap API. Response is empty.');
                return;
            }

            $data = json_decode($response, true);

            if (!isset($data['data'][$currency_symbol]['quote']['USD']['price'])) {
                log_message('error', 'Unexpected API response structure.');
                return;
            }

            // Árfolyam lekérése és kerekítése
            $price = $data['data'][$currency_symbol]['quote']['USD']['price'];
            $price = round($price, 5); // 5 tizedesjegyre kerekítés

            // Adatbázis frissítése
            $this->db->where('name', 'currency_value');
            if (!$this->db->update('settings', ['value' => $price])) {
                log_message('error', 'Failed to update currency value in the database.');
            } else {
                log_message('info', 'Currency value successfully updated: ' . $price);
            }
        } else {
            log_message('error', 'Currency symbol or API key is not set for CoinMarketCap API.');
        }
    }
}
