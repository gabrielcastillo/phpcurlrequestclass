<?php
/*
 * index.php
 *
 * @author Gabriel Castillo <gabriel@gabrielcastillo.net>
 *
 * Copyright (c) 2024.
 */



require_once( 'classes/Curl.php' );

try {
	$curl = new Curl();

	$curl->configure('gabrielcastillo.net');
	$response = $curl->execute();
	$curl->close();

} catch (Exception $exception) {
	die("An exception has been thrown: " . $exception->getMessage());
}

echo $response;