<?php
include_once("wordix.php");

/**************************************/
/***** DATOS DE LOS INTEGRANTES *******/
/**************************************/

/* Apellido, Nombre. Legajo. Carrera. mail. Usuario Github */
/** 
 * Lagos Ezequiel FAI-4270; Carrera: TUDW; Mail: lagosezequiel0@gmail.com; Usuario GITHUB: lgzzzzzz;
 * Contreras Lucas FAI-5094; Carrera: TUDW; Mail: lucas.contreras@est.fi.uncoma.edu.ar ; Usuario GITHUB: LucasContreras888;
 */

/**************************************/
/***** DEFINICION DE FUNCIONES ********/
/**************************************/

/**
 * Retorna array de partidas.
 * @return array $coleccionPartidas
 */
function cargarPartidas()
{
    //ARRAY $coleccionPartidas
    $coleccionPartidas = [
        ["palabraWordix" => "QUESO", "jugador" => "majo",       "intentos" => 0, "puntaje" => 0],
        ["palabraWordix" => "CASAS", "jugador" => "rudolf",     "intentos" => 3, "puntaje" => 14],
        ["palabraWordix" => "QUESO", "jugador" => "pink2000",   "intentos" => 6, "puntaje" => 10],
        ["palabraWordix" => "MUJER", "jugador" => "majo",       "intentos" => 3, "puntaje" => 13],
        ["palabraWordix" => "RASGO", "jugador" => "pink2000",   "intentos" => 5, "puntaje" => 12],
        ["palabraWordix" => "PISOS", "jugador" => "pink2000",   "intentos" => 1, "puntaje" => 17],
        ["palabraWordix" => "RASGO", "jugador" => "pink2000",   "intentos" => 5, "puntaje" => 12],
        ["palabraWordix" => "RASGO", "jugador" => "pink2000",   "intentos" => 2, "puntaje" => 15],
        ["palabraWordix" => "FUEGO", "jugador" => "rudolf",     "intentos" => 6, "puntaje" => 8],
        ["palabraWordix" => "TINTO", "jugador" => "rudolf",     "intentos" => 3, "puntaje" => 15]
    ];
    //Retorna el arreglo
    return $coleccionPartidas;
}

/**
 * Carga datos de la coleccion de partidas y los guarda en un array aparte
 * @param array $partidas
 * @param string $jugador
 * @return array 
 */
function cargarResumen($partidas, $jugador)
{
    //ARRAY $estadisticas, $jugador
    //INT $partida, $estadisticas
    // Inicializar el arreglo de estadísticas para el jugador
    $estadisticas = [
        $jugador => [
            "partidas" => 0,
            "puntajeTotal" => 0,
            "victorias" => 0,
            "porcVictorias" => 0.0,
            "adivinadas" => [
                "intento1" => 0,
                "intento2" => 0,
                "intento3" => 0,
                "intento4" => 0,
                "intento5" => 0,
                "intento6" => 0
            ]
        ]
    ];

    // Recorrer la colección de partidas
    foreach ($partidas as $partida) {
        if ($partida["jugador"] === $jugador) {
            // Sumar una partida jugada
            $estadisticas[$jugador]["partidas"]++;

            // Sumar el puntaje total
            $estadisticas[$jugador]["puntajeTotal"] += $partida["puntaje"];

            // Contar una victoria si fue adivinada
            if ($partida["intentos"] > 0 && $partida["intentos"] <= 6) {
                $estadisticas[$jugador]["victorias"]++;

                // Contar intentos específicos
                $estadisticas[$jugador]["adivinadas"]["intento" . $partida["intentos"]]++;
            }
        }
    }

    // Calcular el porcentaje de victorias
    if ($estadisticas[$jugador]["partidas"] > 0) {
        $estadisticas[$jugador]["porcVictorias"] =
            ($estadisticas[$jugador]["victorias"] / $estadisticas[$jugador]["partidas"]) * 100;
    }

    return $estadisticas;
}

/**
 * Retorna array de palabras.
 * @return array
 */
function cargarPalabras()
{
    //ARRAY $coleccionPalabras
    $coleccionPalabras = [
        "MUJER",
        "QUESO",
        "FUEGO",
        "CASAS",
        "RASGO",
        "GATOS",
        "GOTAS",
        "HUEVO",
        "TINTO",
        "NAVES",
        "VERDE",
        "MELON",
        "YUYOS",
        "PIANO",
        "PISOS"
    ];
    //Retorna el arreglo
    return $coleccionPalabras;
}

/**
 * Retorna el valor numerico de una opcion
 * @return int
 */
function seleccionarOpcion()
{
    echo "\n1) Jugar al wordix con una palabra elegida\n";
    echo "2) Jugar al wordix con una palabra aleatoria\n";
    echo "3) Mostrar una partida\n";
    echo "4) Mostrar la primer partida ganadora\n";
    echo "5) Mostrar resumen de Jugador\n";
    echo "6) Mostrar listado de partidas ordenadas por jugador y por palabra\n";
    echo "7) Agregar una palabra de 5 letras a Wordix\n";
    echo "8) Salir\n";
}

/**
 * Compara nombres y palabras del listado de partidas, retornando -1, 0 o 1.
 * @param array $a $b
 * @return int
 */
function comparar($a, $b)
{
    //Abrimos una alternativa, donde la clave de $a 'jugador' tiene que ser estrictamente igual a $b
    if ($a['jugador'] === $b['jugador']) {
        //strcmp — Comparación de string segura a nivel binario
        return strcmp($a['palabraWordix'], $b['palabraWordix']);
    }
    return strcmp($a['jugador'], $b['jugador']);
}

/**
 * Ordena el listado de partidas por nombre y palabra, y los imprime por consola
 * @param array $partidas
 */
function ordenarPartidas($partidas)
{
    uasort($partidas, "comparar");
    print_r($partidas);
}

/**
 * Muestra por consola un resumen del jugador seleccionado
 * @param array $estadisticas
 * @param string $jugador
 */
function mostrarResumen($estadisticas, $jugador)
{
    //Asignamos el valor a $estJugador 
    $estJugador = $estadisticas[$jugador];
    //Utilizamos las functions para darle color al texto
    escribirVerde("\n***************************************\n");
    escribirVerde("Jugador: " . $jugador . "\n");
    escribirVerde("Partidas: " . $estJugador["partidas"] . "\n");
    escribirVerde("Puntaje total: " . $estJugador["puntajeTotal"] . "\n");
    escribirVerde("Victorias: " . $estJugador["victorias"] . "\n");
    escribirVerde("Porcentaje victorias: " . calcPorcVictorias($estadisticas, $jugador) . "\n");
    //Abrimos un bucle for que se dentra cuando $i sea igual a la cantidad de indices de $estJugador
    for ($i = 0; $i < count($estJugador["adivinadas"]); $i++) {
        escribirVerde("Intento " . $i + 1 . ": " . $estJugador["adivinadas"]["intento" . $i + 1] . "\n");
    }
    escribirVerde("***************************************\n\n");
}
/**
 * Muestra por consola un resumen del jugador seleccionado
 * @param array $estadisticas
 * @param string $jugador
 */
function solicitarJugador()
{
    //STRING $nombre
    //BOOL $condicion
    //Abrimos un alternativa que se repita la menos una vez
    do {
        //Mostramos por pantalla
        echo "Ingresar nombre: ";
        //Asignamos un valor dado por el usuario a una variable       
        $nombre = trim(fgets(STDIN));
        //Varificamos si el no
        $condicion = !ctype_alpha($nombre[0]);
        if ($condicion) {
            escribirRojo("La primera letra del nombre ingresado no es una letra.\n");
        }
    } while ($condicion);
    return strtolower($nombre);
}

/**************************************/
/********* PROGRAMA PRINCIPAL *********/
/**************************************/

// Declaración de variables:
// INT $opcionUsuario
// INT[] $nroPalabrasUsadas
$opcionUsuario = 0;
$nroPalabrasUsadas = [];

// ARRAY $coleccionPalabras, $coleccionPartidas, $estadisticas
$coleccionPalabras = [];
$coleccionPartidas = [];
$estadisticas = [];

//Inicialización de variables:
$coleccionPartidas = cargarPartidas();
$coleccionPalabras = cargarPalabras();

//Proceso:
do {

    $opcion = solicitarNumeroEntre(1, 8);

    switch ($opcion) {
        case 1: // 1) Jugar al wordix con una palabra elegida

            $nombreJugador = solicitarJugador();
            do {
                echo "Elegir nro de palabra: ";
                $nroPalabraActual = trim(fgets(STDIN));
                $condicion = in_array($nroPalabraActual, $nroPalabrasUsadas);
                if ($condicion) {
                    escribirRojo("Nro repetido.\n");
                }
            } while ($condicion);

            $nroPalabrasUsadas[] = $nroPalabraActual;
            $palabraWordix = $coleccionPalabras[$nroPalabraActual - 1];
            $coleccionPartidas[] = jugarWordix($palabraWordix, $nombreJugador);

            break;
        case 2: // 2) Jugar al wordix con una palabra aleatoria

            $nombreJugador = solicitarJugador();
            do {
                $nroPalabraActual = random_int(1, 15);
            } while (in_array($nroPalabraActual, $nroPalabrasUsadas));

            $coleccionPartidas[] = jugarWordix($coleccionPalabras[$nroPalabraActual], $nombreJugador);
            break;
        case 3: // 3) Mostrar una partida
            do {
                echo "Ingresar nro de partida: ";
                $nroPartida = trim(fgets(STDIN));
                $condicion = $nroPartida < 1 || $nroPartida > count($coleccionPartidas);
                if ($condicion) {
                    echo escribirRojo("ERROR: Partida no encontrada.\n");
                }
            } while ($condicion);
            mostrarPartida($coleccionPartidas, $nroPartida);

            break;
        case 4: // 4) Mostrar la primer partida ganadora

            $nombreJugador = solicitarJugador();
            $indicePrimerPartidaGanada = mostrarPrimerIndiceGanado($coleccionPartidas, $nombreJugador);

            if ($indicePrimerPartidaGanada != -1) {
                mostrarPartida($coleccionPartidas, $indicePrimerPartidaGanada);
                break;
            }
            escribirRojo("Todavia no ha ganado ninguna partida.\n");

            break;
        case 5: // 5) Mostrar resumen de jugador

            $nombreJugador = solicitarJugador();
            $estadisticas = cargarResumen($coleccionPartidas, $nombreJugador);
            mostrarResumen($estadisticas, $nombreJugador);
            break;
        case 6: // 6) Mostrar listado de partidas ordenadas por jugador y por palabra

            ordenarPartidas($coleccionPartidas);
            break;
        case 7: // 7) Agregar una palabra de 5 letras a Wordix

            echo "Ingresar palabra de 5 letras: \n";
            $palabraIngresada = trim(fgets(STDIN));

            $coleccionPalabras = agregarPalabra($coleccionPalabras, $palabraIngresada);
            print_r($coleccionPalabras);
            echo "\n";
            break;
    }
} while ($opcion != 8);
