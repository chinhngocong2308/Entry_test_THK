<!-- base view -->
@extends('common.admin.base')

<!-- CSS per page -->
@section('custom_css')
    @vite('resources/scss/admin/search.scss')
@endsection

<!-- main contents -->
@section('main_contents')
    <div class="search-page-wrapper">
        <h2 class="title">予約情報検索</h2>
        @if (session('success'))
            <div class="alert alert-success" id="success-alert">
                {{ session('success') }}
            </div>
        @endif
        <hr>
        <!-- Search Form -->
        <div class="search-booking">
            <form action="{{ route('adminBookingSearchResult') }}" method="get">
                <input type="text" name="customer_name" value="{{ $customerName ?? '' }}" placeholder="顧客名を入力してください">
                <input type="text" name="customer_contact" value="{{ $customerContact ?? '' }}" placeholder="顧客連絡先を入力してください">
                <input type="datetime-local" name="checkin_time" value="{{ $checkinTime ?? '' }}" placeholder="チェックイン日時を選択してください">
                <input type="datetime-local" name="checkout_time" value="{{ $checkoutTime ?? '' }}" placeholder="チェックアウト日時を選択してください">
                <button id="submit-search" type="submit">検索</button>
                <button id="reset-search" type="button">リセット</button>
                @if ($errors->has('checkin_time'))
                    <span class="error-message">{{ $errors->first('checkin_time') }}</span>
                @endif
                @if ($errors->has('checkout_time'))
                    <span class="error-message">{{ $errors->first('checkout_time') }}</span>
                @endif
            </form>

            <!-- Error Message -->
        </div>
        <hr>
        <!-- Search Results -->
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
    </div>
@endsection
<!-- JS per page -->
@section('page_js')
    <script>
        $(document).ready(function() {
            const form = $('.search-booking form');
            const customerNameInput = $('input[name="customer_name"]');
            const customerContactInput = $('input[name="customer_contact"]');
            const checkinTimeInput = $('input[name="checkin_time"]');
            const checkoutTimeInput = $('input[name="checkout_time"]');
            const searchBookingDiv = $('.search-booking');

            const successAlert = $('#success-alert');
            if (successAlert.length) {
                setTimeout(() => {
                    successAlert.hide();
                }, 3000);
            }

            form.on('submit', function(event) {
                const existingError = $('#error-message');
                if (existingError.length) {
                    existingError.remove();
                }

                const customerName = customerNameInput.val().trim();
                const customerContact = customerContactInput.val().trim();
                const checkinTime = checkinTimeInput.val();
                const checkoutTime = checkoutTimeInput.val();

                if (!customerName && !customerContact && !checkinTime && !checkoutTime) {
                    event.preventDefault();

                    const errorMessage = $('<p>', {
                        class: 'error-message',
                        id: 'error-message',
                        text: '少なくとも1つの検索条件を入力してください'
                    });

                    searchBookingDiv.append(errorMessage);

                    setTimeout(() => {
                        errorMessage.remove();
                    }, 2500);
                }
            });

            $(document).on('click', '.page-link', function(event) {
                event.preventDefault();

                const pageUrl = $(this).attr('href');
                const url = new URL(pageUrl);
                const page = url.searchParams.get('page');

                const queryParams = new URLSearchParams(window.location.search);

                if (page) {
                    queryParams.set('page', page);
                }

                window.location.href = `${window.location.pathname}?${queryParams.toString()}`;
            });
            $('#reset-search').on('click', function() {
                customerNameInput.val('');
                customerContactInput.val('');
                checkinTimeInput.val('');
                checkoutTimeInput.val('');

                window.location.href = window.location.pathname;
            });

        });
    </script>
@endsection
