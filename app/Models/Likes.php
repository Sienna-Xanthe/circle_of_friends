<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Likes extends Model
{
    protected $table = "likes";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];
}
