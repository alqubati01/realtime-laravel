<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\StoreCustomerController;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $response = StoreCustomerController::getCustomers();
        
        if ($response->status() == 200) {
            return inertia(
                'Customers/Index',
                [
                    'message' => 'Customers',
                ]
            );
        } else {
            return inertia(
                'Customers/Index',
                [
                    'failedData' => true,
                    'message' => 'Customers',
                ]
            );
        }
    }
}
