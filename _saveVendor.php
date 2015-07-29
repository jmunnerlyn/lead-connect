<?php
require_once("service/SimplePdoLeadConnectService.php");
require_once("entity/Vendor.php");
$service = new SimplePdoLeadConnectService();

if (array_key_exists('id', $_POST) && $_POST['id']) {
	$vendor = $service->loadVendor($_POST['id']);
} else {
	$vendor = new Vendor();
}

$v = new Vendor();
if (array_key_exists('id', $_POST) && $_POST['id']) {
	$v->id = $_POST['id'];
}
$v->account_id = $_POST['account_id'];
$v->name = $_POST['name'];
$v->phone = $_POST['phone'];
$v->email = $_POST['email'];

if ($service->saveVendor($v)) {
	header("Location: account.php?id=" . $_POST['account_id'] . "&message=Saved");
} else {
	header("Location: error.php");
}
?>
