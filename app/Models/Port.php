<?php

namespace App\Models;

class Port extends BaseModel {
    protected $fillable = [
        'user_id', 'node_id', 'node_port', 'server_port',
        'last_dosage', 'total_dosage', 'last_dosage_time',
        'status',
    ];


}
