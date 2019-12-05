<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $appends = ['eventos'];

    public function getEventosAttribute()
    {
        return LogsOcorrencia::where('id_log', $this->id)->count();
    }

    const DEV = 'dev';
    const PRODUCAO = 'produção';
    const HOMOLOGACAO = 'homologação';
    public static $TipoAmbienteLogs = [self::DEV, self::PRODUCAO, self::HOMOLOGACAO];

    const ERROR = 'error';
    const WARNING = 'warning';
    const DEBUG = 'debug';
    public static $TipoLevelLogs = [self::ERROR, self::WARNING, self::DEBUG];

    public static $FiltroLogs = ['level', 'descricao', 'origem'];

    public static $OrdenacaoLogs = ['level', 'frequencia'];

    protected $fillable = [
        'ambiente','level','descricao','origem','arquivado','detalhe','titulo'
    ];

    public function logsOcorrencias()
    {
        return $this->hasMany(LogsOcorrencia::class);
    }
}
