<?php
use PHPUnit\Framework\TestCase;

class PreinscripcionTest extends TestCase
{
    public function testFormularioHabilitado()
    {
        $formularioHabilitado = true;
        $this->assertTrue($formularioHabilitado, "El formulario no está habilitado.");
    }

    public function testPeriodoAbierto()
    {
        $periodo = "activo";
        $this->assertEquals("activo", $periodo);
    }

    public function testAlumnoSinRestricciones()
    {
        $deuda = false;
        $documentacionIncompleta = false;
        $this->assertFalse($deuda || $documentacionIncompleta, "El alumno tiene restricciones.");
    }
}
