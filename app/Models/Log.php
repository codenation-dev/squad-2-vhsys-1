<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    const DEV = 'dev';
    const PRODUCAO = 'produção';
    const HOMOLOGACAO = 'homologação';
    public static $TipoAmbienteLogs = [self::DEV, self::PRODUCAO, self::HOMOLOGACAO];

    public static $FiltroLogs = ['level', 'descricao', 'origem'];

    public static $OrdenacaoLogs = ['level', 'frequencia'];

    protected $fillable = [
        'ambiente','level','descricao','origem','arquivado','eventos','detalhe','titulo'
    ];
}