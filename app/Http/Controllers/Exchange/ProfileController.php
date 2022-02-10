<?php

namespace App\Http\Controllers\Exchange;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
class ProfileController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('exchange/profile.index');
    }
    
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        

        if($request->email != $request->user()->email){
            $rules = ['name' => 'required|string|max:255|regex:/(^[A-Za-z0-9 ]+$)+/', 'email' => 'required|email|unique:users,email'];
        }else{
            $rules = ['name' => 'required|string|max:255|regex:/(^[A-Za-z0-9 ]+$)+/'];
        }
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()){


            $request->user()->name = $request->name;
            $request->user()->email = $request->email;


            if($request->user()->save()){
                return redirect()->action('Exchange\ProfileController@index')->with('success', 'Profile updated with success!'); 
            
            }
        }
        return redirect()->action('Exchange\ProfileController@index')->with('danger', 'Not is possible update your profile. Please try again!'); 

    }


}
