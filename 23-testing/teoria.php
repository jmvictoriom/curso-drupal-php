<?php

/**
 * =============================================================
 *  LECCION 23: TESTING EN DRUPAL
 * =============================================================
 *  PHPUnit, Unit Tests, Kernel Tests, Functional Tests y
 *  BrowserTestBase. Como escribir y ejecutar tests en Drupal.
 * =============================================================
 */

echo "=== 1. POR QUE TESTEAR ===" . PHP_EOL;
echo PHP_EOL;
echo "- Detectar bugs antes de que lleguen a produccion" . PHP_EOL;
echo "- Refactorizar con confianza" . PHP_EOL;
echo "- Documentar el comportamiento esperado del codigo" . PHP_EOL;
echo "- Drupal core tiene +30,000 tests; los modulos contrib buenos tambien" . PHP_EOL;
echo PHP_EOL;
echo "Drupal usa PHPUnit como framework de testing." . PHP_EOL;
echo "Los tests van en el directorio tests/ de tu modulo." . PHP_EOL;
echo PHP_EOL;

echo "=== 2. TIPOS DE TESTS EN DRUPAL ===" . PHP_EOL;
echo PHP_EOL;
echo "Tipo              | Base Class              | Velocidad | Que testea" . PHP_EOL;
echo "------------------|-------------------------|-----------|---------------------------" . PHP_EOL;
echo "Unit              | UnitTestCase            | Rapido    | Logica pura PHP, sin Drupal" . PHP_EOL;
echo "Kernel            | KernelTestBase          | Medio     | Servicios, BD, entidades" . PHP_EOL;
echo "Functional        | BrowserTestBase         | Lento     | UI completa con navegador" . PHP_EOL;
echo "FunctionalJs      | WebDriverTestBase       | Muy lento | JS interactivo (AJAX, etc)" . PHP_EOL;
echo PHP_EOL;
echo "Regla: usa el tipo mas ligero que cubra lo que necesitas." . PHP_EOL;
echo PHP_EOL;


echo "=== 3. ESTRUCTURA DE ARCHIVOS ===" . PHP_EOL;
echo PHP_EOL;
echo "web/modules/custom/mi_modulo/" . PHP_EOL;
echo "├── mi_modulo.info.yml" . PHP_EOL;
echo "├── mi_modulo.services.yml" . PHP_EOL;
echo "├── src/" . PHP_EOL;
echo "│   └── Service/" . PHP_EOL;
echo "│       └── Calculadora.php" . PHP_EOL;
echo "└── tests/" . PHP_EOL;
echo "    └── src/" . PHP_EOL;
echo "        ├── Unit/" . PHP_EOL;
echo "        │   └── CalculadoraTest.php" . PHP_EOL;
echo "        ├── Kernel/" . PHP_EOL;
echo "        │   └── CalculadoraServiceTest.php" . PHP_EOL;
echo "        └── Functional/" . PHP_EOL;
echo "            └── CalculadoraFormTest.php" . PHP_EOL;
echo PHP_EOL;


echo "=== 4. UNIT TEST ===" . PHP_EOL;
echo PHP_EOL;
echo "Testea logica PHP pura, sin base de datos ni servicios de Drupal." . PHP_EOL;
echo PHP_EOL;

echo "Primero, el servicio a testear:" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
<?php
// src/Service/Calculadora.php

namespace Drupal\mi_modulo\Service;

class Calculadora {

  public function sumar(float $a, float $b): float {
    return $a + $b;
  }

  public function restar(float $a, float $b): float {
    return $a - $b;
  }

  public function multiplicar(float $a, float $b): float {
    return $a * $b;
  }

  public function dividir(float $a, float $b): float {
    if ($b == 0) {
      throw new \InvalidArgumentException('No se puede dividir entre cero.');
    }
    return $a / $b;
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "Ahora el Unit Test:" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
<?php
// tests/src/Unit/CalculadoraTest.php

namespace Drupal\Tests\mi_modulo\Unit;

use Drupal\mi_modulo\Service\Calculadora;
use Drupal\Tests\UnitTestCase;

/**
 * Tests para el servicio Calculadora.
 *
 * @group mi_modulo
 * @coversDefaultClass \Drupal\mi_modulo\Service\Calculadora
 */
class CalculadoraTest extends UnitTestCase {

  protected Calculadora $calculadora;

  protected function setUp(): void {
    parent::setUp();
    // Crear instancia directa (no necesita contenedor)
    $this->calculadora = new Calculadora();
  }

  /**
   * @covers ::sumar
   */
  public function testSumar(): void {
    $this->assertEquals(5, $this->calculadora->sumar(2, 3));
    $this->assertEquals(0, $this->calculadora->sumar(-1, 1));
    $this->assertEquals(-5, $this->calculadora->sumar(-2, -3));
  }

  /**
   * @covers ::restar
   */
  public function testRestar(): void {
    $this->assertEquals(1, $this->calculadora->restar(3, 2));
  }

  /**
   * @covers ::multiplicar
   */
  public function testMultiplicar(): void {
    $this->assertEquals(6, $this->calculadora->multiplicar(2, 3));
    $this->assertEquals(0, $this->calculadora->multiplicar(0, 100));
  }

  /**
   * @covers ::dividir
   */
  public function testDividir(): void {
    $this->assertEquals(2, $this->calculadora->dividir(6, 3));
  }

  /**
   * @covers ::dividir
   */
  public function testDividirEntreCero(): void {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('No se puede dividir entre cero.');
    $this->calculadora->dividir(5, 0);
  }

  /**
   * Test con dataProvider para multiples casos.
   *
   * @dataProvider datosSuma
   * @covers ::sumar
   */
  public function testSumarConProvider(float $a, float $b, float $esperado): void {
    $this->assertEquals($esperado, $this->calculadora->sumar($a, $b));
  }

  public static function datosSuma(): array {
    return [
      'positivos' => [2, 3, 5],
      'negativos' => [-2, -3, -5],
      'mixtos' => [-1, 1, 0],
      'decimales' => [1.5, 2.5, 4.0],
    ];
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "Puntos clave del Unit Test:" . PHP_EOL;
echo "  - Extiende UnitTestCase" . PHP_EOL;
echo "  - @group define el grupo (nombre del modulo)" . PHP_EOL;
echo "  - setUp() inicializa lo que necesitas" . PHP_EOL;
echo "  - Metodos test* son los tests individuales" . PHP_EOL;
echo "  - @dataProvider permite multiples casos de prueba" . PHP_EOL;
echo "  - expectException() para verificar que lanza excepciones" . PHP_EOL;
echo PHP_EOL;


echo "=== 5. KERNEL TEST ===" . PHP_EOL;
echo PHP_EOL;
echo "Testea con el contenedor de servicios y base de datos," . PHP_EOL;
echo "pero sin la interfaz web completa." . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
<?php
// tests/src/Kernel/CalculadoraServiceTest.php

namespace Drupal\Tests\mi_modulo\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\mi_modulo\Service\Calculadora;

/**
 * Kernel test para el servicio Calculadora.
 *
 * @group mi_modulo
 */
class CalculadoraServiceTest extends KernelTestBase {

  // Modulos que necesita este test
  protected static $modules = ['mi_modulo'];

  public function testServicioExiste(): void {
    // Verificar que el servicio esta registrado en el contenedor
    $servicio = $this->container->get('mi_modulo.calculadora');
    $this->assertInstanceOf(Calculadora::class, $servicio);
  }

  public function testSumarDesdeServicio(): void {
    $calculadora = $this->container->get('mi_modulo.calculadora');
    $this->assertEquals(10, $calculadora->sumar(4, 6));
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "Para que funcione, necesitas el services.yml:" . PHP_EOL;
echo PHP_EOL;
$servicios = <<<'YAML'
# mi_modulo.services.yml
services:
  mi_modulo.calculadora:
    class: Drupal\mi_modulo\Service\Calculadora
YAML;
echo $servicios . PHP_EOL;
echo PHP_EOL;

echo "Puntos clave del Kernel Test:" . PHP_EOL;
echo "  - Extiende KernelTestBase" . PHP_EOL;
echo '  - $modules lista los modulos necesarios (se instalan para el test)' . PHP_EOL;
echo '  - $this->container para acceder al contenedor de servicios' . PHP_EOL;
echo "  - Se crea una BD SQLite en memoria" . PHP_EOL;
echo PHP_EOL;


echo "=== 6. FUNCTIONAL TEST (BrowserTestBase) ===" . PHP_EOL;
echo PHP_EOL;
echo "Testea la interfaz completa: formularios, paginas, permisos." . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
<?php
// tests/src/Functional/CalculadoraFormTest.php

namespace Drupal\Tests\mi_modulo\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Test funcional del formulario de calculadora.
 *
 * @group mi_modulo
 */
class CalculadoraFormTest extends BrowserTestBase {

  protected static $modules = ['mi_modulo'];

  // Tema a usar en los tests
  protected $defaultTheme = 'stark';

  public function testFormularioAccesible(): void {
    // Crear usuario con permiso y hacer login
    $usuario = $this->drupalCreateUser(['access content']);
    $this->drupalLogin($usuario);

    // Visitar la pagina del formulario
    $this->drupalGet('/calculadora');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Calculadora');
  }

  public function testFormularioSuma(): void {
    $usuario = $this->drupalCreateUser(['access content']);
    $this->drupalLogin($usuario);

    $this->drupalGet('/calculadora');

    // Enviar el formulario
    $this->submitForm([
      'numero_a' => '5',
      'numero_b' => '3',
      'operacion' => 'sumar',
    ], 'Calcular');

    // Verificar el resultado
    $this->assertSession()->pageTextContains('Resultado: 8');
  }

  public function testAccesoDenegadoParaAnonimos(): void {
    // Sin hacer login
    $this->drupalGet('/calculadora');
    $this->assertSession()->statusCodeEquals(403);
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "Puntos clave del Functional Test:" . PHP_EOL;
echo '  - Extiende BrowserTestBase' . PHP_EOL;
echo '  - $defaultTheme es obligatorio (usa "stark" para tests)' . PHP_EOL;
echo '  - drupalCreateUser() crea usuarios con permisos' . PHP_EOL;
echo '  - drupalLogin() hace login como ese usuario' . PHP_EOL;
echo '  - drupalGet() navega a una URL' . PHP_EOL;
echo '  - submitForm() envia formularios' . PHP_EOL;
echo '  - assertSession() para verificar la pagina' . PHP_EOL;
echo PHP_EOL;


echo "=== 7. TESTEAR SERVICIOS CON MOCKS ===" . PHP_EOL;
echo PHP_EOL;
echo "Usa mocks para aislar lo que testeas de sus dependencias." . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
<?php
// Ejemplo: testear un servicio que depende de otro

namespace Drupal\Tests\mi_modulo\Unit;

use Drupal\mi_modulo\Service\ReporteService;
use Drupal\mi_modulo\Service\Calculadora;
use Drupal\Tests\UnitTestCase;

class ReporteServiceTest extends UnitTestCase {

  public function testGenerarReporte(): void {
    // Crear un mock de Calculadora
    $calculadoraMock = $this->createMock(Calculadora::class);

    // Configurar el mock: cuando llamen a sumar(10, 20), devolver 30
    $calculadoraMock->method('sumar')
      ->with(10, 20)
      ->willReturn(30);

    // Inyectar el mock al servicio que estamos testeando
    $reporte = new ReporteService($calculadoraMock);
    $resultado = $reporte->generarReporte(10, 20);

    $this->assertEquals('Total: 30', $resultado);
  }

}
CODE;
echo PHP_EOL . PHP_EOL;


echo "=== 8. EJECUTAR TESTS CON DDEV ===" . PHP_EOL;
echo PHP_EOL;
echo "Configurar phpunit.xml en la raiz del proyecto:" . PHP_EOL;
echo PHP_EOL;
$phpunit = <<<'XML'
<!-- phpunit.xml (en la raiz del proyecto) -->
<phpunit bootstrap="web/core/tests/bootstrap.php">
  <testsuites>
    <testsuite name="custom">
      <directory>web/modules/custom/*/tests/src</directory>
    </testsuite>
  </testsuites>
  <php>
    <env name="SIMPLETEST_BASE_URL" value="https://mi-proyecto.ddev.site"/>
    <env name="SIMPLETEST_DB" value="mysql://db:db@db:3306/db"/>
    <env name="BROWSERTEST_OUTPUT_DIRECTORY" value="/var/www/html/web/sites/simpletest/browser_output"/>
  </php>
</phpunit>
XML;
echo $phpunit . PHP_EOL;
echo PHP_EOL;

echo "Comandos para ejecutar tests:" . PHP_EOL;
echo PHP_EOL;
echo "# Ejecutar TODOS los tests de tu modulo" . PHP_EOL;
echo "ddev exec phpunit --group mi_modulo" . PHP_EOL;
echo PHP_EOL;
echo "# Ejecutar solo Unit tests" . PHP_EOL;
echo "ddev exec phpunit web/modules/custom/mi_modulo/tests/src/Unit/" . PHP_EOL;
echo PHP_EOL;
echo "# Ejecutar solo Kernel tests" . PHP_EOL;
echo "ddev exec phpunit web/modules/custom/mi_modulo/tests/src/Kernel/" . PHP_EOL;
echo PHP_EOL;
echo "# Ejecutar solo Functional tests" . PHP_EOL;
echo "ddev exec phpunit web/modules/custom/mi_modulo/tests/src/Functional/" . PHP_EOL;
echo PHP_EOL;
echo "# Ejecutar un test especifico" . PHP_EOL;
echo "ddev exec phpunit --filter testSumar web/modules/custom/mi_modulo/tests/src/Unit/CalculadoraTest.php" . PHP_EOL;
echo PHP_EOL;
echo "# Ver output detallado" . PHP_EOL;
echo "ddev exec phpunit --verbose --group mi_modulo" . PHP_EOL;
echo PHP_EOL;


echo "=== 9. ASSERTIONS COMUNES ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
// Igualdad
$this->assertEquals($esperado, $actual);
$this->assertSame($esperado, $actual);  // estricto (tipo + valor)
$this->assertNotEquals($valor, $actual);

// Booleanos
$this->assertTrue($valor);
$this->assertFalse($valor);
$this->assertNull($valor);

// Tipos y clases
$this->assertInstanceOf(MiClase::class, $objeto);
$this->assertIsArray($valor);
$this->assertIsString($valor);

// Strings
$this->assertStringContainsString('texto', $cadena);
$this->assertMatchesRegularExpression('/patron/', $cadena);

// Arrays
$this->assertCount(3, $array);
$this->assertArrayHasKey('clave', $array);
$this->assertContains($elemento, $array);

// Excepciones
$this->expectException(\Exception::class);
$this->expectExceptionMessage('mensaje');

// Drupal - BrowserTestBase
$this->assertSession()->statusCodeEquals(200);
$this->assertSession()->pageTextContains('texto');
$this->assertSession()->fieldExists('nombre_campo');
$this->assertSession()->linkExists('Texto del enlace');
$this->assertSession()->elementExists('css', '.mi-clase');
CODE;
echo PHP_EOL . PHP_EOL;


echo "=== RESUMEN ===" . PHP_EOL;
echo "1. Unit Tests: logica pura, rapidos, UnitTestCase" . PHP_EOL;
echo "2. Kernel Tests: servicios + BD, KernelTestBase" . PHP_EOL;
echo "3. Functional Tests: UI completa, BrowserTestBase" . PHP_EOL;
echo "4. Usa @group para agrupar tests por modulo" . PHP_EOL;
echo "5. @dataProvider para multiples casos de prueba" . PHP_EOL;
echo "6. Mocks para aislar dependencias" . PHP_EOL;
echo "7. phpunit.xml configura la conexion a BD y URL base" . PHP_EOL;
echo "8. ddev exec phpunit para ejecutar tests" . PHP_EOL;
echo PHP_EOL;
echo "Siguiente: Leccion 24 - Drush y herramientas de desarrollo" . PHP_EOL;
