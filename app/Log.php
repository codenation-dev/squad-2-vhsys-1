<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'ambiente','level','descricao','origem','created_at','arquivado','eventos'
    ];

    public function details()
    {
        return $this->hasMany(Detail::class);
    }

}
