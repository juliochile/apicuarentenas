<?php

/**
 * Aporte a mapa de Jorge Retamal sobre covid 19 en Chile.
 *
 */

$cuerpo = file_get_contents("https://www.gob.cl/coronavirus/cuarentena/");
preg_match(
	'/<iframe src="(https:\/\/www.google.com\/maps\/d\/embed.*?)"/',
	$cuerpo,
	$matches
);


$cuerpo_iframe = file_get_contents($matches[1]);

preg_match(
	'/var _pageData = "(.*?)";<\/script>/',
	$cuerpo_iframe,
	$matches_iframe
);
$base = $matches_iframe[1];
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
