<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $table = "collection";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];
}
