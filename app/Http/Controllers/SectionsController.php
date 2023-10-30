<?php

namespace App\Http\Controllers;

use App\Http\traits\Sections;
use Illuminate\Http\Request;

class SectionsController extends Controller
{
    //
    public function index(){
        return Sections::all_sections();
    }
}
