<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class AjaxController extends Controller
{
    private $review;

    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    public function reviewGallery(Request $request)
    {
        $page = $request->query('nextPage', 1);
        $pageSize = $request->query('pageSize', 10);

        $result = $this->review->getReviewsWithImagesByPageAndCount($page, $pageSize);

        $reviews = $result['reviews'];
        $total = $result['total'];

        $response = [
            'items' => [],
            'hasMorePages' => ($page * $pageSize) < $total
        ];

        foreach ($reviews as $review) {
            $imageURL = '';
            if ($review->images->count() > 0) {
                $imageURL = $review->images->first()->image_url;
            }
        
            $response['items'][] = [
                'title' => '',
                'src' => $imageURL,
                'productUrl' => ''
            ];
        }
        

        return response()->json($response);
    }
}
