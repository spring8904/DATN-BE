<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Imports\QuizImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class QuizController extends Controller
{
    public function import(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            $file = $request->file('file');

            Excel::import(new QuizImport, $file);

            return response()->json([
                'message' => 'Import quiz successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Import quiz failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
