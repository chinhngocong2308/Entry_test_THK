<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\Prefecture;

/**
 * Class HotelController
 * @package App\Http\Controllers\Admin
 */
class HotelController extends Controller
{
    // Frontend

    /**
     * @param $hotel_id
     * @return \Illuminate\Container\Container|mixed|object
     */
    public function showHotelDetails($hotel_id)
    {
        $hotel = Hotel::with('prefecture')->find($hotel_id);
        if (!$hotel) {
            abort(404, 'Hotel not found');
        }

        return view('frontend.hotel.details', compact('hotel'));
    }

    // Admin

    /**
     * @param Request $request
     * @return \Illuminate\Container\Container|mixed|object
     */
    public function showSearch(Request $request)
    {
        $prefectures = Prefecture::all();

        $hotels = Hotel::with('prefecture')->orderBy('hotel_id', 'desc')->paginate(10);
        return view('admin.hotel.search', compact('hotels', 'prefectures'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Container\Container|mixed|object
     */
    public function searchResult(Request $request)
    {
        $searchValue = $request->input('search_value');
        $prefectureId = $request->input('prefecture_id');

        $query = Hotel::with('prefecture');

        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('hotel_name', 'like', '%' . $searchValue . '%')
                  ->orWhereHas('prefecture', function ($q) use ($searchValue) {
                      $q->where('prefecture_name', 'like', '%' . $searchValue . '%');
                  });
            });
        }

        if (!empty($prefectureId)) {
            $query->where('prefecture_id', $prefectureId);
        }

        $hotels = $query->orderBy('hotel_id', 'desc')->paginate(10);
        $prefectures = Prefecture::all();

        return view('admin.hotel.result', compact('hotels', 'searchValue', 'prefectureId', 'prefectures'));
    }


    /**
     * @return \Illuminate\Container\Container|mixed|object
     */
    public function showCreate()
    {
        $prefectures = Prefecture::all();
        return view('admin.hotel.create', compact('prefectures'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'hotel_name' => 'required|string|max:255',
            'prefecture_id' => 'required|exists:prefectures,prefecture_id',
            'file_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('file_path')) {
            $image = $request->file('file_path');
            $destinationPath = public_path('assets/img/hotel');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move($destinationPath, $imageName);

            $validated['file_path'] = 'hotel/' . $imageName;
        }

        Hotel::create($validated);
        return redirect()->route('adminHotelSearchPage')->with('success', 'Hotel created successfully!');
    }


    /**
     * @param $hotel_id
     * @return \Illuminate\Container\Container|mixed|object
     */
    public function showEdit($hotel_id)
    {
        $hotel = Hotel::find($hotel_id);
        if (!$hotel) {
            abort(404, 'Hotel not found');
        }

        $prefectures = Prefecture::all();
        return view('admin.hotel.edit', compact('hotel', 'prefectures'));
    }

    /**
     * @param Request $request
     * @param $hotel_id
     * @return mixed
     */
    public function edit(Request $request, $hotel_id)
    {
        $hotel = Hotel::find($hotel_id);
        if (!$hotel) {
            return redirect()->back()->with('error', 'Hotel not found');
        }

        $validated = $request->validate([
            'hotel_name' => 'required|string|max:255',
            'prefecture_id' => 'required|exists:prefectures,prefecture_id',
            'file_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('file_path')) {
            $image = $request->file('file_path');
            $destinationPath = public_path('assets/img/hotel');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move($destinationPath, $imageName);

            $validated['file_path'] = 'hotel/' . $imageName;
        }

        $hotel->update($validated);

        return redirect()->route('adminHotelEditPage', ['hotel_id' => $hotel_id])
                         ->with('success', 'ホテルが正常に更新されました!');
    }


    /**
     * @param $hotel_id
     * @return mixed
     */
    public function delete($hotel_id)
    {
        $hotel = Hotel::find($hotel_id);

        if (!$hotel) {
            return redirect()->back()->with('error', 'Hotel not found');
        }

        $hotel->delete();

        return redirect()->route('adminHotelSearchPage')->with('success', 'ホテルが正常に削除されました!');
    }
}
