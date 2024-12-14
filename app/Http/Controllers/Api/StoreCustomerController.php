<?php

namespace App\Http\Controllers\Api;

use App\Events\CustomerReceived;
use App\Http\Controllers\Controller;
use App\Models\Trigger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StoreCustomerController extends Controller
{
    public static function getCustomers()
    {
        $url = env('TRIGGER_URL')."/api/webhooks";
        $callbackUrl = "http://localhost:8000/api/handle-customers";

        $payload = [
            'event' => 'get-user',
            'store_id' => '1',
            'callback_url' => $callbackUrl,
        ];

        $response = Http::post($url, $payload);
        
        if ($response->successful()) {
            $data = json_decode($response->body());

            $trigger = new Trigger();
            $trigger->run_id = $data->id;
            $trigger->event = $data->event ?? $payload['data']['event'];
            $trigger->save();

            return response()->json([
                'msg' => 'Trigger save successfully'
            ], 200);
        } else {
            return response()->json([
                'msg' => 'Error happen'
            ], 400);
        }
    }

    public function handleCustomers(Request $request)
    {
        if (count($request->all())) {
            $data = $request->all();

            $customers = $data['data'];

            broadcast(new CustomerReceived($customers));

            $trigger = Trigger::where('run_id', $data['id'])->firstOrFail();
            $trigger->status = 'COMPLETED';
            $trigger->save();

            return response()->json([
                'msg' => 'Successful get customers'
            ], 200);
        } else {
            return response()->json([
                'msg' => 'No data founded'
            ], 204);
        }
    }
}
