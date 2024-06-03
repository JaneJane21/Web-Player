<?php

namespace App\Http\Controllers;

use App\Models\UserGenre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserGenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function user_favs()
    {
        $favs = UserGenre::query()->where('user_id',Auth::id())->with(['genre'])->get();
        // dd($favs);
        return response()->json($favs,200);
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
    public function show(UserGenre $userGenre)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserGenre $userGenre)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserGenre $userGenre)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserGenre $userGenre)
    {
        //
    }
}
