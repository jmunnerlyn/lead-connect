<?php
require_once("/home/ubuntu/workspace/include/service/SimplePdoLeadConnectService.php");
require_once("/home/ubuntu/workspace/include/entity/Account.php");
$service = new SimplePdoLeadConnectService();

if (array_key_exists('id', $_POST) && $_POST['id']) {
	$account = $service->loadAccount($_POST['id']);
} else {
	$account = new Account();
}

$a = new Account();
if (array_key_exists('id', $_POST) && $_POST['id']) {
	$a->id = $_POST['id'];
}
$a->name = $_POST['name'];
$a->host = $_POST['host'];
$a->phone = $_POST['phone'];
$a->api_key = $_POST['api_key'];

if ($service->saveAccount($a)) {
	header("Location: accounts.php?message=Saved");
} else {
	header("Location: error.php");
}
?>
