@extends('layouts.app')
@section('title', 'My Review List')
@section('content')
    <div class="container">
        <div class="row my-5">
            @include('message')
            <div class="col-md-3">
                <div class="card border-0 shadow-lg">
                    <div class="card-header  text-white">
                        Welcome, {{ Auth::user()->name }}
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            @if (Auth::user()->image != '')
                                <img src="{{ asset('uploads/profile/thumb/' . Auth::user()->image) }}"
                                    class="img-fluid rounded-circle" alt="Luna John">
                            @endif
                        </div>
                        <div class="h5 text-center">
                            <strong>{{ Auth::user()->name }}</strong>
                            <p class="h6 mt-2 text-muted">5 Reviews</p>
                        </div>
                    </div>
                </div>
                <div class="card border-0 shadow-lg mt-3">
                    <div class="card-header  text-white">
                        Navigation
                    </div>
                    <div class="card-body sidebar">
                        @include('layouts.sidebar')
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                @include('message')
                <div class="card border-0 shadow">
                    <div class="card-header  text-white">
                        Reviews
                    </div>
                    <div class="card-body pb-0">
                        <div class="d-flex justify-content-end">
                            {{-- <a href="{{ route('reviews.create') }}" class="btn btn-primary">Add Review</a> --}}
                            <form action="" method="get">
                                <div class="d-flex">
                                    <input type="text" value="{{ Request::get('keyword') }}" class="form-control"
                                        placeholder="Keyword" name="keyword">
                                    <button class="btn btn-success ms-2">Search</button>
                                    <a href="{{ route('reviews.list') }}" class="btn btn-secondary ms-2">Clear</a>
                                </div>
                            </form>
                        </div>
                        <table class="table  table-striped mt-3">
                            <thead class="table-dark">
                                <tr>
                                    <th>Book</th>
                                    <th>Review</th>
                                    <th>Rating</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th width="150">Action</th>
                                </tr>
                            <tbody>
                                @if ($reviews->isNotEmpty())
                                    @foreach ($reviews as $review)
                                        <tr>
                                            <td>{{ $review->book->title }}</td>
                                            <td>{{ $review->review }}</td>
                                            <td>{{ $review->rating }}</td>
                                            <td>
                                                @if ($review->status == 1)
                                                    <p class="text-success">Active</p>
                                                @else
                                                    <p class="text-danger">Block</p>
                                                @endif
                                            </td>
                                            <td>{{ Carbon\Carbon::parse($review->created_at)->format('d M,Y') }}</td>
                                            <td>
                                                <a href="javascript:void(0);" onclick="deleteReview({{ $review->id }})"
                                                    class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>
                            </thead>
                        </table>
                        @if ($reviews->isNotEmpty())
                            {{ $reviews->links() }}
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('customJs')
    <script type="text/javascript">
        function deleteReview(id) {
            if (confirm("Are you sure you want to delete")) {
                $.ajax({
                    url: "{{ route('reviews.destroy') }}",
                    type: "delete",
                    data: {
                        id: id
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        window.location.href = '{{ route('reviews.list') }}';
                    }
                })
            }
        }
    </script>
@endsection
