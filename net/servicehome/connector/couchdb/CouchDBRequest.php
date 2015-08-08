<?php

/*
 * The MIT License
 *
 * Copyright 2015 Marco Saßmannshausen <ms@servicehome.net>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace net\servicehome\connector\couchdb;

/**
 * Description of newPHPClass
 *
 * @author Marco Saßmannshausen <ms@servicehome.net>
 */
class CouchDBRequest {

	static $VALID_HTTP_METHODS = array('DELETE', 'GET', 'POST', 'PUT');
	private $method = 'GET';
	private $url = '';
	private $data = NULL;

	//private $sock = NULL;
	//private $username;
	//private $password;

	function __construct($url, $method = 'GET', $data = NULL) {
		$method = strtoupper($method);
		$this->url = $url;
		$this->method = $method;
		$this->data = $data;

		if (!in_array($this->method, self::$VALID_HTTP_METHODS)) {
			throw new CouchDBException('Invalid HTTP method: ' . $this->method);
		}
	}

	public function getMethod() {
		return $this->method;
	}

	public function getUrl() {
		return $this->url;
	}

	public function getPostData() {
		return $this->data;
	}

//	function getRequest() {
//		$req = "{$this->method} {$this->url} HTTP/1.0\r\nHost: {$this->host}\r\n";
//
//		if ($this->username || $this->password)
//			$req .= 'Authorization: Basic ' . base64_encode($this->username . ':' . $this->password) . "\r\n";
//
//		if ($this->data) {
//			$req .= 'Content-Length: ' . strlen($this->data) . "\r\n";
//			$req .= 'Content-Type: application/json' . "\r\n\r\n";
//			$req .= $this->data . "\r\n";
//		} else {
//			$req .= "\r\n";
//		}
//
//		return $req;
//	}
//	private function connect() {
//		$this->sock = @fsockopen($this->host, $this->port, $err_num, $err_string);
//		if (!$this->sock) {
//			throw new CouchDBException('Could not open connection to ' . $this->host . ':' . $this->port . ' (' . $err_string . ')');
//		}
//	}
//
//	private function disconnect() {
//		fclose($this->sock);
//		$this->sock = NULL;
//	}
//
//	private function execute() {
//		fwrite($this->sock, $this->getRequest());
//		$response = '';
//		while (!feof($this->sock)) {
//			$response .= fgets($this->sock);
//		}
//		$this->response = new CouchDBResponse($response);
//		return $this->response;
//	}
//
//	function send() {
//		$this->connect();
//		$this->execute();
//		$this->disconnect();
//		return $this->response;
//	}

	function getResponse() {
		return $this->response;
	}

}
