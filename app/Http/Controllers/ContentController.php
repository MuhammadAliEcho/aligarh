<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ContentController extends Controller {

  protected $Routes;
  protected $Content;

  public function __Construct(Request $request){
    $root = Route::current()->parameters();
    $this->Routes['ctrl'] = isset($root['ctrl'])? $root['ctrl'] : 'dashboard';
    $this->Routes['job'] = isset($root['job'])? $root['job'] : '';
    $this->Routes['option'] = isset($root['option'])? $root['option'] : '';
//    $this->LoadContent();
  }

  protected function LoadContent(){
    $Content = 'App\\'.Auth::user()->allow_content.'Content';
    $this->Routes['content'] = $this->Content = $Content::where('root', $this->Routes['ctrl'])->firstOrfail();
  }

  public function LoadController(Request $request){
//    dd(Auth::user());
    $this->LoadContent();
    $ClassName = $this->LoadClass();
    $ClassName = new $ClassName($this->Routes, $request);
    $Function = $this->LoadFunc();

    return $ClassName->$Function();
  }

  public function PostLoadController(Request $request){
    $this->LoadContent();
    $ClassName = $this->LoadClass();
    $ClassName = new $ClassName($this->Routes, $request);
    $Function = $this->LoadFunc('post_func');

    return $ClassName->$Function($request);
  }

  public function AjaxLoadController(Request $request){
    $this->LoadContent();
    $ClassName = $this->LoadClass();
    $ClassName = new $ClassName($this->Routes, $request);
    $Function = $this->LoadFunc('ajax_func');

    return $ClassName->$Function();

  }

  protected function LoadClass(){
    return  'App\Http\Controllers\\'.$this->Content->ctrl;
  }

  protected function LoadFunc($func = 'func'){

//    $this->Content->$func = json_decode($this->Content->$func);
    if(!empty($this->Routes['job'])){
      $job = $this->Routes['job'];
      if(isset($this->Content->$func->$job) && Auth::user()->privileges->{$this->Content->id}->$job){
        return $this->Content->$func->$job;
      }
      return  abort(404);
    }
    return (isset($this->Content->$func->default) && Auth::user()->privileges->{$this->Content->id}->default)? $this->Content->$func->default : abort(404);
  }


// Disabled 
/*
  protected function LoadPostFunc(){

    $this->Content->post_func = json_decode($this->Content->post_func);
    if(isset($this->Routes['job'])){
      $job = $this->Routes['job'];
      if(isset($this->Content->post_func->$job)){
        return $this->Content->post_func->$job;
      }
      abort(404);
    }

    abort(503);
  }

*/
}