<?php
/*
 * Curl.php
 *
 * @author Gabriel Castillo <gabriel@gabrielcastillo.net>
 *
 * Copyright (c) 2024.
 */


class Curl {
	private $request;

	/**
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->request = curl_init();
		$this->throwExceptionIfError($this->request);
	}

	/**
	 * @param string $url
	 * @param array $urlParams
	 * @param string $method
	 * @param array $moreOptions
	 *
	 * @return void
	 * @throws Exception
	 */
	final public function configure( string $url, array $urlParams = [], string $method = 'GET', array $moreOptions = [ CURLOPT_FOLLOWLOCATION => true, CURLOPT_RETURNTRANSFER => true] )
	{
		curl_reset( $this->request );

		switch ( $method ) {
			case 'GET':
				$options = [CURLOPT_URL => $url . $this->stringifyParams( $urlParams )];
				break;
			case 'POST':
				$options = [
					CURLOPT_URL => $url,
					CURLOPT_POST => true,
					CURLOPT_POSTFIELDS => $this->stringifyParams( $urlParams ),
				];
				break;
			default:
				throw new Exception('Method must be "GET" or "POST".');
				break;
		}

		$options = $options + $moreOptions;

		foreach ( $options as $option => $value ) {
			$configured = curl_setopt( $this->request, $option, $value );

			$this->throwExceptionIfError( $configured );
		}
	}

	/**
	 * @return bool|string
	 * @throws Exception
	 */
	final public function execute()
	{
		$result = curl_exec( $this->request );
		$this->throwExceptionIfError( $result );
		return $result;
	}

	/**
	 * @return void
	 */
	final public function close()
	{
		curl_close( $this->request );
	}

	/**
	 * @param array $params
	 *
	 * @return string
	 */
	private function stringifyParams( array $params ): string
	{
		$paramString = '?';

		foreach ( $params as $key => $value ) {
			$key = urlencode( $key );
			$value = urlencode( $value );

			$paramString .= "$key=$value&";
		}

		rtrim($paramString, "&");

		return $paramString;
	}

	/**
	 * @param CurlHandle|bool $success
	 *
	 * @return void
	 * @throws Exception
	 */
	private function throwExceptionIfError( CurlHandle|bool $success ): void
	{
		if ( !$success ) {
			throw new Exception(curl_error($this->request));
		}
	}
}