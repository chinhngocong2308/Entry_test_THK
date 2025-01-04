<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use DateTime;

/**
 * Class BookingSeeder
 * @package Database\Seeders
 */
class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('bookings')->truncate();
        Schema::enableForeignKeyConstraints();

        $booking_data = [];

        $hotel_ids = DB::table('hotels')->pluck('hotel_id')->toArray();

        $customer_names = [
            '山田 太郎',
            '佐藤 花子',
            '鈴木 一郎',
            '田中 美咲',
            '高橋 健太',
        ];

        $customer_contacts = [
            '090-1234-5678',
            '080-9876-5432',
            '070-5678-1234',
            '050-4321-8765',
            '090-1111-2222',
        ];

        foreach ($hotel_ids as $hotel_id) {
            foreach ($customer_names as $key => $customer_name) {
                $checkin_time = new DateTime('+' . rand(1, 30) . ' days');
                $checkout_time = clone $checkin_time;
                $checkout_time->modify('+1 day');

                $booking_data[] = [
                    'hotel_id' => $hotel_id,
                    'customer_name' => $customer_name,
                    'customer_contact' => $customer_contacts[$key % count($customer_contacts)],
                    'checkin_time' => $checkin_time,
                    'checkout_time' => $checkout_time,
                    'created_at' => new DateTime(),
                    'updated_at' => new DateTime(),
                ];
            }
        }

        foreach ($booking_data as $value) {
            DB::table('bookings')->insert($value);
        }
    }
}
