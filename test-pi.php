<?php
require_once("/home/ubuntu/workspace/include/service/SimplePdoLeadConnectService.php");
require_once("/home/ubuntu/workspace/include/entity/ProspectInquiry.php");

$service = new SimplePdoLeadConnectService();
$pi = $service->loadProspectInquiry(110);
print_r($pi);
?>