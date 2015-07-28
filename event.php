<?php
header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

require_once("/home/ubuntu/workspace/include/service/SimplePdoLeadConnectService.php");
require_once("/home/ubuntu/workspace/include/entity/Event.php");
require_once("/home/ubuntu/workspace/functions.php");

$service = new SimplePdoLeadConnectService();
$inquiry = $service->loadInquiry($_GET['inquiry-key']);

saveEvent($_POST['DialCallStatus'], $_GET['vendor-id'], $inquiry->id);

$events = $service->loadEvents($inquiry->id);

$vendor_events = groupEventsByVendor($events);

initiateCallbacks($vendor_events);

?>
<Response>
    <Hangup />
</Response>