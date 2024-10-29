<?php

namespace App\Http\Controllers;

use App\Models\Balance;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function balance(Request $request)
    {
        $userId = $request->user()->id;
        $balance = Balance::where('user_id', $userId)->firstOrFail();

        return response()->json(['balance' => $balance->balance], 200);
    }
}