<?php

	
if(empty($_REQUEST['method'])) {
	header('HTTP/1.1 400 Bad Request');
	$response = "method is required.";
	$json = getJSONobj();
	echo $json->encode($response);
}


//load license validation config
require_once('modules/'.$currentModule.'/license/htKanbanOutfittersLicense.php');

if($_REQUEST['method'] == 'validate') {
	htKanbanOutfittersLicense::validate();
} else if($_REQUEST['method'] == 'change') {
	htKanbanOutfittersLicense::change();
}
