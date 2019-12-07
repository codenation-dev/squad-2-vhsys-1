<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\LogsOcorrencia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use JWTAuth;

class LogController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function filter(Request $request)
    {
        try {
            $request->validate([
                'ambiente' => [Rule::in(Log::$TipoAmbienteLogs), 'required'],
                'chave' => ['nullable', Rule::in(Log::$FiltroLogs)],
                'valor' => ['nullable'],
                'order' => ['nullable', Rule::in(Log::$OrdenacaoLogs)]
            ]);
        } catch(ValidationException $exception)
        {
            return response('Parâmetros Inválidos', 400);
        }

        $ambiente = $request->get('ambiente');
        $chave = $request->get('chave');
        $valor = $request->get('valor');
        $order = $request->get('order');

        $qq = Log::where('ambiente', $ambiente)
                    ->where('arquivado', 0);

        if (($chave !== '') && ($valor !== ''))
        {
            $qq->where($chave, $valor);
        }

        if ($order !== '')
        {
            $qq->orderBy($order);
        }

        $logs = $qq
                ->paginate(10);

        return $logs;
    }

    public function show($id)
    {
        $log = Log::find($id);

        if(!$log) {
            return response()->json([
                'success' => false,
                'message' => 'Log com o ID ' . $id . ' não encontrado.'
            ], 400);
        }

        return $log;
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'ambiente' => [Rule::in(Log::$TipoAmbienteLogs), 'required'],
                'level' => [Rule::in(Log::$TipoLevelLogs), 'required'],
                'descricao' => 'required',
                'origem' => 'required',
                'detalhe' =>'required',
                'titulo' => 'required']);
        } catch(ValidationException $exception)
        {
            return response('Parâmetros Inválidos', 400);
        }

        $log = Log::where('ambiente', $request->ambiente)
            ->where('level', $request->level)
            ->where('descricao', $request->descricao)
            ->where('titulo', $request->titulo)
            ->get()
            ->first();

        if (!$log) {
            $log = new Log();
            $log->ambiente = $request->ambiente;
            $log->level = $request->level;
            $log->descricao = $request->descricao;
            $log->origem = $request->origem;
            $log->detalhe = $request->detalhe;
            $log->titulo = $request->titulo;
            $log->arquivado = false;
            if (!($log->save()))
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Não foi possível adicionar o Log.'
                ]);
            }
        }

        $logOcorrencia = new LogsOcorrencia();
        $logOcorrencia->log_id = $log->id;
        if ($this->user->logsOcorrencias()->save($logOcorrencia)) {
            $log->eventos = $log->logsOcorrencias->count('id');
            if ($log->save()) {
                return response()->json([
                    'success' => true,
                    'log' => $log
                ]);
            }
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível adicionar o Log.'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $log = Log::find($id);

        if(!$log) {
            return response()->json([
                'success' => false,
                'message' => 'Log com o ID ' . $id . ' não encontrado.'
            ], 400);
        }

        $data_update = $log->fill($request->all())->save();

        if ($data_update) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Log não pode ser atualizado.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $log = Log::find($id);

        if(!$log) {
            return response()->json([
                'success' => false,
                'message' => 'Log com o ID ' . $id . ' não encontrado.'
            ], 400);
        }

        if ($log->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Log não pode ser excluído.'
            ], 500);
        }

    }
}
