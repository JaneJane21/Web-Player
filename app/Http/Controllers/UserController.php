<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function auth(Request $request){
        // dd($request->all());
        $request->validate([
            'phone'=>['required','digits:11'],
            'password'=>'required'
        ]);
        $user = User::query()->where('phone',$request->phone)->where('password',md5($request->password))->first();
        if($user){
            Auth::login($user);
            return redirect()->route('welcome')->with('success','Успешный вход');
        }
        else{
            return redirect()->back()->with('error','Такой пользователь не найден');
        }


    }
    public function reg(Request $request){
        // dd($request->all());
        $request->validate([
            'phone'=>['required','digits:11','unique:users'],
            'password'=>['required','confirmed']
        ]);
        $user = new User();
        $user->phone = $request->phone;
        $user->password = md5($request->password);
        $user->save();
        $playlist = new Playlist();
        $playlist->title = 'Мои биты';
        $playlist->user_id = $user->id;
        $playlist->save();
        Auth::login($user);
        return redirect()->route('show_select_genres')->with('success','Успешная регистрация');
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }
}
