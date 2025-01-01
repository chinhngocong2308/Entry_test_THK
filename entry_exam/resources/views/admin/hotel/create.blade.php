<!-- base view -->
@extends('common.admin.base')

<!-- CSS per page -->
@section('custom_css')
    @vite('resources/scss/admin/hotel/create.scss')
@endsection

<!-- main contents -->
@section('main_contents')
    <div class="create-page-wrapper">
        <div class="header">
            <h2 class="title">新しいホテルを作成する</h2>
            <a href="{{ route('adminHotelSearchPage') }}" class="btn-back">戻る <<<</a>
        </div>
        <hr>
        <div class="form-container">
            <form action="{{ url('/admin/hotel/create') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="hotel_name">ホテル名</label>
                    <input type="text" name="hotel_name" id="hotel_name" class="form-control"
                        placeholder="Enter hotel name" required>
                </div>

                <div class="form-group">
                    <label for="prefecture_id">県</label>
                    <select name="prefecture_id" id="prefecture_id" class="form-control select2" required>
                        <option value="">-- Select Prefecture --</option>
                        @foreach ($prefectures as $prefecture)
                            <option value="{{ $prefecture->prefecture_id }}">{{ $prefecture->prefecture_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="file_path">ホテルイメージ</label>
                    <input type="file" name="file_path" id="file_path" class="form-control" accept="image/*">
                    <div class="image-preview mt-3">
                        <img id="file_path_preview" src="#" alt="Preview Image" class="d-none img-thumbnail">
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-submit">作成する</button>
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

                    reader.readAsDataURL(file); // Đọc file ảnh
                } else {
                    preview.attr('src', '#').addClass('d-none');
                }
            });
        });
    </script>
@endsection
