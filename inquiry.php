<?php
require_once("service/SimplePdoLeadConnectService.php");
$service = new SimplePdoLeadConnectService();

$inquiry_key = $_GET['inquiry_key'];

$inquiry = $service->loadInquiry($inquiry_key);

echo $inquiry->note;

?>