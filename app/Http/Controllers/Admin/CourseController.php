<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Traits\FilterTrait;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use FilterTrait;
    public function index(Request $request)
    {
        //
        $queryCourses = Course::query();

        // Kiểm tra nếu có từ khóa tìm kiếm
        if ($request->has('query') && $request->input('query')) {
            $search = $request->input(key: 'query');
            $queryCourses->where('name', 'like', "%$search%")
                ->orWhere('code', 'like', "%$search%");
        }



        if ($request->hasAny(['code', 'name', 'user_name', 'level', 'price', 'created_at', 'updated_at'])) {
            $queryCourses = $this->filter($request, $queryCourses);
        }

        $courses = $queryCourses->orderBy('created_at', 'desc')->paginate(10);

        if ($request->ajax()) {

            $html = view('courses.table', compact('courses'))->render();
            return response()->json(['html' => $html]);
        }
        return view('courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $course = Course::findOrFail($id);
        return view('courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    private function filter($request, $query)
    {
        $filters = [
            'code' => ['queryWhere' => 'LIKE'],
            'name' => ['queryWhere' => 'LIKE'],
            // 'user_name' => null,
            'level' => ['queryWhere' => '='],
            'price' => ['queryWhere' => '='],
            'start_date' => ['queryWhere' => '>='],
            'expire_date' => ['queryWhere' => '<='],
            'status' => ['queryWhere' => '='],
            'created_at' => ['attribute' => 'LIKE'],
        ];

        $query = $this->filterTrait($filters, $request, $query);

        return $query;
    }
}
