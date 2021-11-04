<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blacklist extends Model
{
    protected $table = "blacklist";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];
}
