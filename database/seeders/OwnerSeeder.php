<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OwnerSeeder extends Seeder
{
    public function run()
    {
        $owners = [
            [
                'ownerIC' => '050716040127',
                'ownerName' => 'Bang Chan',
                'ownerEmail' => 'bangchan@gmail.com',
                'ownerPhoneNum' => '01111030080',
            ],
            [
                'ownerIC' => '930204014449',
                'ownerName' => 'Hwang Hyunjin',
                'ownerEmail' => 'hwanghyunjin@gmail.com',
                'ownerPhoneNum' => '0192234318',
            ],
            [
                'ownerIC' => '000101030000',
                'ownerName' => 'Umar Rusyad',
                'ownerEmail' => 'umarrusyad@gmail.com',
                'ownerPhoneNum' => '0172652510',
            ],
            [
                'ownerIC' => '991231149999',
                'ownerName' => 'Nurin Iffah',
                'ownerEmail' => 'nuriniffah@gmail.com',
                'ownerPhoneNum' => '01135571415',
            ],
            [
                'ownerIC' => '010923106767',
                'ownerName' => 'Syarah Aqilah',
                'ownerEmail' => 'syarahaqilah@gmail.com',
                'ownerPhoneNum' => '01173676731',
            ],
        ];

        foreach ($owners as $owner) {
            DB::table('owner')->updateOrInsert(
                ['ownerIC' => $owner['ownerIC']], // Check for existing record
                array_merge($owner, [
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ])
            );
        }
    }
}