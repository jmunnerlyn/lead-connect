<?php
require_once("service/SimplePdoLeadConnectService.php");
require_once("entity/Account.php");

if (array_key_exists('id', $_GET) && $_GET['id']) {
    $service = new SimplePdoLeadConnectService();

    if ($service->deleteAccount($_GET['id'])) {
	    header("Location: accounts.php?message=Deleted");
    } else {
    	header("Location: error.php");
    }
}
?>