<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\YoutubePlayList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class YoutubePlayListController extends Controller
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
            $youtubePlayLists = YoutubePlayList::where('publishedAt', '>=', $date)->get();
        } else {
            $youtubePlayLists = YoutubePlayList::all();
        }

        return response()->json([
            'items' => $youtubePlayLists
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
            'id', 'title', 'publishedAt'
        ]);

        $input['publishedAt'] = date('Y-m-d H:i:s', strtotime($input['publishedAt']));

        $validator = Validator::make($input, [
            'id' => 'required|string|unique:youtube_play_lists',
            'title' => 'required|string',
            'publishedAt' => 'required|date'
        ]);

        if ($validator->fails())  {
            return response()->json([
                'message' => $validator->errors(),
                'data' => $input
            ], 400);
        }

        $youtubePlayList = YoutubePlayList::create($input);

        return response()->json([
            'data' => $youtubePlayList,
            'message' => 'success created'   
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\YoutubePlayList  $youtubePlayList
     * @return \Illuminate\Http\Response
     */
    public function show(YoutubePlayList $youtubePlayList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\YoutubePlayList  $youtubePlayList
     * @return \Illuminate\Http\Response
     */
    public function edit(YoutubePlayList $youtubePlayList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\YoutubePlayList  $youtubePlayList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, YoutubePlayList $youtubePlayList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\YoutubePlayList  $youtubePlayList
     * @return \Illuminate\Http\Response
     */
    public function destroy(YoutubePlayList $youtubePlayList)
    {
        //
    }
}
