<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Model\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $id = 1;
        $response = $this->put('/log/'.$id);

        $response->assertStatus(200);

        //$this->assertSame($action($request, ['id' => $filme->id])->getStatusCode(), 204, 'O codigo de retorno para criacao de recurso esta errado');
        $this->assertSame(Log::find($id)->arquivado, true, 'O log Não foi arquivado.');



    }
}
