<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\YoutubePlayListItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class YoutubePlayListItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $date = $request->date;

        if ($date) {
            $youtubePlayListItems = YoutubePlayListItem::where('publishedAt', '>=', $date)->get();
        } else {
            $youtubePlayListItems = YoutubePlayListItem::all();
        }

        return response()->json([
            'items' => $youtubePlayListItems
        ]);
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
    public function store(Request $request)
    {
        $input = $request->only([
            'id', 'youtube_play_lists_id', 'title', 'description', 'publishedAt'
        ]);

        $input['publishedAt'] = date('Y-m-d H:i:s', strtotime($input['publishedAt']));

        $validator = Validator::make($input, [
            'id' => 'required|string|unique:youtube_play_list_items',
            'youtube_play_lists_id' => 'required|string|exists:youtube_play_lists,id',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'publishedAt' => 'required|date'
        ]);

        if ($validator->fails())  {
            return response()->json([
                'message' => $validator->errors(),
                'data' => $input
            ], 400);
        }

        $youtubePlayListItem = YoutubePlayListItem::create($input);

        return response()->json([
            'data' => $youtubePlayListItem,
            'message' => 'success created'   
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\YoutubePlayListItem  $youtubePlayListItem
     * @return \Illuminate\Http\Response
     */
    public function show(YoutubePlayListItem $youtubePlayListItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\YoutubePlayListItem  $youtubePlayListItem
     * @return \Illuminate\Http\Response
     */
    public function edit(YoutubePlayListItem $youtubePlayListItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\YoutubePlayListItem  $youtubePlayListItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, YoutubePlayListItem $youtubePlayListItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\YoutubePlayListItem  $youtubePlayListItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(YoutubePlayListItem $youtubePlayListItem)
    {
        //
    }
}
