<!-- base view -->
@extends('common.admin.base')

<!-- CSS per page -->
@section('custom_css')
    @vite('resources/scss/admin/hotel/form.scss')
@endsection

<!-- main contents -->
@section('main_contents')
    <div class="edit-page-wrapper">
        <div class="header">
            <h2 class="title">{{ $hotel->hotel_name }}</h2>
            <a href="{{ route('adminHotelSearchPage') }}" class="btn-back">戻る <<<</a>
        </div>
        <hr>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success" id="success-alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="form-container">
            <form action="{{ url('/admin/hotel/' . $hotel->hotel_id . '/update') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="hotel_name">ホテル名</label>
                    <input type="text" name="hotel_name" id="hotel_name" class="form-control"
                        placeholder="Enter hotel name" value="{{ old('hotel_name', $hotel->hotel_name) }}" required>
                </div>

                <div class="form-group">
                    <label for="prefecture_id">県</label>
                    <select name="prefecture_id" id="prefecture_id" class="form-control select2" required>
                        <option value="">-- Select Prefecture --</option>
                        @foreach ($prefectures as $prefecture)
                            <option value="{{ $prefecture->prefecture_id }}"
                                {{ $prefecture->prefecture_id == old('prefecture_id', $hotel->prefecture_id) ? 'selected' : '' }}>
                                {{ $prefecture->prefecture_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="file_path">ホテルイメージ</label>
                    <input type="file" name="file_path" id="file_path" class="form-control" accept="image/*">
                    <div class="image-preview mt-3">
                        @if ($hotel->file_path)
                            <img id="file_path_preview" src="{{ asset('assets/img/' . $hotel->file_path ) }}" alt="Preview Image"
                                class="img-thumbnail">
                        @else
                            <img id="file_path_preview" src="#" alt="Preview Image" class="d-none img-thumbnail">
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-submit" onclick="return confirm('本当に編集しますか?')">更新する</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('page_js')
    <script>
        $(document).ready(function() {
            $('#file_path').on('change', function(event) {
                const file = event.target.files[0];
                const preview = $('#file_path_preview');

                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        preview.attr('src', e.target.result).removeClass('d-none');
                    };

                    reader.readAsDataURL(file); 
                } else {
                    preview.attr('src', '#').addClass('d-none');
                }
            });

            const successAlert = $('#success-alert');
            if (successAlert.length) {
                setTimeout(() => {
                    successAlert.hide();
                }, 3000);
            }
        });

    </script>
@endsection
