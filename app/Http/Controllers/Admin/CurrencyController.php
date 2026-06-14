<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CurrencyController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $currencies = DB::table('currency')->get();
            $base = $currencies->where('is_base', 1)->first();
            $rates = [];
            foreach($currencies as $c) {
                $rates[$c->currency_code] = $c->exchange_rate;
            }
            return response()->json([
                'success' => true,
                'baseCurrency' => $base ? $base->currency_code : 'GBP',
                'rates' => $rates
            ]);
        }
        return view('admin.courses.currencies');
    }

    public function update(Request $request)
    {
        $baseCurrency = $request->input('baseCurrency', 'GBP');
        $rates = $request->input('rates', []);

        DB::transaction(function() use ($baseCurrency, $rates) {
            foreach ($rates as $code => $rate) {
                DB::table('currency')->updateOrInsert(
                    ['currency_code' => $code],
                    [
                        'exchange_rate' => $rate,
                        'is_base' => ($code === $baseCurrency),
                        'updated_at' => now()
                    ]
                );
            }
        });

        return response()->json(['success' => true, 'message' => 'Rates updated successfully']);
    }
}
