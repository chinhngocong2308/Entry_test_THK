<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

/**
 * Class BookingController
 * @package App\Http\Controllers\Admin
 */
class BookingController extends Controller
{
    /**
     * Show the booking search screen.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showSearch(Request $request)
    {
        // Fetch all bookings for initial display (default pagination)
        $bookings = Booking::with('hotel')->orderBy('booking_id', 'desc')->paginate(10);

        return view('admin.booking.search', compact('bookings'));
    }

    /**
     * Handle search functionality for bookings.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function searchResult(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'customer_contact' => 'nullable|string|max:255',
            'checkin_time' => 'nullable|date',
            'checkout_time' => 'nullable|date|after_or_equal:checkin_time',
        ], [
            'checkout_time.after_or_equal' => 'チェックアウト日時はチェックイン日時と同じかそれ以降でなければなりません。', // Custom error message
        ]);

        // Lấy giá trị tìm kiếm sau khi validate
        $customerName = $request->input('customer_name');
        $customerContact = $request->input('customer_contact');
        $checkinTime = $request->input('checkin_time');
        $checkoutTime = $request->input('checkout_time');

        $query = Booking::query();

        if (!empty($customerName)) {
            $query->where('customer_name', 'like', '%' . $customerName . '%');
        }

        if (!empty($customerContact)) {
            $query->where('customer_contact', 'like', '%' . $customerContact . '%');
        }

        if (!empty($checkinTime)) {
            $query->where('checkin_time', '>=', $checkinTime);
        }

        if (!empty($checkoutTime)) {
            $query->where('checkout_time', '<=', $checkoutTime);
        }

        $bookings = $query->orderBy('booking_id', 'desc')->paginate(10);

        return view('admin.booking.result', compact(
            'bookings',
            'customerName',
            'customerContact',
            'checkinTime',
            'checkoutTime'
        ));
    }

}
