<?php

namespace App\Http\Controllers;

use App\Models\Log;
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

        $qb = $this->user
            ->logs();

        if ($ambiente)
            $qb->where('ambiente', $ambiente);

        if (($chave) && ($valor))
            $qb->where($chave, $valor);

        if ($order)
            $qb->orderBy($order);

        $logs = $qb
            ->get()
            ->toArray();

        return $logs;
    }

    public function show($id)
    {
        $log = $this->user->logs()->find($id);

        if(!$log) {
            return response()->json([
                'success' => false,
                'message' => 'Product with id ' . $id . ' cannot be found'
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
                'eventos' => 'required|integer', //Tirar duvida do que seria esse campo
                'detalhe' =>'required',
                'titulo' => 'required'
        ]);
        } catch(ValidationException $exception)
        {
            return response('Parâmetros Inválidos', 400);
        }

        $log = new Log();
        $log->ambiente = $request->ambiente;
        $log->level = $request->level;
        $log->descricao = $request->descricao;
        $log->origem = $request->origem;
        $log->eventos = $request->eventos;
        $log->detalhe = $request->detalhe;
        $log->titulo = $request->titulo;
        $log->arquivado = false;

        if ($this->user->logs()->save($log)) {
            return response()->json([
                'success' => true,
                'log' => $log
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Product could not be added'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $log = $this->user->logs()->find($id);

        if(!$log) {
            return response()->json([
                'success' => false,
                'message' => 'Product with id ' .$id. ' cannot be found'
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
                'message' => 'Product could not be updated'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $log = $this->user->logs()->find($id);


        if(!$log) {
            return response()->json([
                'success' => false,
                'message' => 'Product with id ' . $id . 'cannot be found'
            ], 400);
        }

        if ($log->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product could not be deleted'
            ], 500);
        }

    }
}
