<?php

define('LOCAL_CLASS_PATH', realpath('./') . '/');
set_include_path(get_include_path() . PATH_SEPARATOR . LOCAL_CLASS_PATH);
spl_autoload_register();

use net\servicehome\connector\couchdb as couch;

$connector = new couch\CouchDB();

// CREATE DB
////////////////////////
$db_name = "test_db";
$response = $connector->sendCommand(new couch\command\CreateDatabase($db_name));
var_dump($response);

// CREATE sample entries
////////////////////////
$document = couch\data\Document::create("entry_a");
$document->set("Art", "Obst");
$document->set("Name", "Orange");
$document->set("Preis", 1.5);

$response = $connector->sendCommand(couch\command\CreateDocument::initWithDoc($db_name, $document));
var_dump($response);

$document->setId("entry_b");
$document->resetRevision();
$document->set("Art", "Obst");
$document->set("Name", "Apfel");
$document->set("Preis", 1.2);
$response = $connector->sendCommand(couch\command\CreateDocument::initWithDoc($db_name, $document));
var_dump($response);

$document->setId("entry_c");
$document->resetRevision();
$document->set("Art", "Obst");
$document->set("Name", "Birne");
$document->set("Preis", 0.9);
$response = $connector->sendCommand(couch\command\CreateDocument::initWithDoc($db_name, $document));
var_dump($response);


$document->setId("entry_d");
$document->resetRevision();
$document->set("Art", "Gemüse");
$document->set("Name", "Möhre");
$document->set("Preis", 0.2);
$response = $connector->sendCommand(couch\command\CreateDocument::initWithDoc($db_name, $document));
var_dump($response);

$document->setId("entry_e");
$document->resetRevision();
$document->set("Art", "Gemüse");
$document->set("Name", "Tomate");
$document->set("Preis", 0.4);
$response = $connector->sendCommand(couch\command\CreateDocument::initWithDoc($db_name, $document));
var_dump($response);

// CREATE a view
////////////////////////
$view = new couch\data\View();
$view->setId("ansichten");
$view->setView("Obst", array(
	'map' => "function(doc) { if (doc.Art == 'Obst')  emit(doc.Art, doc.Preis ) }",
	'reduce' => "function (key, values) { return sum(values) }"
));
$view->setView("Gemüse", array(
	'map' => "function(doc) { if (doc.Art == 'Gemüse')  emit(doc.Art, {'Name':doc.Name, 'Preis':doc.Preis} ) }",
));
$response = $connector->sendCommand(new couch\command\CreateView($db_name, $view));
var_dump($response);
