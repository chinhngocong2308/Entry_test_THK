@extends('admin.booking.search')

@section('search_results')
    @if ($bookings->count() > 0)
        <div class="search-page-wrapper-results">
            <div class="search-result">

                <div class="search-results">
                    <h3 class="subtitle">検索結果</h3>
                    <table class="booking-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>顧客名</th>
                                <th>顧客連絡先</th>
                                <th>チェックイン日時</th>
                                <th>チェックアウト日時</th>
                                <th>予約日時</th>
                                <th>情報更新日時</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookings as $booking)
                                <tr>
                                    <td>{{ $booking->booking_id }}</td>
                                    <td>{{ $booking->customer_name }}</td>
                                    <td>{{ $booking->customer_contact }}</td>
                                    <td>{{ $booking->checkin_time }}</td>
                                    <td>{{ $booking->checkout_time }}</td>
                                    <td>{{ $booking->created_at }}</td>
                                    <td>{{ $booking->updated_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $bookings->links() }}
                </div>

            </div>
        </div>
    @elseif((!empty($customerName) || !empty($customerContact) || !empty($checkinTime) || !empty($checkoutTime)) && $bookings->count() === 0)
        <p class="no-results">検索結果が見つかりませんでした。</p>
    @endif
@endsection
