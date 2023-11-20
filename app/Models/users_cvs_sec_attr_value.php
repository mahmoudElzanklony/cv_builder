<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class users_cvs_sec_attr_value extends Model
{
    use HasFactory;

    protected $fillable = ['user_cv_section_id','attribute_id','answer','answer_type','content_width'];
}
