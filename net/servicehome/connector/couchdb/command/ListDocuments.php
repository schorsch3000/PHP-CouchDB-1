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

namespace net\servicehome\connector\couchdb\command;

/**
 * Description of ListDocuments
 *
 * @link http://docs.couchdb.org/en/latest/api/database/bulk-api.html
 * 
 * @author Marco Saßmannshausen <ms@servicehome.net>
 */
class ListDocuments extends \net\servicehome\connector\couchdb\CouchDBRequest {

    const OPT__INCLUDE_DOCS = 'include_docs';
    const OPT__LIMIT = 'limit';
    const OPT__SKIP = 'skip';
    const OPT__INCLUDE_END = 'inclusive_end';
    const OPT__START_KEY = 'startkey';
    const OPT__END_KEY = 'endkey';
    const OPT__DESCENDING = 'descending';

    protected static $options = array(
        self::OPT__INCLUDE_DOCS => false,
        self::OPT__LIMIT => false,
        self::OPT__SKIP => false,
        self::OPT__INCLUDE_END => true,
        self::OPT__START_KEY => false,
        self::OPT__END_KEY => false,
        self::OPT__DESCENDING => false
    );

    public function __construct($db_name) {
        $url = '/' . $db_name . '/_all_docs';
        $method = 'GET';
        $data = null;

        $options = array();
        if (($opt_value = self::$options[self::OPT__INCLUDE_DOCS])) {
            $options[] = 'include_docs=' . ($opt_value ? 'true' : 'false');
        }
        if (($opt_value = self::$options[self::OPT__LIMIT])) {
            $options[] = 'limit=' . (int) $opt_value;
        }
        if (($opt_value = self::$options[self::OPT__SKIP])) {
            $options[] = 'skip=' . (int) $opt_value;
        }
        if (($opt_value = self::$options[self::OPT__DESCENDING])) {
            $options[] = 'descending=' . ($opt_value ? 'true' : 'false');
        }

        $key_filter = false;
        if (($opt_value = self::$options[self::OPT__START_KEY])) {
            $options[] = 'startkey=' . json_encode($opt_value);
            $key_filter = true;
        }
        if (($opt_value = self::$options[self::OPT__END_KEY])) {
            $options[] = 'endkey=' . json_encode($opt_value);
            $key_filter = true;
        }
        if ($key_filter && ($opt_value = self::$options[self::OPT__INCLUDE_END])) {
            //$options[] = 'include_end=' . ($opt_value ? 'true' : 'false');
        }


        if (count($options)) {
            $url .= '?' . implode("&", $options);
        }

        echo "URL:" . $url;

        parent::__construct($url, $method, $data);
    }

    public static function changeOption($key, $value) {
        if (isset(self::$options[$key])) {
            self::$options[$key] = $value;
        }
    }

}
