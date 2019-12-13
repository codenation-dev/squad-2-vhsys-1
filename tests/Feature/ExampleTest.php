<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Log;
use JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testArquivarLogs()
    {
        $log = new Log();
        $log->ambiente = 'dev';
        $log->level = 'error';
        $log->descricao = 'teste';
        $log->origem = 'teste';
        $log->detalhe = 'teste';
        $log->titulo = 'teste';
        $log->save();

        $id = $log->id;

        $user = factory(\App\Models\User::class)->create();
        $token = JWTAuth::fromUser($user);
        $response = $this->actingAs($user)
            ->put(route('update', ['id' => $id]), [
                'arquivado' => '1',
                 'token' => $token
        ]);


        $this->assertSame($response->getStatusCode(), 200, 'O codigo de retorno para criacao de recurso esta errado');
        $this->assertSame(Log::find($id)->arquivado, 1, 'O log Não foi arquivado.');
    }

    public function testStoreLogs()
    {
        $user = factory(\App\Models\User::class)->create();
        $token = JWTAuth::fromUser($user);
        $response = $this->actingAs($user)
            ->post(route('store'), [
                'ambiente' => 'dev',
                'level' => 'debug',
                'descricao' => 'teste',
                'origem' => 'cadastro',
                'detalhe' => 'teste detalhe',
                'titulo' => 'teste titulo',
                'token' => $token
            ]);

        if ($response->assertStatus(200))
        {
            $log = json_decode($response->getContent())->log;
        }

        $this->assertSame($response->getStatusCode(), 200, 'O codigo de retorno para criacao de recurso esta errado');
        $this->assertSame(!($log = null), true, 'O log Não foi inserido.');
    }

    public function testStoreLogsInvalidParameters()
    {
        $user = factory(\App\Models\User::class)->create();
        $token = JWTAuth::fromUser($user);
        $response = $this->actingAs($user)
            ->post(route('store'), [
                'ambiente' => 'devs',
                'level' => 'debug',
                'descricao' => 'teste',
                'origem' => 'cadastro',
                'detalhe' => 'teste detalhe',
                'titulo' => 'teste titulo',
                'token' => $token
            ]);

        $this->assertSame($response->getStatusCode(), 400, 'O codigo de retorno para criacao de recurso esta errado');
    }

    public function testUpdateLogs()
    {
        $log = new Log();
        $log->ambiente = 'dev';
        $log->level = 'error';
        $log->descricao = 'teste';
        $log->origem = 'teste';
        $log->detalhe = 'teste';
        $log->titulo = 'teste';
        $log->save();

        $id = $log->id;

        $user = factory(\App\Models\User::class)->create();
        $token = JWTAuth::fromUser($user);
        $response = $this->actingAs($user)
            ->put(route('update', ['id' => $id]), [
                'level' => 'warning',
                'token' => $token
            ]);

        $this->assertSame($response->getStatusCode(), 200, 'O codigo de retorno para atualização de recurso esta errado');
        $this->assertSame(Log::find($id)->level, 'warning', 'O log não foi atualizado.');
    }

    public function testDeleteLogs()
    {
        $log = new Log();
        $log->ambiente = 'dev';
        $log->level = 'error';
        $log->descricao = 'teste';
        $log->origem = 'teste';
        $log->detalhe = 'teste';
        $log->titulo = 'teste';
        $log->save();

        $id = $log->id;

        $user = factory(\App\Models\User::class)->create();
        $token = JWTAuth::fromUser($user);
        $response = $this->actingAs($user)
            ->delete(route('delete', ['id' => $id]), [
                'token' => $token
            ]);

        $this->assertSame($response->getStatusCode(), 200, 'O codigo de retorno para exclusão esta errado');
        $this->assertSame(Log::find($id), null, 'O log não foi excluído.');
    }
}
