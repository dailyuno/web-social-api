<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\YoutubeVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class YoutubeVideoController extends Controller
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
            $youtubeVideos = YoutubeVideo::where('published_at', '>=', $date)->orWhere('published_at', null)->get();
        } else {
            $youtubeVideos = YoutubeVideo::all();
        }

        return response()->json([
            'items' => $youtubeVideos
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
            'id', 'title', 'description', 'published_at', 'thumbnails'
        ]);

        $validator = Validator::make($input, [
            'id' => 'required|string|unique:youtube_videos',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'published_at' => 'nullable|date',
            'thumbnails' => 'nullable|array',
            'thumbnails.*' => 'required|exists:youtube_video_types,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'data' => $input
            ], 400);
        }

        $videoData = collect($input)->except('thumbnails')->all();
        $youtubeVideo = YoutubeVideo::create($videoData);

        if (isset($input['thumbnails'])) {
            for ($i = 0; $i < count($input['thumbnails']); $i++) {
                $youtubeVideo->thumbnails()->create([
                    'video_type_id' => $input['thumbnails'][$i]
                ]);
            }
        }

        return response()->json($youtubeVideo, 200);
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
    public function update(Request $request, YoutubeVideo $youtubeVideo)
    {
        $input = $request->only([
            'title', 'description', 'published_at'
        ]);

        if (isset($input['published_at'])) {
            $input['published_at'] = date('Y-m-d H:i:s', strtotime($input['published_at']));
        }

        $youtubeVideo->update($input);

        return response()->json($youtubeVideo, 200);
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
