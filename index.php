<style>
	.error {
		border: 1px solid red;
		font-weight: bold;
		color: red;
	}
</style>
<?php

function dump($response) {
	echo "<br><br>Header<br>";
	var_dump($response->getHeaderArray());
	//var_dump($response->getHeader()->getStatusCode());

	echo "Body<br>";
	if ($response->isError()) {
		echo '<div class="error">';
		var_dump($response->getBody(true));
		echo '</div>';
	} else {
		var_dump($response->getBody(true));
	}

	echo "<hr>";
}

define('LOCAL_CLASS_PATH', realpath('./') . '/');
set_include_path(get_include_path() . PATH_SEPARATOR . LOCAL_CLASS_PATH);
spl_autoload_register();

use net\servicehome\connector\couchdb as couch;

$connector = new couch\CouchDB();

echo "Test Connection";
$response_test = $connector->sendCommand(couch\CouchDB::createCommand(couch\CouchDB::CMD__TEST));
dump($response_test);

echo "Create a new Database";
$response_create_db = $connector->sendCommand(couch\CouchDB::createCommand(couch\CouchDB::CMD__CREATE_DATABASE, 'test_db'));
dump($response_create_db);

echo "Delete a database";
$response_delete_db = $connector->sendCommand(new couch\command\DeleteDatabase('db_name'));
dump($response_delete_db);

echo "List all databases";
$response_list_dbs = $connector->sendCommand(couch\CouchDB::createCommand(couch\CouchDB::CMD__LIST_ALL_DBS));
dump($response_list_dbs);

echo "Try to fetch document and create/update it";
$response_get_document = $connector->sendCommand(new couch\command\GetDocument('test_db', "setup"));
$document = $response_get_document->isOk() ? new couch\data\Document($response_get_document) : couch\data\Document::create('setup');

// change documents data
$document->set('name', 'max-muster');
$document->set('geburtsjahr', 1525);
$document->set('ort', 'musterstadt');
$document->set('ORT', 'MUSTERSTADT');

// update or create document
try {
	$response_update_or_create_document = $connector->sendCommand(new couch\command\UpdateDocument("test_db", $document));
	dump($response_update_or_create_document);
} catch (Exception $exc) {
	echo '<div class="error">' . $exc->getMessage() . '</div>';
}

echo "List documents in database";
$response_list_documents_in_db = $connector->sendCommand(new couch\command\ListDocuments('test_db'));
dump($response_list_documents_in_db);

//$body_ar = $response_list_documents_in_db->getBody(true);
//var_dump($body_ar['rows']);
