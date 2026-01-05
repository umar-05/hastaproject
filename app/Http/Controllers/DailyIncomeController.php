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

        // Prefer payments for income; fallback to bookings when payments are not present
        $todayIncome = Payment::whereDate('paymentDate', $today)
            ->where('paymentStatus', 'completed')
            ->sum('grandTotal');

        $yesterdayIncome = Payment::whereDate('paymentDate', $yesterday)
            ->where('paymentStatus', 'completed')
            ->sum('grandTotal');

        // If there are no payments for the day, use completed bookings totals as fallback
        if ($todayIncome == 0) {
            $todayIncome = Booking::whereDate('pickupDate', $today)
                ->where('bookingStat', 'completed')
                ->sum('totalPrice');
        }

        if ($yesterdayIncome == 0) {
            $yesterdayIncome = Booking::whereDate('pickupDate', $yesterday)
                ->where('bookingStat', 'completed')
                ->sum('totalPrice');
        }

        // Calculate change percentage
        $change = $yesterdayIncome > 0 
            ? (($todayIncome - $yesterdayIncome) / $yesterdayIncome) * 100 
            : 0;

        // Get transaction count for today (payments preferred, bookings if none)
        $transactionCount = Payment::whereDate('paymentDate', $today)
            ->where('paymentStatus', 'completed')
            ->count();

        if ($transactionCount == 0) {
            $transactionCount = Booking::whereDate('pickupDate', $today)
                ->where('bookingStat', 'completed')
                ->count();
        }

        // Get last 7 days income for chart (payments preferred, then bookings)
        $dailyIncomeChart = Payment::select(
                DB::raw('DATE(paymentDate) as date'),
                DB::raw('SUM(grandTotal) as total')
            )
            ->where('paymentDate', '>=', Carbon::now()->subDays(6))
            ->where('paymentStatus', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        if ($dailyIncomeChart->isEmpty()) {
            $dailyIncomeChart = Booking::select(
                    DB::raw('DATE(pickupDate) as date'),
                    DB::raw('SUM(totalPrice) as total')
                )
                ->where('pickupDate', '>=', Carbon::now()->subDays(6))
                ->where('bookingStat', 'completed')
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        }

        // Get last 7 days bookings trend
        $bookingsTrend = Booking::select(
                DB::raw('DATE(pickupDate) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('pickupDate', '>=', Carbon::now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get recent transactions (payments with booking details), fallback to recent bookings
        $recentPayments = Payment::with(['booking.customer'])
            ->where('paymentStatus', 'completed')
            ->latest('paymentDate')
            ->take(4)
            ->get();

        if ($recentPayments->isEmpty()) {
            $recentTransactions = Booking::with('customer')
                ->where('bookingStat', 'completed')
                ->latest('pickupDate')
                ->take(4)
                ->get()
                ->map(function($booking) {
                    return [
                        'booking_id' => $booking->bookingID,
                        'customer' => $booking->customer->name ?? 'N/A',
                        'amount' => $booking->totalPrice,
                        'date' => $booking->pickupDate ? $booking->pickupDate->format('Y-m-d') : null,
                        'time' => $booking->pickupDate ? $booking->pickupDate->format('H:i') : null,
                        'payment_method' => 'N/A'
                    ];
                });
        } else {
            $recentTransactions = $recentPayments->map(function($payment) {
                return [
                    'booking_id' => $payment->bookingID,
                    'customer' => $payment->booking->customer->name ?? 'N/A',
                    'amount' => $payment->grandTotal,
                    'date' => $payment->paymentDate ? $payment->paymentDate->format('Y-m-d') : null,
                    'time' => $payment->paymentDate ? $payment->paymentDate->format('H:i') : null,
                    'payment_method' => $payment->method
                ];
            });
        }

        return view('staff.reports.dailyincome.index', compact(
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