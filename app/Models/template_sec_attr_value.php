<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class template_sec_attr_value extends Model
{
    protected $fillable = ['template_section_id','attribute_id','answer','answer_type','content_width'];
}
