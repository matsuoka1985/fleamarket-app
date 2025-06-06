<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;


class CommentController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommentRequest $request, $item_id)
    {
        $userId = auth()->id();
        $content = $request->input('comment');

        $latest = Comment::where('user_id', $userId)
            ->where('item_id', $item_id)
            ->latest()
            ->first();

        if ($latest && $latest->content === $content && now()->diffInSeconds($latest->created_at) < 10) {
            return redirect()->route('items.show', ['item_id' => $item_id])
                ->with('error', '同じコメントを連続して送信することはできません');
        }

        Comment::create([
            'user_id' => $userId,
            'item_id' => $item_id,
            'content' => $content,
        ]);

        return redirect()->route('items.show', ['item_id' => $item_id])
            ->with('success', 'コメントを投稿しました');
    }

}
