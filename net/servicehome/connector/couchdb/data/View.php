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

/**
 * Description of newPHPClass
 *
 * @author Marco Saßmannshausen <ms@servicehome.net>
 */
class View extends Document implements ViewInterface {

	/**
	 * Create a new document
	 * 
	 * @param string $document_name
	 * @return \self
	 */
	public static function create($document_name) {
		$tmp = new self();
		$tmp->setId('_design/' . $document_name);
		return $tmp;
	}

	public function setId($name) {
		$this->_id = '_design/' . $name;
	}

	/**
	 * 
	 * @return type
	 * @throws \Exception
	 */
	public function getJson() {
		if (null === $this->getId()) {
			throw new \Exception("No id given!");
		}

		$data = array('_id' => $this->getId());
		if (null !== $this->getRevsion()) {
			$data['_rev'] = $this->getRevsion();
		}

		$data['language'] = 'javascript';
		$data['views'] = $this->data['views'];
		if (!is_array($data['views'])) {
			throw new Exception('No views defined!');
		}

		return json_encode($data);
	}

	public function setView($view_name, $map_reduce_ar) {
		if (!isset($this->data['views'])) {
			$this->data['views'] = array();
		}
		$views = $this->data['views'];

		$views[$view_name] = $map_reduce_ar;

		parent::set('views', $views);
	}

}

//{
//	"_id":"_design/company",
//	"_rev":"12345",
//	"language": "javascript",
//	"views":
//	{
//	"all": {
//	"map": "function(doc) { if (doc.Type == 'customer')  emit(null, doc) }"
//	},
//	"by_lastname": {
//		"map": "function(doc) { if (doc.Type == 'customer')  emit(doc.LastName, doc) }"
//		},
//		"total_purchases": {
//			"map": "function(doc) { if (doc.Type == 'purchase')  emit(doc.Customer, doc.Amount) }",
//			"reduce": "function(keys, values) { return sum(values) }"
//		}
//	}
//}