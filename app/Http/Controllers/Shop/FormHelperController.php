<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
	
use App\FormHelp;

use Validator, Input, Redirect; 

class FormHelpController extends Controller
{
    public function formHelp(Request $request)
	{

		$rules = array(
			
			// 'name' => 'required',
			// 'email' => 'email|required',
		);

		$validator = Validator::make($request->all(), $rules);

		if ($validator->passes()){

		
			$FormHelp->full_name = $request->full_name;
			$FormHelp->email_help = $request->email_help;
			$FormHelp->subject_help = $request->subject_help;
			$FormHelp->message_help = $request->message_help;
			$FormHelp->telefone_help = $request->telefone_help;
			}
			if($FormHelp->save()){
		
		    if ($validator->passes()){
			$status = true;

			Mail::to('contato@allgency.com')->send(new \App\Mail\formhelp($request->full_name, $request->subject_help, $request->email_help, $request->message_help, $request->telefone_help));

			return view('site/home',compact('status'));
		}

		$status = false;
		return view('site/home',compact('status'));

		}
	}
}