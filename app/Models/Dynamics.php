<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dynamics extends Model
{
    protected $table = "dynamics";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];
}
