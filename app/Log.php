<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    const DEV = 'dev';
    const PRODUCAO = 'produção';
    const HOMOLOGACAO = 'homologação';
    public static $TipoAmbienteLogs = [self::DEV, self::PRODUCAO, self::HOMOLOGACAO];

    protected $fillable = [
        'ambiente','level','descricao','origem','arquivado','eventos','detalhe','titulo'
    ];
}
