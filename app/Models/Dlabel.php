<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dlabel extends Model
{
    protected $table = "dlabel";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];
}
