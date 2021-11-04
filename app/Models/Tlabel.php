<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tlabel extends Model
{
    protected $table = "tlabel";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];
}
