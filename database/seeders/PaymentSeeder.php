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
        // Get the first customer
        $customer = Customer::first();
        
        if (!$customer) {
            echo "⚠️  No customers found. Please create a customer first.\n";
            return;
        }

        echo "Creating test data...\n";

        // Create bookings and payments for the last 7 days
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::today()->subDays($i);
            
            // Create 2-4 bookings per day
            $bookingsPerDay = rand(2, 4);
            
            for ($j = 0; $j < $bookingsPerDay; $j++) {
                $bookingID = 'BK' . $date->format('Ymd') . str_pad($j + 1, 3, '0', STR_PAD_LEFT);
                $totalPrice = rand(400, 1200);
                
                // Create booking
                Booking::create([
                    'bookingID' => $bookingID,
                    'matricNum' => $customer->matricNum,
                    'destination' => 'Kuala Lumpur',
                    'pickupDate' => $date->copy()->setTime(10, 0),
                    'returnDate' => $date->copy()->addDays(2)->setTime(10, 0),
                    'pickupLoc' => 'UTM Johor',
                    'returnLoc' => 'UTM Johor',
                    'deposit' => 200,
                    'totalPrice' => $totalPrice,
                    'bookingStat' => 'completed',
                ]);

                // Create payment
                Payment::create([
                    'paymentID' => 'PAY' . $bookingID,
                    'bookingID' => $bookingID,
                    'paymentStatus' => 'completed',
                    'method' => ['Credit Card', 'Online Banking', 'Cash'][rand(0, 2)],
                    'paymentDate' => $date->copy()->setTime(rand(9, 17), rand(0, 59)),
                    'amount' => $totalPrice,
                    'discountedPrice' => 0,
                    'grandTotal' => $totalPrice,
                ]);
                
                echo "✓ Created: {$bookingID} - RM {$totalPrice}\n";
            }
        }

        echo "\n✅ Done! Created bookings and payments for 7 days.\n";
    }
}