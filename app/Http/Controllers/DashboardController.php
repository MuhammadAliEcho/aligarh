<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\AdminContent;
use Illuminate\Support\Facades\Config;

class DashboardController extends Controller
{

//  protected $Routes;
  protected $data;

  public function __Construct($Routes){
//    $this->Routes = $Routes;
    $this->data['root'] = $Routes;
  }

  public function GetDashboard(){
    return view('dashboard', $this->data);
  }

  public function errors(){
    return view('errors.404');
  }

/*  public function navigation(){

  }
*/

}
