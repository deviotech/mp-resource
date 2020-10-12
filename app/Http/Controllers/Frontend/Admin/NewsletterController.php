<?php

namespace App\Http\Controllers\Frontend\Admin;

use App\Http\Controllers\Controller;
use App\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function index()
    {
        return view('admin.newsletter.index');
    }
    public function list()
    {
        $newsletter = Newsletter::all();
        return response(['data' => $newsletter]);
    }
}
