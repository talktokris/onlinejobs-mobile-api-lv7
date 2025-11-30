<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;

class PositionController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Option::select('id', 'name')->where(['status' => '1', 'type' => 'Position Name'])->get(),
            'message' => 'Position data fetch success'
        ], 200);
    }
}