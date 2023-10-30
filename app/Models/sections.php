<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sections extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','ar_name','en_name','ar_info','en_info'];

    public function attributes(){
        return $this->belongsToMany(attributes::class,sections_attributes::class,'section_id','attribute_id');
    }
}
