<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = "comment";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];
}
