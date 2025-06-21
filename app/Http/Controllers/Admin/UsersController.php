<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\UserPrivilege;
use App\AdminContent;
use App\Employee;
use App\Teacher;
use App\User;
use Auth;

class UsersController extends Controller
{
  public function GetUsers(Request $request)
  {
         return response()->json('working later');
  }
  
}
