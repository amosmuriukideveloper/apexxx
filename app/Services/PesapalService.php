<?php

namespace App\Services;

use App\Settings\PaymentSettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PesapalService
{
    protected $paymentSettings;
    protected $consumerKey;
    protected $consumerSecret;
    protected $baseUrl;
    protected $ipnUrl;
    
    public function __construct()
    {
        $this->paymentSettings = app(PaymentSettings::class);
        $this->consumerKey = $this->paymentSettings->pesapal_consumer_key;
        $this->consumerSecret = $this->paymentSettings->pesapal_consumer_secret;
        $this->ipnUrl = $this->paymentSettings->pesapal_ipn_url ?? url('/api/pesapal/ipn');
        $this->baseUrl = $this->paymentSettings->pesapal_demo_mode
            ? 'https://cybqa.pesapal.com/pesapalv3'
            : 'https://pay.pesapal.com/v3';
    }
    
    /**
     * Get Pesapal access token
     */
    protected function getAccessToken()
    {
        try {
            $response = Http::post($this->baseUrl . '/api/Auth/RequestToken', [
                'consumer_key' => $this->consumerKey,
                'consumer_secret' => $this->consumerSecret,
            ]);
            
            if ($response->successful()) {
                return $response->json()['token'];
            }
            
            Log::error('Pesapal Access Token Error', ['response' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error('Pesapal Access Token Exception', ['error' => $e->getMessage()]);
            return null;
        }
    }
    
    /**
     * Register IPN URL
     */
    protected function registerIPN($accessToken)
    {
        try {
            $response = Http::withToken($accessToken)
                ->post($this->baseUrl . '/api/URLSetup/RegisterIPN', [
                    'url' => $this->ipnUrl,
                    'ipn_notification_type' => 'GET',
                ]);
            
            if ($response->successful()) {
                return $response->json()['ipn_id'];
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Pesapal IPN Registration Exception', ['error' => $e->getMessage()]);
            return null;
        }
    }
    
    /**
     * Submit order request
     */
    public function submitOrderRequest($amount, $currency, $reference, $description, $callbackUrl, $customerDetails = [])
    {
        $accessToken = $this->getAccessToken();
        
        if (!$accessToken) {
            return [
                'success' => false,
                'message' => 'Failed to get Pesapal access token'
            ];
        }
        
        // Register IPN if not already registered (you should cache this)
        $ipnId = $this->registerIPN($accessToken);
        
        $payload = [
            'id' => $reference,
            'currency' => $currency ?? 'KES',
            'amount' => floatval($amount),
            'description' => $description,
            'callback_url' => $callbackUrl,
            'notification_id' => $ipnId,
            'billing_address' => [
                'email_address' => $customerDetails['email'] ?? 'customer@example.com',
                'phone_number' => $customerDetails['phone'] ?? '',
                'country_code' => 'KE',
                'first_name' => $customerDetails['first_name'] ?? 'Customer',
                'middle_name' => '',
                'last_name' => $customerDetails['last_name'] ?? '',
                'line_1' => '',
                'line_2' => '',
                'city' => '',
                'state' => '',
                'postal_code' => '',
                'zip_code' => '',
            ]
        ];
        
        try {
            $response = Http::withToken($accessToken)
                ->post($this->baseUrl . '/api/Transactions/SubmitOrderRequest', $payload);
            
            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'order_tracking_id' => $data['order_tracking_id'] ?? null,
                    'merchant_reference' => $data['merchant_reference'] ?? null,
                    'redirect_url' => $data['redirect_url'] ?? null,
                ];
            }
            
            Log::error('Pesapal Order Request Error', ['response' => $response->body()]);
            
            return [
                'success' => false,
                'message' => 'Failed to create Pesapal order',
            ];
        } catch (\Exception $e) {
            Log::error('Pesapal Order Request Exception', ['error' => $e->getMessage()]);
            
            return [
                'success' => false,
                'message' => 'Payment service error: ' . $e->getMessage(),
            ];
        }
    }
    
    /**
     * Get transaction status
     */
    public function getTransactionStatus($orderTrackingId)
    {
        $accessToken = $this->getAccessToken();
        
        if (!$accessToken) {
            return [
                'success' => false,
                'message' => 'Failed to get access token'
            ];
        }
        
        try {
            $response = Http::withToken($accessToken)
                ->get($this->baseUrl . '/api/Transactions/GetTransactionStatus', [
                    'orderTrackingId' => $orderTrackingId,
                ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'data' => $data,
                    'status' => $data['payment_status_description'] ?? 'Unknown',
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Failed to get transaction status',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
