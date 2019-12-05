<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogsOcorrencia extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id_log'
    ];

    public function logs()
    {
        return $this->belongsTo('App\Models\Log', 'Id', 'id_log');
    }
    
    public function users()
    {
        return $this->belongsTo('App\Models\User', 'Id', 'id_user');
    }    
}
