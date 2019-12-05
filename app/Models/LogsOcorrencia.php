<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogsOcorrencia extends Model
{
  /*
    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
            ->where('id_log', '=', $this->getAttribute('id_log'))
            ->where('data_inclusao', '=', $this->getAttribute('data_inclusao'));
        return $query;
    }
*/
    public function logs()
    {
        return $this->belongsTo('App\Models\Log');
    }
}
