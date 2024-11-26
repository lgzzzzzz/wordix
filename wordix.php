<?php

/*
La librería JugarWordix posee la definición de constantes y funciones necesarias
para jugar al Wordix.
Puede ser utilizada por cualquier programador para incluir en sus programas.
*/

/**************************************/
/***** DEFINICION DE CONSTANTES *******/
/**************************************/
const CANT_INTENTOS = 6;

/*
    disponible: letra que aún no fue utilizada para adivinar la palabra
    encontrada: letra descubierta en el lugar que corresponde
    pertenece: letra descubierta, pero corresponde a otro lugar
    descartada: letra descartada, no pertence a la palabra
*/
const ESTADO_LETRA_DISPONIBLE = "disponible";
const ESTADO_LETRA_ENCONTRADA = "encontrada";
const ESTADO_LETRA_DESCARTADA = "descartada";
const ESTADO_LETRA_PERTENECE = "pertenece";

/**************************************/
/***** DEFINICION DE FUNCIONES ********/
/**************************************/

/**
 * Devuelve el primer indice de un array donde haya ganado x jugador
 * @param array $partidas
 * @param string $jugador
 * @return int
 */
function mostrarPrimerIndiceGanado($partidas, $jugador)
{
    $i = 0;
    while ($i < count($partidas)) {
        $partida = $partidas[$i];
        if ($partida["jugador"] === $jugador && $partida["puntaje"] > 0) {
            return $i;
        }
        $i++;
    }
    return -1;
}

/**
 * Devuelve un numero ingresado por el usuario que esté en el minimo y maximo establecido
 * @param int $min, $max
 * @return int
 */
function solicitarNumeroEntre($min, $max)
{
    seleccionarOpcion();
    echo "\nIngresar numero entre " . $min . " y " . $max . ": ";
    $numero = trim(fgets(STDIN));

    if (is_numeric($numero)) { // Determina si un string es un número. puede ser float como entero.
        $numero  = $numero * 1; // Con esta operación convierto el string en número.
    }
    while (!(is_numeric($numero) && (($numero == (int)$numero) && ($numero >= $min && $numero <= $max)))) {
        echo "Debe ingresar un número entre " . $min . " y " . $max . ": ";
        $numero = trim(fgets(STDIN));
        if (is_numeric($numero)) {
            $numero  = $numero * 1;
        }
    }
    return $numero;
}

// TEXTOS CON FONDOS DE COLOR
/**
 * Escrbir un texto con fondo ROJO
 * @param string $texto
 */
function escribirFondoRojo($texto)
{
    echo "\e[1;37;41m $texto \e[0m";
}

/**
 * Escrbir un texto con fondo VERDE
 * @param string $texto
 */
function escribirFondoVerde($texto)
{
    echo "\e[1;37;42m $texto \e[0m";
}

/**
 * Escrbir un texto con fondo AMARILLO
 * @param string $texto
 */
function escribirFondoAmarillo($texto)
{
    echo "\e[1;37;43m $texto \e[0m";
}

/**
 * Escribir un texto con fondo GRIS
 * @param string $texto
 */
function escribirFondoGris($texto)
{
    echo "\e[1;34;47m $texto \e[0m";
}

// TEXTOS DE COLOR
/**
 * Escribir texto de color ROJO
 * @param string $texto
 */
function escribirRojo($texto)
{
    echo "\033[31m$texto\033[0m";
}

/**
 * Escribir texto de color VERDE
 * @param string $texto
 */
function escribirVerde($texto)
{
    echo "\033[32m$texto\033[0m";
}

/**
 * Escribir texto de color AMARILLO
 * @param string $texto
 */
function escribirAmarillo($texto)
{
    echo "\033[33m$texto\033[0m";
}

/**
 * Escribir texto de color GRIS
 * @param string $texto
 */
function escribirGris($texto)
{
    echo "\033[90m$texto\033[0m";
}

/**
 * Escrbir un texto pantalla.
 * @param string $texto
 */
function escribirNormal($texto)
{
    echo "\e[0m $texto \e[0m";
}

/**
 * Escribe un texto en pantalla teniendo en cuenta el estado.
 * @param string $texto
 * @param string $estado
 */
function escribirSegunEstado($texto, $estado)
{
    switch ($estado) {
        case ESTADO_LETRA_DISPONIBLE:
            escribirNormal($texto);
            break;
        case ESTADO_LETRA_ENCONTRADA:
            escribirFondoVerde($texto);
            break;
        case ESTADO_LETRA_PERTENECE:
            escribirFondoAmarillo($texto);
            break;
        case ESTADO_LETRA_DESCARTADA:
            escribirFondoRojo($texto);
            break;
        default:
            echo " $texto ";
            break;
    }
}

/**
 * Muestra en consola el mensaje de bienvenida
 * @param string $usuario
 */
function escribirMensajeBienvenida($usuario)
{
    escribirAmarillo("\n**************************************************\n**");
    escribirAmarillo(" Hola ");
    escribirFondoAmarillo($usuario);
    escribirAmarillo(" Juguemos una PARTIDA de WORDIX! ");
    escribirAmarillo("**\n**************************************************\n\n");
}


/**
 * Verifica si el string ingresado es una palabra
 * @param string $cadena
 * @return boolean
 */
function esPalabra($cadena)
{
    //int $cantCaracteres, $i, boolean $esLetra
    $cantCaracteres = strlen($cadena);
    $esLetra = true;
    $i = 0;
    while ($esLetra && $i < $cantCaracteres) {
        $esLetra = ctype_alpha($cadena[$i]);
        $i++;
    }
    return $esLetra;
}


/**
 * Comprueba si es una palabra de 5 letras
 * @return string
 */
function leerPalabra5Letras()
{
    //string $palabra
    echo "Ingrese una palabra de 5 letras: ";
    $palabra = trim(fgets(STDIN));
    $palabra  = strtoupper($palabra);

    while ((strlen($palabra) != 5) || !esPalabra($palabra)) {
        escribirRojo("ERROR\n");
        echo "Debe ingresar una palabra de 5 letras: ";
        $palabra = strtoupper(trim(fgets(STDIN)));
    }
    return $palabra;
}

/**
 * Muestra por consola datos del nro ingresado de partida
 * @param array $partidas
 * @param int
 */
function mostrarPartida($partidas, $nro)
{
    escribirVerde("\n*********************************************\n");
    escribirVerde("Partida WORDIX N°" . $nro . ": palabra " . $partidas[$nro]["palabraWordix"] ."\n");
    escribirVerde("Jugador: " . $partidas[$nro]["jugador"] ."\n");
    escribirVerde("Puntaje: " . $partidas[$nro]["puntaje"] . " puntos\n");
    if ($partidas[$nro]["puntaje"] == 0) {
        escribirVerde("Intento: No adivinó la palabra\n");
    } else {
        escribirVerde("Intento: Adivinó la palabra en " . $partidas[$nro]["intentos"] . " intentos\n");
    }
    escribirVerde("**********************************************\n");
}

/**
 * Agrega una palabra ingresada por el usuario a la coleccion de palabras
 * @param array $palabras
 * @param string $palabra
 * @return array
 */
function agregarPalabra($palabras, $palabra)
{
    $palabras[] = strtoupper($palabra);
    return $palabras;
}

/**
 * Inicia una estructura asociativa de datos Teclado. La estructura es de tipo: ¿Indexado, asociativo o Multidimensional?
 * @return array
 */
function iniciarTeclado()
{
    //array $teclado (arreglo asociativo, cuyas claves son las letras del alfabeto)
    $teclado = [
        "A" => ESTADO_LETRA_DISPONIBLE,
        "B" => ESTADO_LETRA_DISPONIBLE,
        "C" => ESTADO_LETRA_DISPONIBLE,
        "D" => ESTADO_LETRA_DISPONIBLE,
        "E" => ESTADO_LETRA_DISPONIBLE,
        "F" => ESTADO_LETRA_DISPONIBLE,
        "G" => ESTADO_LETRA_DISPONIBLE,
        "H" => ESTADO_LETRA_DISPONIBLE,
        "I" => ESTADO_LETRA_DISPONIBLE,
        "J" => ESTADO_LETRA_DISPONIBLE,
        "K" => ESTADO_LETRA_DISPONIBLE,
        "L" => ESTADO_LETRA_DISPONIBLE,
        "M" => ESTADO_LETRA_DISPONIBLE,
        "N" => ESTADO_LETRA_DISPONIBLE,
        "Ñ" => ESTADO_LETRA_DISPONIBLE,
        "O" => ESTADO_LETRA_DISPONIBLE,
        "P" => ESTADO_LETRA_DISPONIBLE,
        "Q" => ESTADO_LETRA_DISPONIBLE,
        "R" => ESTADO_LETRA_DISPONIBLE,
        "S" => ESTADO_LETRA_DISPONIBLE,
        "T" => ESTADO_LETRA_DISPONIBLE,
        "U" => ESTADO_LETRA_DISPONIBLE,
        "V" => ESTADO_LETRA_DISPONIBLE,
        "W" => ESTADO_LETRA_DISPONIBLE,
        "X" => ESTADO_LETRA_DISPONIBLE,
        "Y" => ESTADO_LETRA_DISPONIBLE,
        "Z" => ESTADO_LETRA_DISPONIBLE
    ];
    return $teclado;
}

/**
 * Escribe en pantalla el estado del teclado. Acomoda las letras en el orden del teclado QWERTY
 * @param array $teclado
 */
function escribirTeclado($teclado)
{
    //array $ordenTeclado (arreglo indexado con el orden en que se debe escribir el teclado en pantalla)
    //string $letra, $estado
    $ordenTeclado = [
        "salto",
        "Q",
        "W",
        "E",
        "R",
        "T",
        "Y",
        "U",
        "I",
        "O",
        "P",
        "salto",
        "A",
        "S",
        "D",
        "F",
        "G",
        "H",
        "J",
        "K",
        "L",
        "salto",
        "Z",
        "X",
        "C",
        "V",
        "B",
        "N",
        "M",
        "salto"
    ];

    foreach ($ordenTeclado as $letra) {
        switch ($letra) {
            case 'salto':
                echo "\n";
                break;
            default:
                $estado = $teclado[$letra];
                escribirSegunEstado($letra, $estado);
                break;
        }
    }
    echo "\n";
};


/**
 * Escribe en pantalla los intentos de Wordix para adivinar la palabra
 * @param array $estruturaIntentosWordix
 */
function imprimirIntentosWordix($estructuraIntentosWordix)
{
    $cantIntentosRealizados = count($estructuraIntentosWordix);
    //$cantIntentosFaltantes = CANT_INTENTOS - $cantIntentosRealizados;

    for ($i = 0; $i < $cantIntentosRealizados; $i++) {
        $estructuraIntento = $estructuraIntentosWordix[$i];
        echo "\n" . ($i + 1) . ")  ";
        foreach ($estructuraIntento as $intentoLetra) {
            escribirSegunEstado($intentoLetra["letra"], $intentoLetra["estado"]);
        }
        echo "\n";
    }

    for ($i = $cantIntentosRealizados; $i < CANT_INTENTOS; $i++) {
        echo "\n" . ($i + 1) . ")  ";
        for ($j = 0; $j < 5; $j++) {
            escribirFondoGris(" ");
        }
        echo "\n";
    }
    //echo "\n" . "Le quedan " . $cantIntentosFaltantes . " Intentos para adivinar la palabra!";
}

/**
 * Dada la palabra wordix a adivinar, la estructura para almacenar la información del intento 
 * y la palabra que intenta adivinar la palabra wordix.
 * devuelve la estructura de intentos Wordix modificada con el intento.
 * @param string $palabraWordix
 * @param array $estruturaIntentosWordix
 * @param string $palabraIntento
 * @return array estructura wordix modificada
 */
function analizarPalabraIntento($palabraWordix, $estruturaIntentosWordix, $palabraIntento)
{
    $cantCaracteres = strlen($palabraIntento);
    $estructuraPalabraIntento = []; /*almacena cada letra de la palabra intento con su estado */
    for ($i = 0; $i < $cantCaracteres; $i++) {
        $letraIntento = $palabraIntento[$i];
        $posicion = strpos($palabraWordix, $letraIntento);
        if ($posicion === false) {
            $estado = ESTADO_LETRA_DESCARTADA;
        } else {
            if ($letraIntento == $palabraWordix[$i]) {
                $estado = ESTADO_LETRA_ENCONTRADA;
            } else {
                $estado = ESTADO_LETRA_PERTENECE;
            }
        }
        array_push($estructuraPalabraIntento, ["letra" => $letraIntento, "estado" => $estado]);
    }

    array_push($estruturaIntentosWordix, $estructuraPalabraIntento);
    return $estruturaIntentosWordix;
}

/**
 * Actualiza el estado de las letras del teclado. 
 * Teniendo en cuenta que una letra sólo puede pasar:
 * de ESTADO_LETRA_DISPONIBLE a ESTADO_LETRA_ENCONTRADA, 
 * de ESTADO_LETRA_DISPONIBLE a ESTADO_LETRA_DESCARTADA, 
 * de ESTADO_LETRA_DISPONIBLE a ESTADO_LETRA_PERTENECE
 * de ESTADO_LETRA_PERTENECE a ESTADO_LETRA_ENCONTRADA
 * @param array $teclado
 * @param array $estructuraPalabraIntento
 * @return array el teclado modificado con los cambios de estados.
 */
function actualizarTeclado($teclado, $estructuraPalabraIntento)
{
    foreach ($estructuraPalabraIntento as $letraIntento) {
        $letra = $letraIntento["letra"];
        $estado = $letraIntento["estado"];
        switch ($teclado[$letra]) {
            case ESTADO_LETRA_DISPONIBLE:
                $teclado[$letra] = $estado;
                break;
            case ESTADO_LETRA_PERTENECE:
                if ($estado == ESTADO_LETRA_ENCONTRADA) {
                    $teclado[$letra] = $estado;
                }
                break;
        }
    }
    return $teclado;
}

/**
 * Determina si se ganó una palabra intento posee todas sus letras "Encontradas".
 * @param array $estructuraPalabraIntento
 * @return bool
 */
function esIntentoGanado($estructuraPalabraIntento)
{
    $cantLetras = count($estructuraPalabraIntento);
    $i = 0;

    while ($i < $cantLetras && $estructuraPalabraIntento[$i]["estado"] == ESTADO_LETRA_ENCONTRADA) {
        $i++;
    }

    if ($i == $cantLetras) {
        $ganado = true;
    } else {
        $ganado = false;
    }

    return $ganado;
}

/**
 * Calcula y devuelve el puntaje
 * @param int $cantIntentos
 * @param string $palabra
 * @return int
 */
function obtenerPuntajeWordix($cantIntentos, $palabra)
{

    $intentosPuntaje = [6, 5, 4, 3, 2, 1];

    $puntajeTotal = 0;
    $puntajeTotal = $intentosPuntaje[$cantIntentos - 1];

    foreach (str_split($palabra) as $letra) {
        if (in_array($letra, ['A', 'E', 'I', 'O', 'U'])) {
            // Vocales
            $puntajeTotal += 1;
        } elseif ($letra <= 'M') {
            // Consonantes de la A a la M
            $puntajeTotal += 2;
        } else {
            // Consonantes de la N en adelante
            $puntajeTotal += 3;
        }
    }

    return $puntajeTotal;
}

/**
 * Dada una palabra para adivinar, juega una partida de wordix intentando que el usuario adivine la palabra.
 * @param string $palabraWordix
 * @param string $nombreUsuario
 * @return array estructura con el resumen de la partida, para poder ser utilizada en estadísticas.
 */
function jugarWordix($palabraWordix, $nombreUsuario)
{
    /*Inicialización*/
    $arregloDeIntentosWordix = [];
    $teclado = iniciarTeclado();
    escribirMensajeBienvenida($nombreUsuario);
    $nroIntento = 1;
    do {
        escribirAmarillo("INTENTO " . $nroIntento . ":\n");
        $palabraIntento = leerPalabra5Letras();
        $indiceIntento = $nroIntento - 1;
        $arregloDeIntentosWordix = analizarPalabraIntento($palabraWordix, $arregloDeIntentosWordix, $palabraIntento);
        $teclado = actualizarTeclado($teclado, $arregloDeIntentosWordix[$indiceIntento]);
        /*Mostrar los resultados del análisis: */
        imprimirIntentosWordix($arregloDeIntentosWordix);
        escribirTeclado($teclado);
        /*Determinar si la plabra intento ganó e incrementar la cantidad de intentos */

        $ganoElIntento = esIntentoGanado($arregloDeIntentosWordix[$indiceIntento]);
        $nroIntento++;
    } while ($nroIntento <= CANT_INTENTOS && !$ganoElIntento);


    if ($ganoElIntento) {
        $nroIntento--;
        $puntaje = obtenerPuntajeWordix($nroIntento, $palabraWordix);
        echo "Adivinó la palabra Wordix en el intento " . $nroIntento . ": " . $nombreUsuario . " Obtuvo $puntaje puntos!\n";
    } else {
        $nroIntento = 0; //reset intento
        $puntaje = 0;
        echo "Seguí Jugando Wordix, la próxima será!\n";
    }

    $partida = [
        "palabraWordix" => $palabraWordix,
        "jugador" => $nombreUsuario,
        "intentos" => $nroIntento,
        "puntaje" => $puntaje
    ];

    return $partida;
}
