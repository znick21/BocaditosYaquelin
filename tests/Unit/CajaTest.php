<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Caja;

class CajaTest extends TestCase
{
    /** @test */
    public function caja_puede_instanciarse_correctamente()
    {
        $caja = new Caja(['estado' => 'abierta']);
        $this->assertTrue($caja->estaAbierta());
    }

    /** @test */
    public function estado_cerrado_se_valida_correctamente()
    {
        $caja = new Caja(['estado' => 'cerrada']);
        $this->assertFalse($caja->estaAbierta());
    }
}
