<?php

/**
 * =============================================================
 *  EJERCICIO 06: CLASES Y OBJETOS
 * =============================================================
 */


// -----------------------------------------------
// EJERCICIO 1: Clase basica con constructor
// -----------------------------------------------
// Crea una clase "Libro" con:
//   Propiedades: titulo, autor, paginas, leido (bool, default false)
//   Constructor que reciba titulo, autor y paginas
//   Metodo "leer()" que ponga leido a true y muestre "[titulo] marcado como leido"
//   Metodo "info()" que muestre toda la informacion del libro
// Crea 2 libros, lee uno, y muestra info de ambos.

echo "--- Ejercicio 1 ---" . PHP_EOL;
// TU CODIGO AQUI



// -----------------------------------------------
// EJERCICIO 2: Visibilidad y encapsulacion
// -----------------------------------------------
// Crea una clase "Jugador" con:
//   Propiedad privada: $vida (int, empieza en 100)
//   Propiedad publica: $nombre
//   Metodo "recibirDanio(int $cantidad)" que reste vida (minimo 0)
//   Metodo "curar(int $cantidad)" que sume vida (maximo 100)
//   Metodo "getVida()" que devuelva la vida actual
//   Metodo "estaVivo()" que devuelva true/false
// Simula una pequenia batalla.

echo PHP_EOL . "--- Ejercicio 2 ---" . PHP_EOL;
// TU CODIGO AQUI



// -----------------------------------------------
// EJERCICIO 3: Herencia
// -----------------------------------------------
// Crea una clase base "Forma" con:
//   Propiedad: $color
//   Metodo abstracto: calcularArea(): float
//   Metodo: descripcion() que muestre "[Forma] de color [color] con area [area]"
//
// Crea dos clases hijas:
//   "Rectangulo" (con $ancho y $alto)
//   "Circulo" (con $radio)
// Cada una implementa calcularArea().
// Crea objetos y muestra la descripcion de cada uno.

echo PHP_EOL . "--- Ejercicio 3 ---" . PHP_EOL;
// TU CODIGO AQUI



// -----------------------------------------------
// EJERCICIO 4: Interfaces
// -----------------------------------------------
// Crea una interface "Imprimible" con:
//   metodo imprimir(): string
// Crea una interface "Serializable" con:
//   metodo serializar(): string (devuelve JSON)
//
// Crea una clase "Tarea" que implemente ambas interfaces con:
//   Propiedades: titulo, completada (bool)
//   imprimir() devuelve "[X] titulo" o "[ ] titulo"
//   serializar() devuelve la tarea como JSON
// Crea 3 tareas, completa una, e imprime todas.

echo PHP_EOL . "--- Ejercicio 4 ---" . PHP_EOL;
// TU CODIGO AQUI



// -----------------------------------------------
// EJERCICIO 5: Constructor promotion + readonly
// -----------------------------------------------
// Crea una clase "Evento" usando constructor promotion con:
//   readonly string $nombre
//   readonly string $fecha
//   readonly string $lugar
//   int $asistentes (default 0, NO readonly para poder cambiarlo)
//   Metodo "registrar()" que incremente asistentes en 1
//   Metodo "__toString()" que devuelva info del evento

echo PHP_EOL . "--- Ejercicio 5 ---" . PHP_EOL;
// TU CODIGO AQUI



// -----------------------------------------------
// EJERCICIO 6 (BONUS): Mini sistema de notificaciones
// -----------------------------------------------
// Crea un trait "Notificable" con:
//   propiedad $notificaciones (array)
//   metodo "notificar(string $mensaje)"
//   metodo "verNotificaciones()" que muestre todas
//
// Crea dos clases que usen el trait:
//   "Cliente" (con nombre)
//   "Empleado" (con nombre y departamento)
// Envia notificaciones a ambos y muestralas.

echo PHP_EOL . "--- Ejercicio 6 (Bonus) ---" . PHP_EOL;
// TU CODIGO AQUI



echo PHP_EOL . "Ejercicios completados! Pidele a Claude que revise tu codigo." . PHP_EOL;
