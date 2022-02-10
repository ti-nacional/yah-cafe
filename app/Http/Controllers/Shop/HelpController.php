<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Help;
use Validator;
use Input; 
use Redirect;
use Mail;

class HelpController extends Controller
{
    public function email(Request $request)
	{

		$rules = array(
			
		
		
			
			// 'name' => 'required',
			// 'email' => 'email|required',
			
		
		
		);

		$validator = Validator::make($request->all(), $rules);

		if ($validator->passes()){
		
		    if ($validator->passes()){
			$status = true;

			Mail::to('contato@allgency.com')->send(new \App\Mail\Help( $request->telefone_help, $request->title_help, $request->full_name, $request->email_help, $request->message_help));

			return view('site/index',compact('status'));
		}

		$status = false;
		return view('site/index',compact('status'));

		}
	}
}