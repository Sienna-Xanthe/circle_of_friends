<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flower extends Model
{
    protected $table = "flower";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];
}
