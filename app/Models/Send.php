<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Send extends Model
{
    protected $table = "send";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];
}
