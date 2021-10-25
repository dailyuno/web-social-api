<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\YoutubeThumbnail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class YoutubeThumbnailController extends Controller
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
            $youtubeThumbnails = YoutubeThumbnail::where('created_at', '>=', $date)->get();
        } else {
            $youtubeThumbnails = YoutubeThumbnail::all();
        }

        return response()->json([
            'items' => $youtubeThumbnails
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
            'youtube_thumbnailable_id', 'youtube_thumbnailable_type', 'url', 'type', 'width', 'height'
        ]);

        $validator = Validator::make($input, [
            'youtube_thumbnailable_id' => 'required|string',
            'youtube_thumbnailable_type' => 'required|in:youtube_play_lists,youtube_play_list_items',
            'url' => 'required|string|unique:youtube_thumbnails,youtube_thumbnailable_id,url',
            'type' => 'required|string|in:default,medium,high,standard,maxres',
            'width' => 'required|integer',
            'height' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'data' => $input
            ], 400);
        }

        $youtubeThumbnail = YoutubeThumbnail::create($input);

        return response()->json([
            'data' => $youtubeThumbnail,
            'message' => 'success created'   
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\YoutubeThumbnail  $youtubeThumbnail
     * @return \Illuminate\Http\Response
     */
    public function show(YoutubeThumbnail $youtubeThumbnail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\YoutubeThumbnail  $youtubeThumbnail
     * @return \Illuminate\Http\Response
     */
    public function edit(YoutubeThumbnail $youtubeThumbnail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\YoutubeThumbnail  $youtubeThumbnail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, YoutubeThumbnail $youtubeThumbnail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\YoutubeThumbnail  $youtubeThumbnail
     * @return \Illuminate\Http\Response
     */
    public function destroy(YoutubeThumbnail $youtubeThumbnail)
    {
        //
    }
}
