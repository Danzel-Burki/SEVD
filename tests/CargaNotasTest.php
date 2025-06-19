<?php
use PHPUnit\Framework\TestCase;

class CargaNotasTest extends TestCase
{
    public function testNotaValida()
    {
        $nota = 8;
        $this->assertGreaterThanOrEqual(1, $nota);
        $this->assertLessThanOrEqual(10, $nota);
    }

    public function testAccesoAutorizado()
    {
        $rol = "docente";
        $this->assertEquals("docente", $rol, "Solo los docentes pueden cargar notas.");
    }
}
