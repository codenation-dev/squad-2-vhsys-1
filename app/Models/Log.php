<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'ambiente','level','descricao','origem','arquivado','detalhe','titulo'
    ];

    const DEV = 'dev';
    const PRODUCAO = 'produção';
    const HOMOLOGACAO = 'homologação';
    public static $TipoAmbienteLogs = [self::DEV, self::PRODUCAO, self::HOMOLOGACAO];

    const ERROR = 'error';
    const WARNING = 'warning';
    const DEBUG = 'debug';
    public static $TipoLevelLogs = [self::ERROR, self::WARNING, self::DEBUG];

    public static $FiltroLogs = ['level', 'descricao', 'origem'];

    public static $OrdenacaoLogs = ['level', 'eventos'];

    public function logsOcorrencias()
    {
        return $this->hasMany(LogsOcorrencia::class, 'log_id');
    }
}
