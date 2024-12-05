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
 * @return int $i
 */
function mostrarPrimerIndiceGanado($partidas, $jugador)
{
    //Inicializamos la variable
    $indice = -1;
    $i = 0;
    //Abrimos una repetitiva que se dentra cuando $i sea mayor a la cantidad de indices count($partidas)
    while ($i < count($partidas) && $indice == -1) {
        $partida = $partidas[$i];
        //Abrimos una condicion donde evaluamos si el jugador es igual a jugador de ingreso de la function y si tambien el puntaje es mayor a 0
        if ($partida["jugador"] === $jugador && $partida["puntaje"] > 0) {
            $indice = $i;
        }
        //Por cada repeticion sumamos 1
        $i++;
    }
    return $indice;
}

/**
 * Devuelve un numero ingresado por el usuario que esté en el minimo y maximo establecido
 * @param int $min, $max, $numero
 * @return int
 */
function solicitarNumeroEntre($min, $max)
{
    seleccionarOpcion();
    //Mostramos por pantalla
    echo "\nIngresar numero entre " . $min . " y " . $max . ": ";
    //El usuario ingresa un valor 
    $numero = trim(fgets(STDIN));
    //is_numeric — Comprueba si una variable es un número o un string numérico
    if (is_numeric($numero)) { // Determina si un string es un número. puede ser float como entero.
        $numero  = $numero * 1; // Con esta operación convierto el string en número.
    }
    //Abrimos una repetitiva donde se evalúa si es un numero, ser un numero entero y estar dentro del rango $min y $max (incluido)
    while (!(is_numeric($numero) && (($numero == (int)$numero) && ($numero >= $min && $numero <= $max)))) {
        //Mostramos por pantalla
        echo "Debe ingresar un número entre " . $min . " y " . $max . ": ";
        //El usuario ingresa un valor 
        $numero = trim(fgets(STDIN));
        // Verificamos nuevamente si es numérico y lo convertimos a número si es necesario.
        if (is_numeric($numero)) {
            $numero  = $numero * 1;
        }
    }
    //retornamos $numero
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
    //
    switch ($estado) {
        //Caso en que la letra está disponible (no utilizada ni descartada)
        case ESTADO_LETRA_DISPONIBLE:
            escribirNormal($texto);
            break;
        //Caso en que la letra ha sido encontrada en la posición correcta.
        case ESTADO_LETRA_ENCONTRADA:
            escribirFondoVerde($texto);
            break;
        //Caso en que la letra pertenece a la palabra pero está en la posición incorrecta.
        case ESTADO_LETRA_PERTENECE:
            escribirFondoAmarillo($texto);
            break;
        //Caso en que la letra fue descartada (no pertenece a la palabra).
        case ESTADO_LETRA_DESCARTADA:
            escribirFondoRojo($texto);
            break;
        //si $estado no coincide con ninguno de los anteriores
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
 * @param string $cadena,
 * @param int $cantCaracteres, $i 
 * @return boolean $esLetra,
 */
function esPalabra($cadena)
{
    //int $cantCaracteres, $i, boolean $esLetra
    //strlen — Obtiene la longitud de un string
    $cantCaracteres = strlen($cadena);
    //Inicializamos variables
    $esLetra = true;
    $i = 0;
    //Abrimos una repetitiva con la condicion de que si $esLetra es true y $i es menor que $cantCaracteres
    while ($esLetra && $i < $cantCaracteres) {     
        //$esLetra sera true si el carácter en $cadena[$i] es una letra, y false si no lo es
        $esLetra = ctype_alpha($cadena[$i]);
        //sumamos uno al contador
        $i++;
    }
    //Retornamos la variable que contiene un bool
    return $esLetra;
}


/**
 * Comprueba si es una palabra de 5 letras
 * @return string $palabra
 */
function leerPalabra5Letras()
{
    //Mostramos por pantalla
    echo "Ingrese una palabra de 5 letras: ";
    //El usuario ingrese un dato
    $palabra = trim(fgets(STDIN));
    //Pasamos todo el string a mayusculas
    $palabra  = strtoupper($palabra);
    //Comparamos la longitud del string $palabra, si es diferente a 5 o no es palabra (function esPalabra)
    while ((strlen($palabra) != 5) || !esPalabra($palabra)) {
        escribirRojo("ERROR\n");
        echo "Debe ingresar una palabra de 5 letras: ";
        //strtoupper — Convierte un string a mayúsculas
        $palabra = strtoupper(trim(fgets(STDIN)));
    }
    //Retornamos 
    return $palabra;
}

/**
 * Muestra por consola datos del nro ingresado de partida
 * @param array $partidas
 * @param int $nro
 */
function mostrarPartida($partidas, $nro)
{
    //invocamos las functions que cambia de color el texto 
    escribirVerde("\n*********************************************\n");
    escribirVerde("Partida WORDIX N°" . $nro+1 . ": palabra " . $partidas[$nro]["palabraWordix"] ."\n");
    escribirVerde("Jugador: " . $partidas[$nro]["jugador"] ."\n");
    escribirVerde("Puntaje: " . $partidas[$nro]["puntaje"] . " puntos\n");
    //Abrimos una alternativa donde evaluamos si el numero del puntaje es igual a 0
    if ($partidas[$nro]["puntaje"] == 0) {
        escribirVerde("Intento: No adivinó la palabra\n");
    } else {
        //En caso que no se igual a 0 retornamos un mensaje para esa situacion.
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
    //strtoupper — Convierte un string a mayúsculas
    //A $palabra la convertimos en mayusculas para agregarla al arreglo 
    $palabras[] = strtoupper($palabra);
    //Retornamos el arreglo
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
    //Recorremos el arreglo y le asignamos el dato a $letra
    foreach ($ordenTeclado as $letra) {
        //Abrimos una alternativa swich
        switch ($letra) {
            //en el caso que sea igual a 'salto' hacemos un salto de linea (\n)
            case 'salto':
                echo "\n";
                break;
            //En el resto de casos que no sea 'salto' le asignamos $teclado[$letra] a $estado
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
 * @param array $estructuraIntentosWordix, 
 * @param int $cantIntentosRealizados, $i, estructuraIntento
 * @param string $intentoLetra
 */
function imprimirIntentosWordix($estructuraIntentosWordix)
{
    //Le asignamos $cantIntentosRealizados la cantidad de inidices de $estructuraIntentosWordix
    $cantIntentosRealizados = count($estructuraIntentosWordix);
    //$cantIntentosFaltantes = CANT_INTENTOS - $cantIntentosRealizados;
    //Abrimos un bucle for, se detiene cuando $i sea igual a $cantIntentosRealizados
    for ($i = 0; $i < $cantIntentosRealizados; $i++) {
        $estructuraIntento = $estructuraIntentosWordix[$i];
        //Mostramos por pantalla
        echo "\n" . ($i + 1) . ")  ";
        //Recorremos el arreglo
        foreach ($estructuraIntento as $intentoLetra) {
            escribirSegunEstado($intentoLetra["letra"], $intentoLetra["estado"]);
        }
        echo "\n";
    }
    //Abrimos un bucle for, se detiene cuando $i sea igual a $cantIntentosRealizados. $i se lo iguala a $cantIntentosRealizados
    for ($i = $cantIntentosRealizados; $i < CANT_INTENTOS; $i++) {
        //Mostramos por pantalla
        echo "\n" . ($i + 1) . ")  ";
        //Abrimos un bucle for, se detiene cuando $j = a 5
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
 * @param string $palabraWordix, $palabraIntento, $letraIntento 
 * @param array $estruturaIntentosWordix
 * @param int $i, $cantCaracteres
 * @param bool $posicion
 * @return array estructura wordix modificada
 */
function analizarPalabraIntento($palabraWordix, $estruturaIntentosWordix, $palabraIntento)
{
    //strlen — Obtiene la longitud de un string
    //Le asignamos la cantidad de caracteres al string a la variable
    $cantCaracteres = strlen($palabraIntento);
    //Inicializamos el arreglo
    $estructuraPalabraIntento = []; /*almacena cada letra de la palabra intento con su estado */
    //Abrimos un bucle for, se detendra cuando $i sea igual a $cantCaracteres
    for ($i = 0; $i < $cantCaracteres; $i++) {
        //$letraIntento se le asigna el dato de $palabraIntento del indice $i
        $letraIntento = $palabraIntento[$i];
        //strpos — Encuentra la posición de la primera ocurrencia de un substring en un string
        $posicion = strpos($palabraWordix, $letraIntento);
        //Abrimos una alternativa, si $posicion es estrictamente igual
        if ($posicion === false) {
            $estado = ESTADO_LETRA_DESCARTADA;
            //En caso contrario
        } else {
            //Abrimos una alternativa, si $letraIntento es igual al indice $i del arreglo $palabraWordix le asigna $estado = ESTADO_LETRA_ENCONTRADA; 
            if ($letraIntento == $palabraWordix[$i]) {
                $estado = ESTADO_LETRA_ENCONTRADA;
            } else {
                //Si no es asi: $estado = ESTADO_LETRA_PERTENECE; 
                $estado = ESTADO_LETRA_PERTENECE;
            }
        }
        //array_push — Inserta uno o más elementos al final de un array
        //Agregamos un elemento al array, y se le asigna la clave y el valor 
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
    //Recorremos el arreglo
    foreach ($estructuraPalabraIntento as $letraIntento) {
        //Le asignamos el valor de la clave 'letra'
        $letra = $letraIntento["letra"];
        //le asignamos el valor de la clave 'estado'
        $estado = $letraIntento["estado"];
        //Abrimos una alternativa swich
        switch ($teclado[$letra]) {
            case ESTADO_LETRA_DISPONIBLE:
                //Si es igual a $estado
                $teclado[$letra] = $estado;
                break;
            case ESTADO_LETRA_PERTENECE:
                //Abrimos una laternatica donde comparemos si $estado == ESTADO_LETRA_ENCONTRADA
                if ($estado == ESTADO_LETRA_ENCONTRADA) {
                    //Le asignamos el dato que contiene la variable a la clave $letra del arreglo $teclado
                    $teclado[$letra] = $estado;
                }
                break;
        }
    }
    //Retornamos el arreglo
    return $teclado;
}

/**
 * Determina si se ganó una palabra intento posee todas sus letras "Encontradas".
 * @param array $estructuraPalabraIntento
 * @param int $cantLetras, $i
 * @return bool $ganado
 */
function esIntentoGanado($estructuraPalabraIntento)
{
    //Le asignamos la cantidad de indicies que contiene el arrelgo $estructuraPalabraIntento a $cantLetras
    $cantLetras = count($estructuraPalabraIntento);
    //Inicializamos el contador
    $i = 0;
    //Iniciamos un bucle que se detiene cuando $i sea igual a $cantLetras y el inidice $i['estado'] de $estructuraPalabraIntento
    while ($i < $cantLetras && $estructuraPalabraIntento[$i]["estado"] == ESTADO_LETRA_ENCONTRADA) {
        //Le sumamos 1 al contador
        $i++;
    }
    //Si $i e igual $cantLetras, $ganado es true si no false
    if ($i == $cantLetras) {
        $ganado = true;
    } else {
        $ganado = false;
    }
    //retornamos $ganado
    return $ganado;
}

/**
 * Calcula y devuelve el puntaje
 * @param array $intentosPuntaje
 * @param int $cantIntentos, puntajeTotal
 * @param string $palabra
 * @return int
 */
function obtenerPuntajeWordix($cantIntentos, $palabra)
{

    $intentosPuntaje = [6, 5, 4, 3, 2, 1];
    //Inicializamos la variable
    $puntajeTotal = 0;
    //Asignamos $cantIntentos -1 al indice
    $puntajeTotal = $intentosPuntaje[$cantIntentos - 1];
    //recorremos el arreglo
    //str_split — Convierte un string en un array
    foreach (str_split($palabra) as $letra) {
        //in_array — Comprueba si un valor existe en un array
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
function calcPorcVictorias($estadisticas, $jugador){
    

}
/**
 * Dada una palabra para adivinar, juega una partida de wordix intentando que el usuario adivine la palabra.
 * @param string $palabraWordix
 * @param string $nombreUsuario
 * @return array estructura con el resumen de la partida, para poder ser utilizada en estadísticas.
 */
function jugarWordix($palabraWordix, $nombreUsuario)
{
    //int $nroIntento, $indiceIntento
    /*Inicialización*/
    $arregloDeIntentosWordix = [];
    $teclado = iniciarTeclado();
    escribirMensajeBienvenida($nombreUsuario);
    $nroIntento = 1;
    //Iniciamos el bucle do...while que tiene como caracteristica que se inica al menos 1 veces
    do {
        //LLamamos a la function
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
        //La condicion se detendra si supera la cantidad maxima de intentos y si el jugador adivina 
    } while ($nroIntento <= CANT_INTENTOS && !$ganoElIntento);

    //Si $ganoElIntento es true
    if ($ganoElIntento) {
        //Le restamos 1 al contador 
        $nroIntento--;
        $puntaje = obtenerPuntajeWordix($nroIntento, $palabraWordix);
        echo "Adivinó la palabra Wordix en el intento " . $nroIntento . ": " . $nombreUsuario . " Obtuvo $puntaje puntos!\n";
    } else {
        //Si $ganoElIntento es false
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
?>