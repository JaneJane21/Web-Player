<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\AlbumGenre;
use App\Models\AlbumTrack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        // dd($request->all());

        $valid = Validator::make($request->all(),[
            'title'=>['required'],
            'genres'=>['required'],
            'img'=>['required'],
            'tracks'=>['required'],
            'date_release'=>['required'],


        ]);
        if($valid->fails()){
            return response()->json($valid->errors(),400);
        }
        $path_img = $request->file('img')->store('public/img');
        $album = new Album();
        $album->title = $request->title;
        $album->date_release = $request->date_release;
        $album->user_id = Auth::id();
        $album->img = '/storage/'.$path_img;
        $album->save();
        foreach($request->genres as $g){
            $link = new AlbumGenre();
            $link->album_id = $album->id;
            $link->genre_id = $g;
            $link->save();
        }
        foreach($request->tracks as $g){
            $link = new AlbumTrack();
            $link->album_id = $album->id;
            $link->track_id = $g;
            $link->save();
        }
        return response()->json('Успешно загружено',200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Album $album)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Album $album)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Album $album)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Album $album)
    {
        //
    }
}
