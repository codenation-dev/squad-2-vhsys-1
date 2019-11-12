<?php

namespace App\Http\Controllers;

use App\Log;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Integer;
use JWTAuth;

class LogController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }


    //Nesse get temos o default: ambiente eh producao e o log nao esta arquivado
    public function index()
    {
        return $this->user
            ->logs()
            ->where('arquivado',false)
            ->where('ambiente','producao')
            ->get()
            ->toArray();
    }


    public function show(Integer $id)
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

    public function store (Request $request)
    {
        $this->validate($request, [

            //Criar mais validates para os campos poderem assumir apenas os valores do dominio correto
            //Criar Request personalizada e fazer a validacao em nessa outra classe de request

            'ambiente' => 'required',
            'level' => 'required',
            'descricao' => 'required',
            'origem' => 'required',
            'eventos' => 'required|integer', //Tirar duvida do que seria esse campo
            'detalhe' =>'required', //Tirar duvida se realmente detalhe eh required e se ele eh um campo do tipo text mesmo
            'titulo' => 'required' //Tirar duvida se realmente eh required
        ]);

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

    public function findLevel(String $level)
    {
        $logs = $this->user
            ->logs()
            ->where('level', $level)
            ->get()
            ->toArray();

        if(!$logs) {
            return response()->json([
                'success' => false,
                'message' => 'O log com o Level ' . $level . ' não foi encontrado.'
            ], 400);
        }

        return $logs;        
    }

    public function findDescricao(String $descricao)
    {
        $logs = $this->user
            ->logs()
            ->where('descricao', $descricao)
            ->get()
            ->toArray();

        if(!$logs) {
            return response()->json([
                'success' => false,
                'message' => 'O log com a Descrição ' . $descricao . ' não foi encontrado.'
            ], 400);
        }

        return $logs; 
    }

    public function findOrigem(String $origem)
    {
        $logs = $this->user
            ->logs()
            ->where('origem', $origem)
            ->get()
            ->toArray();

        if(!$logs) {
            return response()->json([
                'success' => false,
                'message' => 'O log com a origem ' . $origem . ' não foi encontrado.'
            ], 400);
        }

        return $logs; 
    }

}
