<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\Admin\TopController as AdminTopController;
use App\Http\Controllers\Admin\HotelController as AdminHotelController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;

/** User Screen */
Route::get('/', [TopController::class, 'index'])->name('top');
Route::get('/{prefecture_name_alpha}/hotellist', [HotelController::class, 'showList'])->name('hotelList');
Route::get('/hotel/{hotel_id}', [HotelController::class, 'showDetail'])->name('hotelDetail');

/** Admin Screen */
Route::prefix('admin')->group(function () {
    Route::get('/', [AdminTopController::class, 'index'])->name('adminTop');
    Route::get('/hotel/search', [AdminHotelController::class, 'showSearch'])->name('adminHotelSearchPage');
    Route::get('/hotel/{hotel_id}/update', [AdminHotelController::class, 'showEdit'])->name('adminHotelEditPage');
    Route::get('/hotel/create', [AdminHotelController::class, 'showCreate'])->name('adminHotelCreatePage');
    Route::get('/booking/search', [AdminBookingController::class, 'showSearch'])->name('adminBookingSearchPage');

    Route::match(['get', 'post'], '/hotel/search/result', [AdminHotelController::class, 'searchResult'])->name('adminHotelSearchResult');
    Route::post('/hotel/{hotel_id}/update', [AdminHotelController::class, 'edit'])->name('adminHotelEditProcess');
    Route::post('/hotel/create', [AdminHotelController::class, 'create'])->name('adminHotelCreateProcess');
    Route::delete('/hotel/{hotel_id}/delete', [AdminHotelController::class, 'delete'])->name('adminHotelDeleteProcess');
    Route::match(['get', 'post'], '/booking/search/result', [AdminBookingController::class, 'searchResult'])->name('adminBookingSearchResult');
});