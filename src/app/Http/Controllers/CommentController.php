<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;


class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

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


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
