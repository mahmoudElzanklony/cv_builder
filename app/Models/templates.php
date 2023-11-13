<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class templates extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['user_id','category_id','name','type','visibility'];

    public function category(){
        return $this->belongsTo(categories::class,'category_id');
    }
}
