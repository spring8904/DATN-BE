<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;

class AnalyticController extends Controller
{
    public function index(){
        return view('analytics.index');
    }
}
