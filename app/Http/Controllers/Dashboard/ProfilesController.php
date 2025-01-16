<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Models\Dashboard\Profile;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Languages;

class ProfilesController extends Controller
{
  
    public function edit()
    {
        //
        $user = Auth::user();
        return view('dashboardPages.profile.edit',[
            'user'=>$user,
            'countries' => Countries::getNames(),
            'locales' => Languages::getNames(),
        ]);
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileRequest $request)
    {
        //
    
        $user = $request->user();


        //$user->profile->fill($request->all())->save();
        
        if ($user->profile) {
            $user->profile->update($request->validated());
        } else {
            $user->profile()->create($request->validated());
        }
        
        return redirect()->route('dashboard.profile.edit')->with('success', 'Profile Updated!');
        
        /*
         //   $profile = $user->profile;

        if($profile->user_id){
        $user->profile->update($request->all());

        }else{

        $user->profile()->create($request->all());

        }*/
        
    }

 
}
