<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiTestController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function success(): JsonResponse
    {
        return response()->json(['status' => 'success']);
    }

    /**
     * @return JsonResponse
     */
    public function error(): JsonResponse
    {
        return response()->json(['status' => 'error'], 500);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function exception(): JsonResponse
    {
        throw new Exception('This is a test exception');
    }
}
