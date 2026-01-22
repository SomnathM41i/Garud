<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MetalRate;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();

        // Check if today's rates already exist
        $gold = MetalRate::where('metal', 'gold')->where('rate_date', $today)->first();
        $silver = MetalRate::where('metal', 'silver')->where('rate_date', $today)->first();

        $apiKey = env('GOLD_API_KEY');
        $cityPremium = 450; // Pune premium per gram
        $gst = 3;           // percent
        $margin = 2;        // percent

        // If not found, call API (only once per day)
        if (!$gold || !$silver) {
            $headers = [
                'x-access-token' => $apiKey,
                'Content-Type' => 'application/json',
            ];

            // GOLD
            $goldRes = Http::withHeaders($headers)->timeout(10)->get("https://www.goldapi.io/api/XAU/INR");
            if ($goldRes->successful()) {
                $goldData = $goldRes->json();
                $pureGold = round($goldData['price_gram_24k'], 2);

                $retailGold = round(($pureGold + $cityPremium) * (1 + ($gst + $margin) / 100), 2);

                $gold = MetalRate::updateOrCreate(
                    ['metal' => 'gold', 'rate_date' => $today],
                    ['rate_per_gram' => $pureGold]
                );
            }

            // SILVER
            $silverRes = Http::withHeaders($headers)->timeout(10)->get("https://www.goldapi.io/api/XAG/INR");
            if ($silverRes->successful()) {
                $silverData = $silverRes->json();
                $pureSilver = round($silverData['price_gram_24k'], 2);

                $retailSilver = round(($pureSilver + $cityPremium / 10) * (1 + ($gst + $margin) / 100), 2);

                $silver = MetalRate::updateOrCreate(
                    ['metal' => 'silver', 'rate_date' => $today],
                    ['rate_per_gram' => $pureSilver]
                );
            }
        } else {
            // Prices already exist in DB
            $pureGold = $gold->rate_per_gram;
            $retailGold = round(($pureGold + $cityPremium) * (1 + ($gst + $margin) / 100), 2);

            $pureSilver = $silver->rate_per_gram;
            $retailSilver = round(($pureSilver + $cityPremium / 10) * (1 + ($gst + $margin) / 100), 2);
        }

        return view('admin.dashboard', compact(
            'pureGold',
            'retailGold',
            'pureSilver',
            'retailSilver'
        ));
    }

    public function refreshRates()
    {
        $today = Carbon::today()->toDateString();
        $apiKey = env('GOLD_API_KEY');

        try {
            $headers = [
                'x-access-token' => $apiKey,
                'Content-Type' => 'application/json',
            ];

            // GOLD
            $goldRes = Http::withHeaders($headers)->timeout(10)->get("https://www.goldapi.io/api/XAU/INR");
            if ($goldRes->successful()) {
                $goldData = $goldRes->json();
                MetalRate::updateOrCreate(
                    ['metal' => 'gold', 'rate_date' => $today],
                    ['rate_per_gram' => round($goldData['price_gram_24k'], 2)]
                );
            }

            // SILVER
            $silverRes = Http::withHeaders($headers)->timeout(10)->get("https://www.goldapi.io/api/XAG/INR");
            if ($silverRes->successful()) {
                $silverData = $silverRes->json();
                MetalRate::updateOrCreate(
                    ['metal' => 'silver', 'rate_date' => $today],
                    ['rate_per_gram' => round($silverData['price_gram_24k'], 2)]
                );
            }

            return back()->with('success', 'Metal rates refreshed successfully.');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->with('error', 'Failed to refresh rates. Try again later.');
        }
    }
}
