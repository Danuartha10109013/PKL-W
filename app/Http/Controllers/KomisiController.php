<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KomisiController extends Controller
{
    public function index(){
        return view('pages.penjualan.index');
    }

    public function add(){
        return view('pages.penjualan.add');
    }

    public function store(){
        
    }
}
