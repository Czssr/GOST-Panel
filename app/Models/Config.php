<?php

namespace App\Models;

class Config extends BaseModel {
    protected $fillable = [
        'name', 'key', 'value', 'suffix', 'status',
    ];


}
