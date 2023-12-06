<?php

namespace App\Http\Controllers;

use App\Http\traits\SectionsHelper;
use Illuminate\Http\Request;

class SectionsController extends Controller
{
    //
    public function index(){
        return SectionsHelper::all_sections();
    }

    public function per_template(){
        return SectionsHelper::per_template(request('template_id'));
    }
}
