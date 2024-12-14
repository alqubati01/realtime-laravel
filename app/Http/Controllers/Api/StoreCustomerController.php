<?php

namespace App\Http\Controllers\Api;

use App\Events\CustomerReceived;
use App\Http\Controllers\Controller;
use App\Models\Trigger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

            return ['msg' => 'trigger save successful', 'status' => 200];
        } else {
            return ['msg' => 'failed to get data', 'status' => 404];
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
                'msg' => 'successful get customers'
            ], 200);
        } else {
            return response()->json([
                'msg' => 'no data founded'
            ], 200);
        }


        // $customers = [
        //     ['name' => 'amgad', 'email' => 'amgad@gmail.com', 'phone_number' => '777444555'],
        //     ['name' => 'ali', 'email' => 'ali@gmail.com', 'phone_number' => '777888999'],
        //     ['name' => 'ahmed', 'email' => 'ahmed@gmail.com', 'phone_number' => '777222333'],
        //     ['name' => 'arwa', 'email' => 'arwa@gmail.com', 'phone_number' => '777555444'],
        // ];

        // $customers = $data->data;
        // \Log::info($customers);
        // session()->put(['customers' => $customers]);

        // return new StreamedResponse(function () use($customers) {
        //     while (true) {

        //         $data = session('customers', $customers);

        //         // sleep(7);

        //         if ($data) {
        //             echo "data: " . json_encode([
        //                 'time' => now(),
        //                 'customers' => $data
        //             ]) . "\n\n";
        //             ob_flush();
        //             flush();
        //             break;
        //         }

        //         // Sleep to limit frequency (e.g., send every second)
        //         // sleep(1);
        //     }
        // }, 200, [
        //     'Content-Type' => 'text/event-stream',
        //     'Cache-Control' => 'no-cache',
        //     'Connection' => 'keep-alive',
        // ]);
    }
}
