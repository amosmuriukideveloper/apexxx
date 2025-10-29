<?php

namespace App\Services;

use App\Settings\PaymentSettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MpesaService
{
    protected $paymentSettings;
    protected $consumerKey;
    protected $consumerSecret;
    protected $shortcode;
    protected $passkey;
    protected $callbackUrl;
    protected $environment;
    
    public function __construct()
    {
        $this->paymentSettings = app(PaymentSettings::class);
        $this->consumerKey = $this->paymentSettings->mpesa_consumer_key;
        $this->consumerSecret = $this->paymentSettings->mpesa_consumer_secret;
        $this->shortcode = $this->paymentSettings->mpesa_shortcode;
        $this->passkey = $this->paymentSettings->mpesa_passkey;
        $this->callbackUrl = $this->paymentSettings->mpesa_callback_url ?? url('/api/mpesa/callback');
        $this->environment = $this->paymentSettings->mpesa_environment ?? 'sandbox';
    }
    
    /**
     * Get M-Pesa access token
     */
    protected function getAccessToken()
    {
        $url = $this->environment === 'production' 
            ? 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'
            : 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        
        try {
            $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
                ->get($url);
            
            if ($response->successful()) {
                return $response->json()['access_token'];
            }
            
            Log::error('M-Pesa Access Token Error', ['response' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error('M-Pesa Access Token Exception', ['error' => $e->getMessage()]);
            return null;
        }
    }
    
    /**
     * Initiate STK Push
     */
    public function initiateSTKPush($phoneNumber, $amount, $accountReference, $transactionDesc = 'Payment')
    {
        $accessToken = $this->getAccessToken();
        
        if (!$accessToken) {
            return [
                'success' => false,
                'message' => 'Failed to get M-Pesa access token'
            ];
        }
        
        $url = $this->environment === 'production'
            ? 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest'
            : 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        
        $timestamp = now()->format('YmdHis');
        $password = base64_encode($this->shortcode . $this->passkey . $timestamp);
        
        $payload = [
            'BusinessShortCode' => $this->shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => (int) $amount,
            'PartyA' => $phoneNumber,
            'PartyB' => $this->shortcode,
            'PhoneNumber' => $phoneNumber,
            'CallBackURL' => $this->callbackUrl,
            'AccountReference' => $accountReference,
            'TransactionDesc' => $transactionDesc,
        ];
        
        try {
            $response = Http::withToken($accessToken)
                ->post($url, $payload);
            
            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'message' => $data['CustomerMessage'] ?? 'STK Push sent successfully',
                    'checkout_request_id' => $data['CheckoutRequestID'] ?? null,
                    'merchant_request_id' => $data['MerchantRequestID'] ?? null,
                ];
            }
            
            Log::error('M-Pesa STK Push Error', ['response' => $response->body()]);
            
            return [
                'success' => false,
                'message' => 'Failed to initiate M-Pesa payment. ' . ($response->json()['errorMessage'] ?? 'Please try again.'),
            ];
        } catch (\Exception $e) {
            Log::error('M-Pesa STK Push Exception', ['error' => $e->getMessage()]);
            
            return [
                'success' => false,
                'message' => 'Payment service error: ' . $e->getMessage(),
            ];
        }
    }
    
    /**
     * Query STK Push transaction status
     */
    public function queryTransaction($checkoutRequestId)
    {
        $accessToken = $this->getAccessToken();
        
        if (!$accessToken) {
            return ['success' => false, 'message' => 'Failed to get access token'];
        }
        
        $url = $this->environment === 'production'
            ? 'https://api.safaricom.co.ke/mpesa/stkpushquery/v1/query'
            : 'https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query';
        
        $timestamp = now()->format('YmdHis');
        $password = base64_encode($this->shortcode . $this->passkey . $timestamp);
        
        $payload = [
            'BusinessShortCode' => $this->shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'CheckoutRequestID' => $checkoutRequestId,
        ];
        
        try {
            $response = Http::withToken($accessToken)
                ->post($url, $payload);
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Failed to query transaction',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
