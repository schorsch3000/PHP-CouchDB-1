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

use net\servicehome\connector\couchdb\command;

/**
 * Description of newPHPClass
 *
 * @author Marco Saßmannshausen <ms@servicehome.net>
 */
class CouchDB {

	const CMD__LIST_ALL_DBS = 'cmd_type_list_all_dbs';
	const CMD__TEST = 'test_conenction';
	const CMD__CREATE_DATABASE = 'cmd_type__create_database';
	const CMD__CREATE_DOC = 'cmd_create_doc';

	private $host;
	private $port;
	private $http_user;
	private $http_pwd;

	public function __construct() {
		$this->host = 'localhost';
		$this->port = '5984';
	}

	private function setup($user = null, $pwd = null, $host = 'localhost', $port = '5984') {
		$this->http_user = $user;
		$this->http_pwd = $pwd;

		$this->host = $host;
		$this->port = $port;
	}

	public function sendCommandRaw($method, $url, $post_data = NULL) {
		$error_number = 0;
		$error_string = '';
		$socket = fsockopen($this->host, $this->port, $error_number, $error_string);
		if (!is_resource($socket)) {
			return FALSE;
		}

		$request = "$method $url HTTP/1.0\r\nHost: $this->host\r\n";

		if ($this->http_user) {
			$request .= "Authorization: Basic " . base64_encode("$this->http_user:$this->http_pwd") . "\r\n";
		}

		if ($post_data) {
			$request .= "Content-Length: " . strlen($post_data) . "\r\n\r\n";
			$request .= "$post_data\r\n";
		} else {
			$request .= "\r\n";
		}

		fwrite($socket, $request);

		$response = "";
		while (!feof($socket)) {
			$response .= fgets($socket);
		}

		//list($headers, $body) = explode("\r\n\r\n", $response);
		//return array($headers, $body);

		return $response;
	}

	public function sendCommand(CouchDBRequest $request) {

		$method = $request->getMethod();
		$url = $request->getUrl();
		$post_data = $request->getPostData();

		$command_result = $this->sendCommandRaw($method, $url, $post_data);
		if (FALSE !== $command_result) {
			$response_text = $command_result;

			$response = new CouchDBResponse($response_text);
			return $response;
		} else {
			return FALSE;
		}
	}

	/**
	 * 
	 * @param type $type
	 * @param type $parameter_ar
	 * @return \net\servicehome\connector\couchdb\command\Test|\net\servicehome\connector\couchdb\command\ListDatabases
	 */
	public static function createCommand($type, $parameter = null) {
		switch ($type) {
			case self::CMD__LIST_ALL_DBS:
				return new command\ListDatabases();

			case self::CMD__TEST:
				return new command\Test();

			case self::CMD__CREATE_DATABASE:
				$database_name = $parameter;
				return new command\CreateDatabase($database_name);

			case self::CMD__CREATE_DOC:
				$db_name = $parameter[0];
				$document_name = $parameter[1];
				$data = null;
				if (count($parameter) > 2) {
					$data = $parameter[2];
				}
				return new command\CreateDocument($db_name, $document_name, $data);

			default:
				return null;
		}
	}

}
