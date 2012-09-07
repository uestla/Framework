<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008, 2012 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Kdyby\Extension\Curl;



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
interface Exception
{

}



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class InvalidArgumentException extends \InvalidArgumentException implements Exception
{

}



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class InvalidStateException extends \RuntimeException implements Exception
{

}



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class InvalidUrlException extends \InvalidArgumentException implements Exception
{

}



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class MissingCertificateException extends \RuntimeException implements Exception
{

}



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class FileNotWritableException extends \RuntimeException implements Exception
{

}



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class DirectoryNotWritableException extends \RuntimeException implements Exception
{

}



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class NotSupportedException extends \LogicException implements Exception
{

}



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class CurlException extends \RuntimeException implements Exception
{

	/**
	 * @var \Kdyby\Extension\Curl\Request
	 */
	private $request;

	/**
	 * @var \Kdyby\Extension\Curl\Response
	 */
	private $response;



	/**
	 * @param string $message
	 * @param \Kdyby\Extension\Curl\Request $request
	 * @param \Kdyby\Extension\Curl\Response $response
	 */
	public function __construct($message, Request $request = NULL, Response $response = NULL)
	{
		parent::__construct($message);
		$this->request = $request;
		if ($this->response = $response) {
			$this->code = $response->headers['Status-Code'];
		}
	}



	/**
	 * @return \Kdyby\Extension\Curl\Request
	 */
	public function getRequest()
	{
		return $this->request;
	}



	/**
	 * @return \Kdyby\Extension\Curl\Response
	 */
	public function getResponse()
	{
		return $this->response;
	}

}



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class FailedRequestException extends CurlException
{

	/**
	 * @var mixed
	 */
	private $info;



	/**
	 * @param \Kdyby\Extension\Curl\CurlWrapper $curl
	 */
	public function __construct(CurlWrapper $curl)
	{
		parent::__construct($curl->error);
		$this->code = $curl->errorNumber;
		$this->info = $curl->info;
	}



	/**
	 * @see curl_getinfo()
	 * @return mixed
	 */
	public function getInfo()
	{
		return $this->info;
	}

}



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class BadStatusException extends CurlException
{

}
