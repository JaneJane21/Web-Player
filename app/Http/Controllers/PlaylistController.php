<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\PlaylistTrack;
use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index_for_song()
    {
        $playlists = Playlist::query()->where('user_id',Auth::id())->get();
        // dd($playlists);
        return response()->json($playlists,200);
    }
    public function song_in_new_playlist(Request $request){
        // dd($request->all());
        $request->validate([
            'title'=>'required'
        ]);

        $list = new Playlist();
        $list->title = $request->title;
        $list->user_id = Auth::id();
        $list->save();
        $listTrack = new PlaylistTrack();
        $listTrack->playlist_id = $list->id;
        $listTrack->track_id = $request->id;
        $listTrack->save();
        return response()->json('Добавлено в новый плейлист',200);
    }

    public function song_in_playlist(Request $request, Track $track){
        // dd($request->all());
        foreach($request->playlists as $play){
            $playlist = PlaylistTrack::query()->where('playlist_id',$play)->where('track_id',$track->id)->first();
            if(!$playlist){
                $listTrack = new PlaylistTrack();
                $listTrack->playlist_id = $play;
                $listTrack->track_id = $request->id;
                $listTrack->save();
            }
        }
        return response()->json('Добавлено',200);

    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Playlist $playlist)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Playlist $playlist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Playlist $playlist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Playlist $playlist)
    {
        //
    }
}
