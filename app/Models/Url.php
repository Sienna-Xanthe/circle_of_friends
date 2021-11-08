<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $table = "url";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];

    public function dynamics1(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Dynamics::class,'id');
    }
}
