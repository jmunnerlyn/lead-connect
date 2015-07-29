<?php
require_once("service/SimplePdoLeadConnectService.php");
require_once("entity/Prospect.php");
$service = new SimplePdoLeadConnectService();

if (array_key_exists('id', $_POST) && $_POST['id']) {
	$prospect = $service->loadProspect($_POST['id']);
} else {
	$prospect = new Prospect();
}

$p = new Prospect();
if (array_key_exists('id', $_POST) && $_POST['id']) {
	$p->id = $_POST['id'];
}
$p->name = $_POST['name'];
$p->email = $_POST['email'];
$p->phone = $_POST['phone'];

if ($service->saveProspect($p)) {
	header("Location: accounts.php?message=Saved");
} else {
	header("Location: error.php");
}
?>
