<?php
header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

require_once("/home/ubuntu/workspace/include/service/SimplePdoLeadConnectService.php");
require_once("/home/ubuntu/workspace/include/entity/Inquiry.php");
require_once("/home/ubuntu/workspace/functions.php");

// if the caller pressed anything but 1 send them back
if($_REQUEST['Digits'] != '1') {
    header("Location: vendor-connect.php");
    die;
}

$inquiry_key = $_GET['inquiry-key'];
$vendor_id = $_GET['vendor-id'];

$service = new SimplePdoLeadConnectService();
$inquiry = $service->loadInquiry($inquiry_key);
$events = $service->loadEvents($inquiry->id);
saveEvent("callback", $vendor_id, $inquiry->id);
?>

<Response>
    <Say>Your call back has been scheduled. Goodbye.</Say>
</Response>

<?php
$vendor_events = groupEventsByVendor($events);

initiateCallbacks($vendor_events);
?>
