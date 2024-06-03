@extends('layouts.app')
@section('title', 'Book List')
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
                        Books
                    </div>
                    <div class="card-body pb-0">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('books.create') }}" class="btn btn-primary">Add Book</a>
                            <form action="" method="get">
                                <div class="d-flex">
                                    <input type="text" value="{{ Request::get('keyword') }}" class="form-control"
                                        placeholder="Keyword" name="keyword">
                                    <button class="btn btn-success ms-2">Search</button>
                                    <a href="{{ route('books.list') }}" class="btn btn-secondary ms-2">Clear</a>
                                </div>
                            </form>
                        </div>
                        <table class="table  table-striped mt-3">
                            <thead class="table-dark">
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Rating</th>
                                    <th>Status</th>
                                    <th width="150">Action</th>
                                </tr>
                            <tbody>
                                @if ($books->isNotEmpty())
                                    @foreach ($books as $book)
                                        <tr>
                                            <td>{{ $book->title }}</td>
                                            <td>{{ $book->author }}</td>
                                            <td>3.0 (3 Reviews)</td>
                                            <td>
                                                @if ($book->status == 1)
                                                    <p class="text-success">Active</p>
                                                @else
                                                    <p class="text-danger">Block</p>
                                                @endif
                                            </td>
                                            <td>
                                                {{-- <a href="#" class="btn btn-success btn-sm"><i
                                                        class="fa-regular fa-star"></i></a> --}}
                                                <a href="{{ route('books.edit', $book->id) }}"
                                                    class="btn btn-primary btn-sm"><i
                                                        class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                                <a href="javascript:void(0);" onclick="deleteBook({{ $book->id }})"
                                                    class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>
                            </thead>
                        </table>
                        @if ($books->isNotEmpty())
                            {{ $books->links() }}
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('customJs')
    <script type="text/javascript">
        function deleteBook(id) {
            if (confirm("Are you sure you want to delete")) {
                $.ajax({
                    url: "{{ route('books.destroy') }}",
                    type: "delete",
                    data: {
                        id: id
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        window.location.href = '{{ route('books.list') }}';
                    }
                })
            }
        }
    </script>
@endsection
