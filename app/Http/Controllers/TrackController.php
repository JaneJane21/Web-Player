<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Track;
use App\Models\TrackGenre;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tracks = Track::query()->where('is_available','true')->with(['user'])->get();
        return response()->json($tracks,200);
    }

    public function index_my_tracks()
    {
        $tracks = Track::query()->where('user_id',Auth::id())->with(['user'])->get();
        return response()->json($tracks,200);
    }

    public function index_one(Request $request)
    {
        $id = $request[0];
        $track = Track::query()->where('id',$id)->with(['user'])->first();

        return response()->json($track,200);
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
            'audio'=>['required'],
            'img'=>['required'],
            'genres'=>['required'],
            'date_release'=>['required'],
            'text'=>['required'],

        ]);
        if($valid->fails()){
            return response()->json($valid->errors(),400);
        }
        $path_audio = $request->file('audio')->store('public/audio');
        $path_img = $request->file('img')->store('public/img');
        $track = new Track();
        $track->title = $request->title;
        $track->date_release = $request->date_release;
        $track->genre_id = $request->genres[0];
        $track->user_id = Auth::id();
        $track->audio = '/storage/'.$path_audio;
        $track->img = '/storage/'.$path_img;
        $track->text = $request->text;
        if($request->is_cens == 'on'){
            $track->is_cens = 'true';
        }
        $track->save();
        foreach($request->genres as $g){
            $link = new TrackGenre();
            $link->track_id = $track->id;
            $link->genre_id = $g;
            $link->save();
        }
        return response()->json('Успешно загружено',200);

    }

    /**
     * Display the specified resource.
     */
    public function show(Track $track)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function add_track_listen(Request $request, Track $track)
    {
        $track = Track::query()->where('id', $request[0])->first();
        $track->listeners +=1;
        $track->update();
        return response()->json([$track->listeners],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function application_hide_track(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'text'=>'required'
        ]);
        $app = new Application();
        $app->user_id = Auth::id();
        $app->category_id = 1;
        $app->track_id = $request->track;
        $app->text = $request->text;
        $app->save();
        $track = Track::query()->where('id',$request->track)->first();
        $track->is_available = 'false';
        $track->status = 'модерация';
        $track->update();
        return response()->json('Успешно',200);
    }

    public function application_delete_track(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'text'=>'required'
        ]);
        $app = new Application();
        $app->user_id = Auth::id();
        $app->category_id = 2;
        $app->track_id = $request->track;
        $app->text = $request->text;
        $app->save();
        $track = Track::query()->where('id',$request->track)->first();
        $track->is_available = 'false';
        $track->status = 'модерация';
        $track->update();
        return response()->json('Успешно',200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Track $track)
    {
        //
    }
}
