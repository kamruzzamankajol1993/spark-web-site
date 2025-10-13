<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\ExtraPage;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($slug)
    {
        $pages = ExtraPage::first();
        if (!$pages) {
            abort(404);
        }

        // Match the URL slug to the database column and set a title
        $contentMapping = [
            'privacy-policy' => ['title' => 'Privacy Policy', 'content' => $pages->privacy_policy],
            'terms-and-conditions' => ['title' => 'Terms & Conditions', 'content' => $pages->term_condition],
            'return-policy' => ['title' => 'Return Policy', 'content' => $pages->return_policy],
            'warranty-policy' => ['title' => 'Warranty Policy', 'content' => $pages->warranty_policy],
            'payment-terms' => ['title' => 'Payment Terms', 'content' => $pages->payment_term],
            'delivery-policy' => ['title' => 'Delivery Policy', 'content' => $pages->delivery_policy],
            'refund-policy' => ['title' => 'Refund and Return Policy', 'content' => $pages->refund_policy],
        ];

        // If the slug doesn't match a known policy, show a 404 error
        if (!array_key_exists($slug, $contentMapping)) {
            abort(404);
        }

        $page = $contentMapping[$slug];

        return view('front.page.show', [
            'title' => $page['title'],
            'content' => $page['content']
        ]);
    }
}