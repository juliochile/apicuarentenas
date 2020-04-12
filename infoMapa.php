<?php

/**
 * Aporte a mapa de Jorge Retamal sobr covid 19 en Chile.
 *
 * Puede recibir el parámetro "pagina", que corresponde al mapa del Ministerio de Salud.
 *
 */

$pagina = isset($_GET["pagina"]) ? $_GET["pagina"] :  "https://www.google.com/maps/d/u/0/embed?mid=1cdg89HnDuW_y4-aenjiJ-hBfO5rRGNF3&hl=es-419&ll=-33.443721884784594%2C-70.66818168388977&z=11";
$cuerpo = file_get_contents($pagina);

preg_match(
	'/var _pageData = "(.*?)";<\/script>/',
	$cuerpo,
	$matches
);
$base = $matches[1];
$base =  preg_replace('/\\\"/', '"', $base);
$base =  preg_replace('/\\\n/', '', $base);
$base =  preg_replace('/\\\\"/', "'", $base);
$base =  preg_replace("/\\\'/", "'", $base);

$array = json_decode($base);

$comunas = $array[1][6][0][12][0][13][0];

$salida = array();
foreach ($comunas as $comuna) {
	$coordenadas = array();
	foreach ($comuna[3][0][0][0][0] as $coordenada) {
		array_push(
			$coordenadas, 
			$coordenada[0]
		);
	}
	array_push(
		$salida, 
		array(
			"nombre" => $comuna[6][1][0],
			"coordenadas" => $coordenadas,
		)
	);
}

echo json_encode($salida);
