<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DailyIncomeController extends Controller
{
    public function index()
    {
        // Get today's and yesterday's income
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        $todayIncome = Payment::whereDate('paymentDate', $today)
            ->where('paymentStatus', 'completed')
            ->sum('grandTotal');

        $yesterdayIncome = Payment::whereDate('paymentDate', $yesterday)
            ->where('paymentStatus', 'completed')
            ->sum('grandTotal');

        // Calculate change percentage
        $change = $yesterdayIncome > 0 
            ? (($todayIncome - $yesterdayIncome) / $yesterdayIncome) * 100 
            : 0;

        // Get transaction count for today
        $transactionCount = Payment::whereDate('paymentDate', $today)
            ->where('paymentStatus', 'completed')
            ->count();

        // Get last 7 days income for chart
        $dailyIncomeChart = Payment::select(
                DB::raw('DATE(paymentDate) as date'),
                DB::raw('SUM(grandTotal) as total')
            )
            ->where('paymentDate', '>=', Carbon::now()->subDays(6))
            ->where('paymentStatus', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get last 7 days bookings trend
        $bookingsTrend = Booking::select(
                DB::raw('DATE(pickupDate) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('pickupDate', '>=', Carbon::now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get recent transactions (payments with booking details)
        $recentTransactions = Payment::with(['booking.customer'])
            ->where('paymentStatus', 'completed')
            ->latest('paymentDate')
            ->take(4)
            ->get()
            ->map(function($payment) {
                return [
                    'booking_id' => $payment->bookingID,
                    'customer' => $payment->booking->customer->name ?? 'N/A',
                    'amount' => $payment->grandTotal,
                    'date' => $payment->paymentDate->format('Y-m-d'),
                    'time' => $payment->paymentDate->format('H:i'),
                    'payment_method' => $payment->method
                ];
            });

        return view('report.daily-income.index', compact(
            'todayIncome',
            'yesterdayIncome',
            'change',
            'transactionCount',
            'dailyIncomeChart',
            'bookingsTrend',
            'recentTransactions'
        ));
    }
}