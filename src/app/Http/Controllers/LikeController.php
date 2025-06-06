<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class LikeController extends Controller
{
    public function toggle(Item $item)
    {
        $user = auth()->user();
        $existingLike = $item->likes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
        } else {
            $item->likes()->create([
                'user_id' => $user->id,
                'created_at' => now(),
            ]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'count' => $item->likes()->count(),
        ]);
    }
}
