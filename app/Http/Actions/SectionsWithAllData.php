<?php


namespace App\Http\Actions;


use App\Models\sections;

class SectionsWithAllData
{
    public static function get_data(){
        return sections::query()->with(['attributes.image','image']);
    }
}
