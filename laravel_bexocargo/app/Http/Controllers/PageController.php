<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display the home page.
     */
    public function home()
    {
        return view('pages.home');
    }

    /**
     * Display the about page.
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Display the terms and conditions page.
     */
    public function terms()
    {
        return view('pages.terms');
    }

    /**
     * Display the privacy policy page.
     */
    public function privacy()
    {
        return view('pages.privacy');
    }

    /**
     * Display the shipping terms page.
     */
    public function shippingTerms()
    {
        return view('pages.shipping-terms');
    }
}