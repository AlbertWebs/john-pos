<?php

namespace App\Http\Controllers;

use App\Services\ETimsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ETimsCallbackController extends Controller
{
    /**
     * Handle callback from eTIMS API
     */
    public function handle(Request $request)
    {
        try {
            $callbackData = $request->all();
            
            Log::info('eTIMS callback received', ['data' => $callbackData]);

            $etimsService = new ETimsService();
            $success = $etimsService->handleCallback($callbackData);

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Callback processed successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to process callback',
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('eTIMS callback error', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error processing callback: ' . $e->getMessage(),
            ], 500);
        }
    }
}

