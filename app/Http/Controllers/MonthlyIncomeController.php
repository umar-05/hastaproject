<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MonthlyIncomeController extends Controller
{
    public function index()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $lastMonth = Carbon::now()->subMonth()->month;

        // ==========================================
        // 1. TOP CARDS DATA (Using Booking Model)
        // ==========================================

        // We use the scopeCompleted() you defined in your Booking model
        
        // A. Current Month Income
        $currentMonthIncome = Booking::completed()
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->sum('totalPrice'); 

        // B. Previous Month Income
        $previousMonthIncome = Booking::completed()
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $lastMonth)
            ->sum('totalPrice');

        // C. Yearly Total
        $yearlyTotal = Booking::completed()
            ->whereYear('created_at', $currentYear)
            ->sum('totalPrice');

        // D. Monthly Average
        $averageMonthly = $currentMonth > 0 ? ($yearlyTotal / $currentMonth) : 0;

        $cards = [
            'current_month' => $currentMonthIncome,
            'previous_month' => $previousMonthIncome,
            'average_monthly' => $averageMonthly,
            'yearly_total' => $yearlyTotal,
        ];

        // ==========================================
        // 2. DONUT CHART (Using Payment Model)
        // ==========================================
        
        // We group payments by 'method' to see which is most popular
        $paymentData = Payment::select('method', DB::raw('count(*) as total'))
            ->whereYear('created_at', $currentYear) // or use paymentDate if you prefer
            ->groupBy('method')
            ->pluck('total', 'method')
            ->toArray();

        // Calculate percentages
        $totalPayments = array_sum($paymentData);
        $paymentMethods = [];

        if($totalPayments == 0) {
            // Fallback if no payments exist yet
            $paymentMethods = ['No Data' => 100]; 
        } else {
            foreach ($paymentData as $method => $count) {
                // Ensure method name is capitalized (e.g., "credit card" -> "Credit Card")
                $formattedName = ucwords($method); 
                $percentage = round(($count / $totalPayments) * 100);
                $paymentMethods[$formattedName] = $percentage;
            }
        }

        // ==========================================
        // 3. TABLE & BAR CHART (Monthly Breakdown)
        // ==========================================
        
        $breakdown = [];
        $previousIncome = 0;

        // Loop through Jan (1) to Dec (12)
        for ($m = 1; $m <= 12; $m++) {
            
            // Get completed bookings for this specific month
            $monthData = Booking::completed()
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $m)
                ->get();

            $income = $monthData->sum('totalPrice');
            $count = $monthData->count();
            
            // Calculate Average per booking
            $avg = $count > 0 ? round($income / $count) : 0;

            // Calculate Growth % compared to last month
            $growth = null;
            if ($m > 1) { 
                if ($previousIncome > 0) {
                    $growth = round((($income - $previousIncome) / $previousIncome) * 100, 1);
                } elseif ($income > 0 && $previousIncome == 0) {
                    $growth = 100; 
                } else {
                    $growth = 0;
                }
            }

            // Add to data array
            $breakdown[] = [
                'month' => Carbon::create()->month($m)->format('M'),
                'year' => $currentYear,
                'income' => $income,
                'bookings' => $count,
                'avg' => $avg,
                'growth' => $growth
            ];

            $previousIncome = $income;
        }

        return view('staff.reports.monthlyincome.index', compact('cards', 'paymentMethods', 'breakdown'));
    }
}