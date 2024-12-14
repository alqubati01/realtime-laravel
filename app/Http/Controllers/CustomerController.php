<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\StoreCustomerController;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $response = StoreCustomerController::getCustomers();
        // \Log::info($response);
        if ($response["status"] == 200) {
            // if (session()->has('customers')) {
            //     return inertia(
            //         'Customers/Index',
            //         [
            //             'customersData' => session('customers'),
            //             'message' => 'Hello Customers',
            //         ]
            //     );
            // } else {
                return inertia(
                    'Customers/Index',
                    [
                        'message' => 'Hello Customers',
                    ]
                );
            // }
        } else {
            return inertia(
                'Customers/Index',
                [
                    'failedData' => true,
                    'message' => 'Hello Customers',
                ]
            );
        }
    }
}
