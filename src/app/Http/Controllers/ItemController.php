<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemImage;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $query = Item::query();

        if (auth()->check()) {
            // 自分が出品した商品を除外
            $query->where('user_id', '!=', auth()->id());
        }

        if ($request->query('tab') === 'mylist') {
            if (!auth()->check()) {
                return redirect()->route('login');
            }

            $user = auth()->user();

            $query->whereHas('likes', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where('title', 'like', '%' . $keyword . '%');
        }

        $items = $query->latest()->get();

        return view('items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExhibitionRequest $request)
    {
        $path = $request->file('image')->store('images/items', 'public');

        $item = Item::create([
            'user_id'     => auth()->id(),
            'title'       => $request->input('title'),
            'brand_name'  => $request->input('brand_name'),
            'description' => $request->input('description'),
            'price'       => $request->input('price'),
            'condition'   => $request->input('condition'),
            'status'      => 'on_sale',
        ]);

        $item->images()->create([
            'image_url' => $path,
        ]);

        $item->categories()->attach($request->input('category_ids'));

        return redirect()->route('items.show', ['item_id' => $item->id])->with('status', '商品を出品しました');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($item_id)
    {
        $item = Item::with(['images', 'categories', 'user', 'likes'])
            ->findOrFail($item_id);
        return view('items.show', compact('item'));
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
