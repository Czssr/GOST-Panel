<?php

namespace App\Models;

use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable implements JWTSubject {
    use SoftDeletes, Notifiable, HasJsonRelationships;

    protected $fillable = [
        'invite_id', 'relations', 'username', 'password',
        'nickname', 'avatar', 'status',
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'relations' => 'json'
    ];

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() :array {
        return [];
    }

}
