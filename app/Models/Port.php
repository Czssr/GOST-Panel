<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Port extends BaseModel {
    protected $fillable = [
        'user_id', 'node_id', 'node_port', 'server_port',
        'last_dosage', 'total_dosage', 'last_dosage_time',
        'status',
    ];



    public function hasOneNode():HasOne {
        return $this->hasOne(Node::class, 'id', 'node_id');
    }
}
