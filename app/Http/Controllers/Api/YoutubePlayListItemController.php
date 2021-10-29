<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\YoutubePlayListItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use App\Http\Resources\YoutubePlayListItemResource;

class YoutubePlayListItemController extends Controller
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
            $youtubePlayListItems = YoutubePlayListItem::where('published_at', '>=', $date)->get();
        } else {
            $youtubePlayListItems = YoutubePlayListItem::all();
        }

        return response()->json([
            'items' => $youtubePlayListItems
        ], 200);
    }

    public function getYoutubePlayListItems($lang, $count) {
        $youtubePlayListItems = Cache::remember('youtube_play_list_items' . $lang, 60 * 60, function () use ($lang, $count) {
            $items = YoutubePlayListItem::join('youtube_play_lists', 'youtube_play_lists.id', 'youtube_play_list_items.play_list_id')
                ->join('youtube_videos', 'youtube_videos.id', 'youtube_play_list_items.video_id')
                ->where('youtube_play_lists.lang', $lang)
                ->where('youtube_videos.published_at', '<>', null)
                ->select(['youtube_play_list_items.*', 'youtube_play_lists.lang'])
                ->orderBy('published_at', 'desc')
                ->take($count)
                ->get();
            return $items;
        });

        return $youtubePlayListItems;
    }

    public function kr()
    {
        $youtubePlayListItems = $this->getYoutubePlayListItems('kr', 5);

        return response()->json([
            'items' => YoutubePlayListItemResource::collection($youtubePlayListItems)
        ], 200);
    }

    public function en()
    {
        $youtubePlayListItems = $this->getYoutubePlayListItems('en', 5);

        return response()->json([
            'items' => YoutubePlayListItemResource::collection($youtubePlayListItems)
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
            'play_list_id', 'video_id', 'published_at'
        ]);

        $validator= Validator::make($input, [
            'play_list_id' => 'required|string|exists:youtube_play_lists,id',
            'video_id' => ['required', 'string', 'exists:youtube_videos,id', Rule::unique('youtube_play_list_items')->where(function($query){
                global $request;
                return $query->where('play_list_id', $request->input('play_list_id'));
            })],
            'published_at' => 'required|date'
        ]);

        if ($validator->fails())  {
            return response()->json([
                'message' => $validator->errors(),
                'data' => $input
            ], 400);
        }

        $input['published_at'] = date('Y-m-d H:i:s', strtotime($input['published_at']));

        $youtubePlayListItem = YoutubePlayListItem::create($input);

        return response()->json($youtubePlayListItem);
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
