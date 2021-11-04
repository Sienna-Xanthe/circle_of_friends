<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Background extends Model
{
    protected $table = "background";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];
}
