<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements JWTSubject {
    use SoftDeletes, Notifiable, HasJsonRelationships;

    protected $fillable = [
        'invite_id', 'relations', 'username', 'password', 'auth', 'email',
        'nickname', 'avatar', 'level', 'traffic', 'reset_traffic', 'status',
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'relations' => 'json'
    ];

    protected $appends = [
        'avatar_url'
    ];

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return '';
        } else {
            return '';
        }
    }


    /**
     * 用户流量统计
     * @return HasMany
     */
    public function hasManyFlow():HasMany {
        return $this->hasMany(UserFlow::class, 'user_id');
    }

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() :array {
        return [];
    }

}
