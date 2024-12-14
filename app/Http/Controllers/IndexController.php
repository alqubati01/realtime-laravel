<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $message = 'Hello Customer';
        
        return inertia(
            'Index/Index',
            [
                'message' => $message,
            ]   
        );
    }

    public function show()
    {
        return inertia('Index/Show');
    }
}
