<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    protected $fillable = [ 'detalhe', 'created_at'];

    public function log()
    {
        $this->belongsTo(Log::class);
    }
}
