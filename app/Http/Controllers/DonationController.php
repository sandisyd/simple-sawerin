<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function index($username){
        $user = User::where("username",$username)->firstOrFail();
        return view("donation",compact("user"));
    }
}
