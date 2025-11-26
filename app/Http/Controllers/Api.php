<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Api extends Controller
{
  public function Aderesse(Request $request){

$user =$request->ip();
return $user;
  }
}
