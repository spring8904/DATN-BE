<?php

namespace App\Http\Controllers\API\Note;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Note\StoreNoteRequest;
use App\Http\Requests\API\Note\UpdateNoteRequest;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $query = Note::query();
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->has('lesson_id')) {
            $query->where('lesson_id', $request->lesson_id);
        }
        if ($request->has('time_min') && $request->has('time_max')) {
            $query->where('time', [$request->time_min, $request->time_max]);
        }
        $notes = $query->paginate(10);
        return response()->json($notes, 200);
    }
    public function show($id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json(['message' => 'Không tìm thấy note'], 404);
        }
        return response()->json($note, 200);
    }
    public function store(StoreNoteRequest $request)
    {
        try {
            $note = $request->validated();
            $note = Note::create($note);
            return response()->json([
                'success' => true,
                'message' => 'Thao tác thành công',
                'data' => $note,
            ], 200);
        } catch (\Exception $e) {
            //throw $th;
            $this->LogError($e);
            return response()->json([
                'success' => false,
                'message' => 'Thao tác không thành công',
            ], 500);
        }
    }
    public function update(UpdateNoteRequest $request, $id)
    {
        try {
            $note = Note::find($id);
            if (!$note) {
                return response()->json(['message' => 'Không tìm thấy note'], 404);
            }

            $validate = $request->validated();

            $note->update($validate);
            return response()->json([
                'success' => true,
                'message' => 'Thao tác thành công',
                'data' => $note,
            ], 200);
        } catch (\Exception $e) {
            $this->logError($e);
            return response()->json([
                'success' => false,
                'message' => 'Thao tác không thành công',
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $note = Note::find($id);
            $note->delete();
            return response()->json($data = ['status' => 'success', 'message' => 'Mục đã được xóa.']);
        } catch (\Exception $e) {
            //throw $th;
            $this->logError($e);

            return response()->json($data = ['status' => 'error', 'message' => 'Lỗi thao tác.']);
        }
    }
}
