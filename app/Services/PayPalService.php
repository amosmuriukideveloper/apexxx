<?php

namespace App\Services;

use App\Settings\PaymentSettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    protected $paymentSettings;
    protected $clientId;
    protected $clientSecret;
    protected $environment;
    protected $baseUrl;
    
    public function __construct()
    {
        $this->paymentSettings = app(PaymentSettings::class);
        $this->clientId = $this->paymentSettings->paypal_client_id;
        $this->clientSecret = $this->paymentSettings->paypal_client_secret;
        $this->environment = $this->paymentSettings->paypal_environment ?? 'sandbox';
        $this->baseUrl = $this->environment === 'production'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }
    
    /**
     * Get PayPal access token
     */
    protected function getAccessToken()
    {
        try {
            $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
                ->asForm()
                ->post($this->baseUrl . '/v1/oauth2/token', [
                    'grant_type' => 'client_credentials'
                ]);
            
            if ($response->successful()) {
                return $response->json()['access_token'];
            }
            
            Log::error('PayPal Access Token Error', ['response' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error('PayPal Access Token Exception', ['error' => $e->getMessage()]);
            return null;
        }
    }
    
    /**
     * Create payment order
     */
    public function createOrder($amount, $currency, $reference, $returnUrl, $cancelUrl)
    {
        $accessToken = $this->getAccessToken();
        
        if (!$accessToken) {
            return [
                'success' => false,
                'message' => 'Failed to get PayPal access token'
            ];
        }
        
        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => $reference,
                    'amount' => [
                        'currency_code' => $currency ?? 'USD',
                        'value' => number_format($amount, 2, '.', ''),
                    ],
                    'description' => 'Payment for ' . $reference,
                ]
            ],
            'application_context' => [
                'return_url' => $returnUrl,
                'cancel_url' => $cancelUrl,
                'brand_name' => 'Scholars Quiver',
                'landing_page' => 'BILLING',
                'user_action' => 'PAY_NOW',
            ]
        ];
        
        try {
            $response = Http::withToken($accessToken)
                ->post($this->baseUrl . '/v2/checkout/orders', $payload);
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Find the approval URL
                $approvalUrl = null;
                foreach ($data['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        $approvalUrl = $link['href'];
                        break;
                    }
                }
                
                return [
                    'success' => true,
                    'order_id' => $data['id'],
                    'approval_url' => $approvalUrl,
                ];
            }
            
            Log::error('PayPal Order Creation Error', ['response' => $response->body()]);
            
            return [
                'success' => false,
                'message' => 'Failed to create PayPal order',
            ];
        } catch (\Exception $e) {
            Log::error('PayPal Order Creation Exception', ['error' => $e->getMessage()]);
            
            return [
                'success' => false,
                'message' => 'Payment service error: ' . $e->getMessage(),
            ];
        }
    }
    
    /**
     * Capture payment for order
     */
    public function captureOrder($orderId)
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
                ->post($this->baseUrl . "/v2/checkout/orders/{$orderId}/capture");
            
            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'data' => $data,
                    'status' => $data['status'],
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Failed to capture payment',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
