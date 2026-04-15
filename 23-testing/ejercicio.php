<?php

/**
 * =============================================================
 *  EJERCICIO 23: TESTING EN DRUPAL
 * =============================================================
 */

echo "=== EJERCICIO: ESCRIBE TESTS PARA UN MODULO ===" . PHP_EOL;
echo PHP_EOL;

echo "Crea un modulo 'calculadora' con un servicio y un formulario," . PHP_EOL;
echo "y escribe tests para ambos." . PHP_EOL;
echo PHP_EOL;

echo "--- Parte 1: El servicio ---" . PHP_EOL;
echo PHP_EOL;
echo "Crea el servicio Calculadora con estos metodos:" . PHP_EOL;
echo "  - sumar(float, float): float" . PHP_EOL;
echo "  - restar(float, float): float" . PHP_EOL;
echo "  - multiplicar(float, float): float" . PHP_EOL;
echo "  - dividir(float, float): float (lanza excepcion si divisor es 0)" . PHP_EOL;
echo "  - porcentaje(float valor, float porcentaje): float" . PHP_EOL;
echo PHP_EOL;

echo "--- Parte 2: Unit Tests ---" . PHP_EOL;
echo PHP_EOL;
echo "Escribe CalculadoraTest con:" . PHP_EOL;
echo PHP_EOL;
echo "1. testSumar(): verifica 2+3=5, -1+1=0, 0+0=0" . PHP_EOL;
echo "2. testRestar(): verifica 5-3=2, 0-5=-5" . PHP_EOL;
echo "3. testMultiplicar(): verifica 3*4=12, 5*0=0" . PHP_EOL;
echo "4. testDividir(): verifica 10/2=5, 7/2=3.5" . PHP_EOL;
echo "5. testDividirEntreCero(): verifica que lanza InvalidArgumentException" . PHP_EOL;
echo "6. testPorcentaje(): verifica que el 10% de 200 es 20" . PHP_EOL;
echo "7. Crea un @dataProvider con al menos 5 casos para sumar" . PHP_EOL;
echo PHP_EOL;

echo "--- Parte 3: El formulario ---" . PHP_EOL;
echo PHP_EOL;
echo "Crea un formulario en /calculadora con:" . PHP_EOL;
echo "  - Campo 'numero_a' (number)" . PHP_EOL;
echo "  - Campo 'numero_b' (number)" . PHP_EOL;
echo "  - Select 'operacion' (sumar, restar, multiplicar, dividir, porcentaje)" . PHP_EOL;
echo "  - Boton 'Calcular'" . PHP_EOL;
echo "  - Muestra el resultado despues de enviar" . PHP_EOL;
echo PHP_EOL;

echo "--- Parte 4: Functional Test ---" . PHP_EOL;
echo PHP_EOL;
echo "Escribe CalculadoraFormTest con:" . PHP_EOL;
echo PHP_EOL;
echo "1. testFormularioExiste(): verifica que /calculadora responde 200" . PHP_EOL;
echo "2. testSumaDesdeFormulario(): envia 5+3 y verifica 'Resultado: 8'" . PHP_EOL;
echo "3. testRestaDesdeFormulario(): envia 10-4 y verifica 'Resultado: 6'" . PHP_EOL;
echo "4. testDivisionEntreCero(): envia 5/0 y verifica mensaje de error" . PHP_EOL;
echo "5. testAccesoDenegado(): verifica que usuarios anonimos reciben 403" . PHP_EOL;
echo PHP_EOL;

echo "--- Parte 5: Kernel Test (bonus) ---" . PHP_EOL;
echo PHP_EOL;
echo "Escribe CalculadoraServiceTest con:" . PHP_EOL;
echo PHP_EOL;
echo "1. testServicioRegistrado(): verifica que el contenedor tiene el servicio" . PHP_EOL;
echo "2. testServicioEsInstanciaCorrecta(): verifica la clase del servicio" . PHP_EOL;
echo PHP_EOL;

echo "--- Estructura esperada ---" . PHP_EOL;
echo "web/modules/custom/calculadora/" . PHP_EOL;
echo "├── calculadora.info.yml" . PHP_EOL;
echo "├── calculadora.routing.yml" . PHP_EOL;
echo "├── calculadora.services.yml" . PHP_EOL;
echo "├── src/" . PHP_EOL;
echo "│   ├── Service/" . PHP_EOL;
echo "│   │   └── Calculadora.php" . PHP_EOL;
echo "│   └── Form/" . PHP_EOL;
echo "│       └── CalculadoraForm.php" . PHP_EOL;
echo "└── tests/" . PHP_EOL;
echo "    └── src/" . PHP_EOL;
echo "        ├── Unit/" . PHP_EOL;
echo "        │   └── CalculadoraTest.php" . PHP_EOL;
echo "        ├── Kernel/" . PHP_EOL;
echo "        │   └── CalculadoraServiceTest.php" . PHP_EOL;
echo "        └── Functional/" . PHP_EOL;
echo "            └── CalculadoraFormTest.php" . PHP_EOL;
echo PHP_EOL;

echo "--- Ejecutar ---" . PHP_EOL;
echo "ddev exec phpunit --group calculadora --verbose" . PHP_EOL;
echo PHP_EOL;
echo "--- Verificar ---" . PHP_EOL;
echo "Todos los tests deben pasar en verde." . PHP_EOL;
echo "Intenta romper un test a proposito para ver como se ve un fallo." . PHP_EOL;
echo PHP_EOL;
echo "Pidele a Claude que revise tus tests!" . PHP_EOL;
