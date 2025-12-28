<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display the Book Now page.
     */
    public function bookNow(): View
    {
        return view('customer.book-now');
    }

    /**
     * Display the Bookings page.
     */
    public function bookings(): View
    {
        return view('customer.bookings');
    }

    /**
     * Display the Rewards page.
     */
    public function rewards(): View
    {
        return view('customer.rewards');
    }

    /**
     * Display the FAQ page.
     */
    public function faq(): View
    {
        return view('customer.faq');
    }
}
