@extends('admin.hotel.search')

@section('search_results')
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
    @elseif(!empty($hotelName) && $hotels->count() === 0)
        <p class="no-results">検索結果が見つかりませんでした。</p>
    @endif
@endsection
