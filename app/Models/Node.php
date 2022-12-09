<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Node extends BaseModel {
    protected $fillable = [
        'ip', 'metrics_prefix', 'metrics_port', 'panel_prefix', 'panel_port', 'port', 'auth', 'level', 'type',
        'remark', 'status',
    ];


    public function hasManyPort():HasMany {
        return $this->hasMany(Port::class, 'node_id');
    }

}
