<?php

namespace App\Services;

use App\Models\Sale;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ETimsService
{
    /**
     * Send invoice to eTIMS API
     */
    public function sendInvoice(Sale $sale): array
    {
        try {
            // Get company settings
            $settings = DB::table('settings')->pluck('value', 'key')->toArray();
            
            // Get eTIMS API configuration from environment
            $apiUrl = config('services.etims.api_url', env('ETIMS_API_URL', 'https://etims-api.kra.go.ke/api'));
            $clientId = config('services.etims.client_id', env('ETIMS_CLIENT_ID'));
            $clientSecret = config('services.etims.client_secret', env('ETIMS_CLIENT_SECRET'));
            
            if (!$apiUrl || !$clientId || !$clientSecret) {
                throw new \Exception('eTIMS API configuration is missing. Please configure ETIMS_API_URL, ETIMS_CLIENT_ID, and ETIMS_CLIENT_SECRET in your .env file.');
            }

            // Prepare invoice data according to eTIMS API specification
            $invoiceData = $this->prepareInvoiceData($sale, $settings);

            // Send to eTIMS API
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getAccessToken($clientId, $clientSecret, $apiUrl),
            ])->post($apiUrl . '/invoices', $invoiceData);

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Update sale with eTIMS response if available
                if (isset($responseData['invoiceNumber']) || isset($responseData['uuid'])) {
                    $sale->update([
                        'etims_invoice_number' => $responseData['invoiceNumber'] ?? null,
                        'etims_uuid' => $responseData['uuid'] ?? null,
                        'etims_approval_date' => isset($responseData['approvalDate']) ? now()->parse($responseData['approvalDate']) : null,
                        'etims_verified' => isset($responseData['status']) && $responseData['status'] === 'approved',
                    ]);
                }

                return [
                    'success' => true,
                    'message' => 'Invoice sent to eTIMS successfully',
                    'data' => $responseData,
                ];
            } else {
                $errorMessage = $response->json()['message'] ?? 'Failed to send invoice to eTIMS';
                Log::error('eTIMS API Error', [
                    'sale_id' => $sale->id,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                return [
                    'success' => false,
                    'message' => $errorMessage,
                ];
            }
        } catch (\Exception $e) {
            Log::error('eTIMS Service Error', [
                'sale_id' => $sale->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Error sending invoice to eTIMS: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get access token from eTIMS API
     */
    private function getAccessToken(string $clientId, string $clientSecret, string $apiUrl): string
    {
        // In a real implementation, you might want to cache the token
        // For now, we'll request a new token each time
        $response = Http::asForm()->post($apiUrl . '/auth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
        ]);

        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        throw new \Exception('Failed to obtain eTIMS access token');
    }

    /**
     * Prepare invoice data according to eTIMS API specification
     */
    private function prepareInvoiceData(Sale $sale, array $settings): array
    {
        $sale->load(['customer', 'saleItems.part']);

        // Calculate VAT (16% of subtotal)
        $vatAmount = $sale->subtotal * 0.16;
        $subtotalExcludingVat = $sale->subtotal;
        $totalIncludingVat = $subtotalExcludingVat + $vatAmount;

        // Prepare line items
        $lineItems = [];
        foreach ($sale->saleItems as $item) {
            $itemVatAmount = ($item->subtotal * 0.16);
            $lineItems[] = [
                'itemCode' => $item->part->part_number ?? $item->part->sku ?? '',
                'itemName' => $item->part->name,
                'quantity' => $item->quantity,
                'unitPrice' => round($item->price, 2),
                'taxRate' => 16.00,
                'taxAmount' => round($itemVatAmount, 2),
                'discountAmount' => 0,
                'lineTotal' => round($item->subtotal + $itemVatAmount, 2),
            ];
        }

        // Prepare invoice data
        return [
            'invoiceNumber' => $sale->invoice_number,
            'invoiceDate' => $sale->date->format('Y-m-d\TH:i:s'),
            'sellerPin' => $settings['kra_pin'] ?? '',
            'buyerPin' => $sale->customer && $sale->customer->kra_pin ? $sale->customer->kra_pin : '',
            'buyerName' => $sale->customer ? $sale->customer->name : 'Walk-in Customer',
            'buyerPhone' => $sale->customer && $sale->customer->phone ? $sale->customer->phone : '',
            'buyerEmail' => $sale->customer && $sale->customer->email ? $sale->customer->email : '',
            'currency' => $settings['currency'] ?? 'KES',
            'subTotal' => round($subtotalExcludingVat, 2),
            'taxAmount' => round($vatAmount, 2),
            'discountAmount' => round($sale->discount, 2),
            'totalAmount' => round($totalIncludingVat, 2),
            'items' => $lineItems,
        ];
    }

    /**
     * Handle callback from eTIMS API
     */
    public function handleCallback(array $callbackData): bool
    {
        try {
            // Find the sale by invoice number or UUID
            $sale = Sale::where('invoice_number', $callbackData['invoiceNumber'] ?? '')
                ->orWhere('etims_uuid', $callbackData['uuid'] ?? '')
                ->first();

            if (!$sale) {
                Log::warning('eTIMS callback: Sale not found', ['callback_data' => $callbackData]);
                return false;
            }

            // Update sale with callback data
            $sale->update([
                'etims_invoice_number' => $callbackData['invoiceNumber'] ?? $sale->etims_invoice_number,
                'etims_uuid' => $callbackData['uuid'] ?? $sale->etims_uuid,
                'etims_approval_date' => isset($callbackData['approvalDate']) ? now()->parse($callbackData['approvalDate']) : $sale->etims_approval_date,
                'etims_verified' => isset($callbackData['status']) && $callbackData['status'] === 'approved',
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('eTIMS callback error', [
                'error' => $e->getMessage(),
                'callback_data' => $callbackData,
            ]);
            return false;
        }
    }
}

