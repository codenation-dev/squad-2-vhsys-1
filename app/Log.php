<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'ambiente','level','descricao','origem','arquivado','eventos','detalhe','titulo'
    ];

//    public function details()
//    {
//        return $this->hasMany(Detail::class);
//    }

}
