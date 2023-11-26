<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class templates_sections extends Model
{
    use HasFactory;

    protected $fillable = ['template_id','section_id','content_width'];
}
