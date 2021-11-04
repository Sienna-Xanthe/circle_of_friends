<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personality extends Model
{
    protected $table = "personality";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];
}
