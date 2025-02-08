<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TopCourseController extends Controller
{
    public function index(){
        return view('top-courses.index');
    }
}
