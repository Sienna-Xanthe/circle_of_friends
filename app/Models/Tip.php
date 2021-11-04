<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tip extends Model
{
    protected $table = "tip";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];
}
