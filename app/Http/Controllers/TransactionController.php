<?php

namespace App\Http\Controllers;

use App\Models\Balance;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric',
            'type' => 'required|in:deposit,withdrawal',
        ]);

        return DB::transaction(function () use ($request) {
            $userId = $request->user_id;
            $amount = $request->amount;
            $type = $request->type;

            $balance = Balance::lockForUpdate()->where('user_id', $userId)->first();

            if ($type === 'withdrawal' && $balance->balance < $amount) {
                return response()->json(['error' => 'Insufficient funds'], 400);
            }

            $transaction = Transaction::create([
                'user_id' => $userId,
                'amount' => $amount,
                'type' => $type,
            ]);

            $balance->balance += $type === 'deposit' ? $amount : -$amount;
            $balance->save();

            return response()->json($transaction, 201);
        });
    }
}