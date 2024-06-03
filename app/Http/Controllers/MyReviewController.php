<?php

namespace App\Http\Controllers;

use App\Models\ReviewRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyReviewController extends Controller
{
    public function index(Request $request)
    {
        $id = Auth::user()->id;
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
        $reviews = $reviews->where('user_id', '=', $id)->orderBy('created_at', 'DESC')->paginate(9);
        return view('reviews.list', [
            'reviews' => $reviews
        ]);
    }
}
