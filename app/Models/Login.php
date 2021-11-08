<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Login extends \Illuminate\Foundation\Auth\User implements JWTSubject,Authenticatable
{
    protected $table = "login";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];
    protected $fillable = ['username', 'password'];
    protected $hidden = [
        'password',
    ];


    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getJWTIdentifier()
    {
        return self::getKey();
    }

    /**
     * 创建用户
     *
     * @param array $array
     * @return |null
     * @throws \Exception
     */
    public static function createUser($array = [])
    {
        try {
            $login_id = self::create($array)->username;
            return $login_id ?
                $login_id :
                false;
        } catch (\Exception $e) {
            logError('添加用户失败!', [$e->getMessage()]);
            die($e->getMessage());
            return false;
        }
    }

    protected $attributes = array();

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * 从token中获取id
     * @return string
     */
    public function getAuthIdentifierName()
    {
        // Return the name of unique identifier for the user (e.g. "id")

        return 'id';
    }

    /**
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        // Return the unique identifier for the user (e.g. their ID, 123)
        $identifier_name = $this->getAuthIdentifierName();
        return $this->attributes[$identifier_name];
    }



}
