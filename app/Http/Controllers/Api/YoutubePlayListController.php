<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\YoutubePlayList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\YoutubePlayListResource;

class YoutubePlayListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $date = $request->input('date');

        if ($date) {
            $youtubePlayLists = YoutubePlayList::where('published_at', '>=', $date)->get();
        } else {
            $youtubePlayLists = YoutubePlayList::all();
        }

        return response()->json([
            'items' => $youtubePlayLists
        ], 200);
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
            'id', 'title', 'description', 'published_at'
        ]);

        $validator = Validator::make($input, [
            'id' => 'required|string|unique:youtube_play_lists',
            'title' => 'required|string',
            'description' => 'string',
            'published_at' => 'required|date'
        ]);

        if ($validator->fails())  {
            return response()->json([
                'message' => $validator->errors(),
                'data' => $input
            ], 400);
        }

        $youtubePlayList = YoutubePlayList::create($input);

        return response()->json($youtubePlayList);
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
