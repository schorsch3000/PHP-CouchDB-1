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
class DocumentList {

    private $document_list;
    private $count;
    private $offset;

    public function __construct(CouchDBResponse $response) {
        if ($response->isOk()) {
            $this->document_list = array();

            $data = $response->getBody(true);

            $this->count = $data['total_rows'];
            $this->offset = $data['offset'];

            if (isset($data['rows'])) {
                foreach ($data['rows'] as $documentData) {
                    $doc_id = $documentData['id'];
                    $doc_rev = $documentData['value']['rev'];

                    $data = isset($documentData['doc']) ? $documentData['doc'] : null;
                    unset($data['_id']);
                    unset($data['_rev']);

                    $_tmp = new Document();
                    $_tmp->parseData($doc_id, $doc_rev, $data);

                    $this->document_list[] = $_tmp;
                }
            }
        }
    }

    public function getCount() {
        return $this->count;
    }

    public function getOffset() {
        return $this->offset;
    }

    public function getDocuments() {
        return null === $this->document_list ? array() : $this->document_list;
    }

}
