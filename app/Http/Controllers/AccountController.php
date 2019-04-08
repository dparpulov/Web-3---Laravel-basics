<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function accountPage(){
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        return view('pages.accountPage');
    }

    public function Update(Request $request){
        $user->user_name = $request->user_name;
        $user->save();
        return redirect('/accountPage')->with('success','You have successfully changed your name');
    }


    public function update_avatar(Request $request){

        $request->validate([
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($request->hasFile('avatar')){
            //Get filename with the extension
            $fileNameWithExt = $request->file('avatar')->getClientOriginalName();
            
            //Get just filename
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

            //Get just extension
            $extension = $request->file('avatar')->getClientOriginalExtension();

            //Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;

            //Upload image
            $path = $request->avatar->storeAs('public/avatars', $fileNameToStore);
        }else{
            $fileNameToStore = 'noimage.jpg';
        }

        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        if($request->hasFile('avatar')){
            if($user->avatar != 'noimage.jpg') {
                //Storage::delete('public/avatars/'.$user->avatar);
            }
            $user->avatar = $fileNameToStore;
        }

        $user->avatar = $fileNameToStore;
        $user->save();

        return redirect('/accountPage')->with('success','You have successfully uploaded a profile picture.');

    }
}
