<!-- base view -->
@extends('common.admin.base')

<!-- CSS per page -->
@section('custom_css')
    @vite('resources/scss/admin/search.scss')
@endsection

<!-- main contents -->
@section('main_contents')
    <div class="search-page-wrapper">
        <h2 class="title">ホテル検索</h2>
        @if (session('success'))
            <div class="alert alert-success" id="success-alert">
                {{ session('success') }}
            </div>
        @endif
        <hr>
        <!-- Search Form -->
        <div class="search-hotel-name">
            <form action="{{ route('adminHotelSearchResult') }}" method="get">
                <input type="text" name="search_value" value="{{ $searchValue ?? '' }}" placeholder="ホテル名を入力してください">

                <select name="prefecture_id" class="select2">
                    <option value="">すべての地域</option>
                    @foreach ($prefectures as $prefecture)
                        <option value="{{ $prefecture->prefecture_id }}"
                            {{ old('prefecture_id', $prefectureId ?? '') == $prefecture->prefecture_id ? 'selected' : '' }}>
                            {{ $prefecture->prefecture_name }}
                        </option>
                    @endforeach
                </select>
                <button id="submit-search" type="submit">検索</button>
                <button id="reset-search" type="button">リセット</button>
            </form>

            <!-- Error Message -->
        </div>
        <hr>
        <!-- Search Results -->
        @if ($hotels->count() > 0)
            <div class="search-page-wrapper-results">
                <div class="search-result">

                    <div class="search-results">
                        <h3 class="subtitle">検索結果</h3>
                        <table class="hotel-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ホテル名</th>
                                    <th>地域</th>
                                    <th>画像</th>
                                    <th>アクション</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hotels as $hotel)
                                    <tr>
                                        <td>{{ $hotel->hotel_id }}</td>
                                        <td>{{ $hotel->hotel_name }}</td>
                                        <td>{{ $hotel->prefecture->prefecture_name ?? 'N/A' }}</td>
                                        <td>
                                            @if ($hotel->file_path)
                                                <img src="{{ asset('assets/img/' . $hotel->file_path) }}"
                                                    alt="{{ $hotel->hotel_name }}" class="hotel-thumbnail"
                                                    style="max-width: 100px;">
                                            @else
                                                <span>画像なし</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('adminHotelEditPage', ['hotel_id' => $hotel->hotel_id]) }}"
                                                class="btn-edit">編集</a>
                                            <form
                                                action="{{ route('adminHotelDeleteProcess', ['hotel_id' => $hotel->hotel_id]) }}"
                                                method="post" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-delete"
                                                    onclick="return confirm('本当に削除しますか？')">削除</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="pagination-wrapper">
                        {{ $hotels->links() }}
                    </div>

                </div>
            </div>
        @elseif(!empty($searchValue) && $hotels->count() === 0)
            <p class="no-results">検索結果が見つかりませんでした。</p>
        @endif
    </div>
@endsection
<!-- JS per page -->
@section('page_js')
    <script>
        $(document).ready(function() {
            const form = $('.search-hotel-name form');
            const searchValueInput = $('input[name="search_value"]');
            const prefectureSelect = $('select[name="prefecture_id"]');
            const searchHotelNameDiv = $('.search-hotel-name');

            const successAlert = $('#success-alert');
            if (successAlert.length) {
                setTimeout(() => {
                    successAlert.hide();
                }, 3000);
            }
            if (window.location.pathname === '/admin/hotel/search') {
                form.on('submit', function(event) {
                    const existingError = $('#error-message');
                    if (existingError.length) {
                        existingError.remove();
                    }

                    const hotelName = searchValueInput.val().trim();
                    const prefectureId = prefectureSelect.val();

                    if (!hotelName && !prefectureId) {
                        event.preventDefault();

                        const errorMessage = $('<p>', {
                            class: 'error-message',
                            id: 'error-message',
                            text: 'ホテル名または地域を選択してください'
                        });

                        searchHotelNameDiv.append(errorMessage);

                        setTimeout(() => {
                            errorMessage.remove();
                        }, 2500);
                    }
                });
            } else {
                form.on('submit', function(event) {
                    event.preventDefault();

                    const hotelName = searchValueInput.val().trim();
                    const prefectureId = prefectureSelect.val();

                    if (!hotelName && !prefectureId) {
                        const existingError = $('#error-message');
                        if (existingError.length) {
                            existingError.remove();
                        }

                        const errorMessage = $('<p>', {
                            class: 'error-message',
                            id: 'error-message',
                            text: 'ホテル名または地域を選択してください'
                        });

                        searchHotelNameDiv.append(errorMessage);

                        setTimeout(() => {
                            errorMessage.remove();
                        }, 2500);
                        return;
                    }

                    const queryParams = new URLSearchParams(window.location.search);
                    queryParams.delete('page');

                    if (hotelName) {
                        queryParams.set('search_value', hotelName);
                    } else {
                        queryParams.delete('search_value');
                    }
                    if (prefectureId) {
                        queryParams.set('prefecture_id', prefectureId);
                    } else {
                        queryParams.delete('prefecture_id');
                    }

                    window.location.href = `${window.location.pathname}?${queryParams.toString()}`;
                });
            }

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
                searchValueInput.val('');
                prefectureSelect.val('');

                window.location.href = window.location.pathname;
            });
        });
    </script>
@endsection
