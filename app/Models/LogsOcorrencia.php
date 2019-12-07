<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogsOcorrencia extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'log_id'
    ];

    public function logs()
    {
        return $this->belongsTo('App\Models\Log', 'id', 'log_id');
    }
    
    public function users()
    {
        return $this->belongsTo('App\Models\User', 'id', 'user_id');
    }    
}
