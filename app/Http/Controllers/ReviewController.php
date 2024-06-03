<?php

namespace App\Http\Controllers;

use App\Models\ReviewRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = ReviewRating::with(['user', 'book']);
        $search_reviews = $request->keyword;
        if (!empty($search_reviews)) {
            $reviews = $reviews->where('review', 'like', '%' . $search_reviews . '%')
                ->orWhereHas('book', function ($query) use ($search_reviews) {
                    $query->where('title', 'LIKE', '%' . $search_reviews . '%');
                })->orWhereHas('user', function ($query) use ($search_reviews) {
                    $query->where('name', 'LIKE', '%' . $search_reviews . '%');
                });
        }
        $reviews = $reviews->orderBy('created_at', 'DESC')->paginate(9);
        return view('reviews.list', [
            'reviews' => $reviews
        ]);
    }

    public function edit($id)
    {
        $review = ReviewRating::findOrFail($id);
        return view('reviews.edit', [
            'review' => $review
        ]);
    }

    public function update($id, Request $request)
    {
        $review = ReviewRating::findOrFail($id);
        $validate = Validator::make($request->all(), [
            'review' => 'required',
            'status' => 'required',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate);
        }

        $review->review = $request->review;
        $review->status = $request->status;

        $review->save();

        session()->flash('success', 'Review Updated successfully');
        return redirect()->route('reviews.list')->with('success', 'Review Updated successfully');
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $review = ReviewRating::findOrFail($id);
        $review->delete();
        session()->flash('success', 'Review Deleted successfully');
        return response()->json([
            'status' => true
        ]);
    }
}
