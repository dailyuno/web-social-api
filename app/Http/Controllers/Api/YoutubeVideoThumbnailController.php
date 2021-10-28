<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\YoutubeVideoThumbnail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule; 

class YoutubeVideoThumbnailController extends Controller
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
            $youtubeThumbnails = YoutubeVideoThumbnail::join('youtube_videos', 'youtube_video_thumbnails.video_id', 'youtube_videos.id')
                ->select('youtube_video_thumbnails.*')
                ->where('youtube_videos.published_at', '>=', $date)
                ->get();
        } else {
            $youtubeThumbnails = YoutubeVideoThumbnail::all();
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
            'video_id', 'video_type_id'
        ]);

        $validator = Validator::make($input, [
            'video_id' => 'required|string|exists:youtube_videos,id',
            'video_type_id' => ['required', 'string', 'exists:youtube_video_types,id', Rule::unique('youtube_video_thumbnails')->where(function($query){
                global $request;
                return $query->where('video_id', $request->input('video_id'));
            })]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'data' => $input
            ], 400);
        }

        $youtubeThumbnail = YoutubeVideoThumbnail::create($input);

        return response()->json($youtubeThumbnail, 200);
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
