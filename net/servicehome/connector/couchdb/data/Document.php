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

namespace net\servicehome\connector\couchdb\data;

use net\servicehome\connector\couchdb\CouchDBResponse;

/**
 * Description of newPHPClass
 *
 * @author Marco Saßmannshausen <ms@servicehome.net>
 */
class Document {

	private $_id;
	private $_rev;
	private $data;

	public function __construct(CouchDBResponse $response = null) {
		$this->_id = null;
		$this->_rev = null;
		$this->data = null;
		if (null !== $response) {
			$this->parse($response);
		}
	}

	/**
	 * Create a new document
	 * 
	 * @param string $document_name
	 * @return \self
	 */
	public static function create($document_name) {
		$tmp = new self();
		$tmp->setId($document_name);
		return $tmp;
	}

	public function parse(CouchDBResponse $response) {
		$body_array = $response->getBody(true);
		foreach ($body_array as $key => $value) {
			if (in_array($key, array('_id', '_rev'))) {
				$this->{$key} = $value;
			} else {
				$this->data[$key] = $value;
			}
		}
	}

	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	public function setId($name) {
		$this->_id = $name;
	}

	public function getId() {
		return $this->_id;
	}

	public function getRevsion() {
		return $this->_rev;
	}

	public function getData() {
		return $this->data;
	}

	public function getJson() {
		if (null === $this->getId()) {
			throw new \Exception("No id given!");
		}

		$data = array('_id' => $this->getId());
		if (null !== $this->getRevsion()) {
			$data['_rev'] = $this->getRevsion();
		}

		foreach ($this->data as $key => $value) {
			$data[$key] = $value;
		}

		return json_encode($data);
	}

}
