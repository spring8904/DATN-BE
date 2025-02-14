<?php

namespace App\Http\Controllers\API\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Comments\StoreCommentRequest;
use App\Models\Comment;
use App\Traits\LoggableTrait;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use LoggableTrait;
    public function store(StoreCommentRequest $request)
    {
        $request->validated();

        Comment::create($request->all());

        return response()->json(['message' => 'Comment created successfully']);
    }

    public function update(StoreCommentRequest $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $this->authorize('update', $comment);

        $comment->update($request->only('content'));

        return response()->json(['message' => 'Comment updated successfully']);
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }

    public function index($commentableId, $commentableType)
    {
        $comments = Comment::where('commentable_id', $commentableId)
            ->where('commentable_type', $commentableType)
            ->with('replies')
            ->get();

        return response()->json($comments);
    }
}
