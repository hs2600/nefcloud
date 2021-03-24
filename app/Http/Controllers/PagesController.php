<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(){ //done
        return view('pages.index');
    }

    public function dashboard(){
        return view('pages.dashboard');
    }

    public function search(){
        return view('pages.search');
    }

    public function demo(){
        return view('pages.demo');
    }

    public function file(){
        return view('pages.file');
    }

    public function guest(){
        return view('pages.guest');
    }

    public function profile(){
        return view('pages.profile');
    }

    public function about(){
        return view('pages.about');
    }
    public function guest_share($share_id){
        return view('pages.share.guest_share', array(
            'id' => $share_id
        ));
    }
    public function login($id){
        return view('pages.share.guest_share');
    }    
}
