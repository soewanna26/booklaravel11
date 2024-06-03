<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::orderBy('created_at', 'DESC');
        if (!empty($request->keyword)) {
            $books = $books->where('title', 'like', '%' . $request->keyword . '%')->orWhere('author', 'like', '%' . $request->keyword . '%');
        }
        $books = $books->paginate(10);
        return view('books.list', [
            'books' => $books
        ]);
    }
    public function create()
    {
        return view('books.create');
    }
    public function store(Request $request)
    {
        $rule = [
            'title' => 'required',
            'author' => 'required',
            'status' => 'required',
        ];

        if (!empty($request->image)) {
            $rule['image'] = 'image';
        };
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->route('books.create')->withInput()->withErrors($validator);
        }

        $book = new Book();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->description = $request->description;
        $book->status = $request->status;
        $book->save();

        // Delete existing book image (if it exists)
        if (File::exists(public_path('uploads/book/' . $book->image))) {
            File::delete(public_path('uploads/book/' . $book->image));
            File::delete(public_path('uploads/book/thumb/' . $book->image));
        }
        //save book book
        if (!empty($request->image)) {
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . "." . $ext;

            $image->move(public_path('uploads/book'), $imageName);
            $book->image = $imageName;
            $book->save();
            //thumb image
            $manager = new ImageManager(Driver::class);
            $image = $manager->read(public_path('uploads/book/' . $imageName));
            $image->resize(1280, 720);
            $image->save(public_path('uploads/book/thumb/' . $imageName));
        }


        return redirect()->route('books.list')->with('success', 'Book created successfully');
    }
    public function edit($id, Request $request)
    {
        $book = Book::find($id);
        if ($book == null) {
            session()->flash('error', 'Book Not Found');
            return response()->json([
                'status' => false,
                'message' => 'Book not found',
            ]);
        };
        return view('books.edit', [
            'book' => $book,
        ]);
    }
    public function update(Request $request, $id)
    {

        $book = Book::findOrFail($id);
        if ($book == null) {
            session()->flash('error', 'Book Not Found');
            return response()->json([
                'status' => false,
                'message' => 'Book not found',
            ]);
        }
        $rule = [
            'title' => 'required',
            'author' => 'required',
            'status' => 'required',
        ];

        if (!empty($request->image)) {
            $rule['image'] = 'image';
        };
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->route('books.edit', $book->id)->withInput()->withErrors($validator);
        }

        $book->title = $request->title;
        $book->author = $request->author;
        $book->description = $request->description;
        $book->status = $request->status;
        $book->save();

        // Delete existing book image (if it exists)
        if (File::exists(public_path('uploads/book/' . $book->image))) {
            File::delete(public_path('uploads/book/' . $book->image));
            File::delete(public_path('uploads/book/thumb/' . $book->image));
        }
        //save book book
        if (!empty($request->image)) {
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . "." . $ext;

            $image->move(public_path('uploads/book'), $imageName);
            $book->image = $imageName;
            $book->save();

            $manager = new ImageManager(Driver::class);
            $image = $manager->read(public_path('uploads/book/' . $imageName));
            $image->resize(1280, 720);
            $image->save(public_path('uploads/book/thumb/' . $imageName));
        }
        //thumb image


        return redirect()->route('books.list')->with('success', 'Book Updated successfully');
    }
    public function destroy(Request $request)
    {
        $book = Book::findOrFail($request->id);
        if ($book == null) {
            session()->flash('error', 'Book Not Found');
            return response()->json([
                'status' => false,
                'message' => 'Book not found',
            ]);
        }
        if (File::exists(public_path('uploads/book/' . $book->image))) {
            File::delete(public_path('uploads/book/' . $book->image));
            File::delete(public_path('uploads/book/thumb/' . $book->image));
        }
        $book->delete();
        session()->flash('success', 'Book deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Book deleted successfully',
        ]);
    }
}
