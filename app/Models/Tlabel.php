<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tlabel extends Model
{
    protected $table = "tlabel";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];

    public static function getXlkTipLabel()
    {
        try {
            $res = self::select([
                'id',
                'tlabel_name'
            ])
                ->get();
            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('搜索错误', [$e->getMessage()]);
            return false;
        }
    }


}
