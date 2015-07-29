<?php
header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

require_once("service/SimplePdoLeadConnectService.php");
require_once("entity/Event.php");
require_once("functions.php");

// if the caller pressed anything but 1 send them back
if($_REQUEST['Digits'] != '1') {
    header("Location: vendor-connect.php");
    die;
}

$live = $_GET['live'];
$sid = $_GET['sid'];
$phone = $_GET['phone'];
$inquiry_key = $_GET['inquiry-key'];
$vendor_id = $_GET['vendor-id'];
$params = "phone=$phone&amp;vendor-id=$vendor_id&amp;inquiry-key=$inquiry_key&amp;sid=$sid&amp;live=$live";

$service = new SimplePdoLeadConnectService();
$inquiry = $service->loadInquiry($inquiry_key);
$events = $service->loadEvents($inquiry->id);
?>

<Response>
    <?php
    if (count($events) > 0){
        
        $vendor_events = groupEventsByVendor($events);
        
        $call_in_process = detectCallInProcess($vendor_events);

        if ($call_in_process == 1){
            ?>
            <Say>Sorry. Another vendor has just connected a call to the prospect.</Say>
            <?php
            scheduleCallback($params);
        }else{
            saveEvent("accepted", $vendor_id, $inquiry->id);
            if ($live == "true"){
                connectConference($sid, $params);
            }else{
                callProspect($phone, $params);   
            }
        }
    }else{
        saveEvent("accepted", $vendor_id, $inquiry->id);
        if ($live == "true"){
            connectConference($sid, $params);
        }else{
            callProspect($phone, $params);   
        }
    }
    ?>
</Response>