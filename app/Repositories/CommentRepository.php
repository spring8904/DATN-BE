<?php
namespace App\Repositories;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentRepository
{
    public function create(array $data): Comment
    {
        return Comment::create([
            'user_id' => Auth::id(),
            'commentable_id' => $data['commentable_id'],
            'commentable_type' => $data['commentable_type'],
            'parent_id' => $data['parent_id'] ?? null,
            'content' => $data['content'],
        ]);
    }

    public function update(Comment $comment, array $data): Comment
    {
        $comment->update($data);
        return $comment;
    }

    public function delete(Comment $comment): bool
    {
        return $comment->delete();
    }

    public function getByCommentable($commentableId, $commentableType, $perPage = 10)
    {
        return Comment::where('commentable_id', $commentableId)
            ->where('commentable_type', $commentableType)
            ->with('replies')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
