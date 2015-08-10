<?php

define('LOCAL_CLASS_PATH', realpath('./') . '/');
set_include_path(get_include_path() . PATH_SEPARATOR . LOCAL_CLASS_PATH);
spl_autoload_register();

use net\servicehome\connector\couchdb as couch;

$connector = new couch\CouchDB();

//$view = new couch\data\View();
//$view->setId('test');
//$view->setView('with_test', array('map' => "function(doc) { if (doc.Type == 'Gemüse')  emit(doc.id, doc) }"));
//$response = $connector->sendCommand(new couch\command\CreateView('test_db', $view));
//var_dump($response);

$response = $connector->sendCommand(new couch\command\GetView("test_db", 'test'));
if ($response->isOk()) {
	$view = new couch\data\View($response);

	$view->setView('reduce_example', array(
		'map' => "function(doc) { if (doc.Art == 'Obst')  emit(doc.Art, doc.Preis ) }",
		'reduce' => "function (key, values) { return sum(values) }"
			)
	);

	$response_update = $connector->sendCommand(new couch\command\UpdateView('test_db', $view));

	var_dump($response_update);
} else {
	var_dump($response);
}



