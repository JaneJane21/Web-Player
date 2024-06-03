<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Genre;
use App\Models\LikeAlbums;
use App\Models\Playlist;
use App\Models\PlaylistTrack;
use App\Models\Track;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function welcome(){
        return view('welcome');
    }
    public function login(){
        return view('guest.auth');
    }
    public function reg(){
        return view('guest.reg');
    }
    public function show_select_genres(){
        return view('user.select_genre');
    }
    public function show_search_page($tracks, $search, $playlists, $albums){

        return view('guest.search',['tracks'=>$tracks,'search'=>$search,'playlists'=>$playlists,'albums'=>$albums]);
    }
    public function new_track(){
        $genres = Genre::all();
        $tracks = Track::query()->where('user_id',Auth::id())->get();
        return view('artist.newtrack',['genres'=>$genres,'tracks'=>$tracks]);
    }
    public function track($id){
       return view('guest.track',['track'=>$id]);
    }
    public function search(Request $request){
        // dd($request->all());
        $tracks = [];
        $playlists = [];
        $albums = [];
        $words = explode(' ',$request->search);

        $query_track = Track::query();
        foreach($words as $word){
            $query_track = $query_track->
            where('title','LIKE',"%{$word}%")->
            orWhereRelation('user','nickname','LIKE',"%{$word}%");
        }
        $tracks = $query_track->where('is_available','true')->with(['user'])->get();

        $query_playlist = Playlist::query();
        foreach($words as $word){
            $query_playlist = $query_playlist->
            where('title','LIKE',"%{$word}%");

        }
        $playlists = $query_playlist->get();

        $query_album = Album::query();
        foreach($words as $word){
            $query_album = $query_album->
            where('title','LIKE',"%{$word}%");

        }
        $albums = $query_album->get();

        // dd($tracks);
        return view('guest.search',['tracks'=>$tracks,'search'=>$request->search,'playlists'=>$playlists,'albums'=>$albums]);
        // return redirect()->route('show_search_page',['tracks'=>$tracks,'search'=>$request->search,'playlists'=>$playlists,'albums'=>$albums]);
        // return $this->show_search_page($tracks, $request->search, $playlists, $albums);

    }

    public function artist_profile(){
        $user = User::query()->where('id',Auth::id())->first();
        // dd($user);
        return view('artist.profile',['user'=>$user]);
    }

    public function user_profile(){
            $user = User::query()->where('id',Auth::id())->first();
            // dd($user);
            return view('user.profile',['user'=>$user]);
        }

    public function my_beats(){
        $playlist = Playlist::query()->where('user_id',Auth::id())->where('title','Мои биты')->first();
        $tracks = [];
        $playlist_track = PlaylistTrack::query()->where('playlist_id',$playlist->id)->get();
        foreach($playlist_track as $p){
            $track = Track::query()->where('id',$p->track_id)->where('is_available','true')->with(['user'])->first();
            array_push($tracks,$track);
        }
        // $tracks->get();
        // dd($tracks);

        $playlists = Playlist::query()->where('user_id',Auth::id())->where('title','!=','Мои биты')->get();
        $albums = LikeAlbums::query()->where('user_id',Auth::id())->get();
        return view('user.my_beats',['tracks'=>$tracks,'playlists'=>$playlists,'albums'=>$albums]);


    }
}

