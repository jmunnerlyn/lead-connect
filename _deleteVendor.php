<?php
require_once("service/SimplePdoLeadConnectService.php");
require_once("entity/Vendor.php");

if (array_key_exists('id', $_GET) && $_GET['id']) {
    $service = new SimplePdoLeadConnectService();

    if ($service->deleteVendor($_GET['id'])) {
	    header("Location: account.php?id=" . $_GET['account_id'] . "&message=Deleted");
    } else {
    	header("Location: error.php");
    }
}
?>