<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Customer;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        // Get or create a test customer
        $customer = Customer::first();
        
        if (!$customer) {
            echo "⚠️  No customers found in database. Please add a customer first.\n";
            return;
        }

        // Create bookings for the last 7 days with payments
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::today()->subDays($i);
            
            // Create 2-4 bookings per day
            $bookingsCount = rand(2, 4);
            
            for ($j = 0; $j < $bookingsCount; $j++) {
                $bookingID = 'BK' . $date->format('Ymd') . str_pad($j + 1, 3, '0', STR_PAD_LEFT);
                
                // Create booking
                $booking = Booking::create([
                    'bookingID' => $bookingID,
                    'matricNum' => $customer->matricNum,
                    'plateNumber' => null,
                    'destination' => 'Kuala Lumpur',
                    'pickupDate' => $date->copy()->addHours(10),
                    'returnDate' => $date->copy()->addDays(2)->addHours(10),
                    'pickupLoc' => 'UTM',
                    'returnLoc' => 'UTM',
                    'deposit' => 200,
                    'totalPrice' => rand(300, 800),
                    'bookingStat' => 'completed',
                ]);

                // Create payment for this booking
                Payment::create([
                    'paymentID' => 'PAY' . $bookingID,
                    'bookingID' => $bookingID,
                    'paymentStatus' => 'completed',
                    'method' => ['Credit Card', 'Online Banking', 'Cash'][rand(0, 2)],
                    'paymentDate' => $date->copy()->addHours(rand(8, 18)),
                    'amount' => $booking->totalPrice,
                    'discountedPrice' => 0,
                    'grandTotal' => $booking->totalPrice,
                ]);
            }
        }

        echo "✅ Successfully created test bookings and payments!\n";
    }
}