<?php
header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

require_once("service/SimplePdoLeadConnectService.php");
require_once("entity/Inquiry.php");
require_once("entity/Event.php");
require_once("functions.php");

$sid = $_GET['sid'];
$live = $_GET['live'];
$phone = $_GET['phone'];
$name = $_GET['name'];
$inquiry_key = $_GET['inquiry-key'];
$vendor_id = $_GET['vendor-id'];
$params = "phone=$phone&amp;vendor-id=$vendor_id&amp;inquiry-key=$inquiry_key&amp;sid=$sid&amp;live=$live";

$service = new SimplePdoLeadConnectService();
$vendor = $service->loadVendor($vendor_id);
$account = $service->loadAccount($vendor->account_id);
$inquiry = $service->loadInquiry($inquiry_key);
$events = $service->loadEvents($inquiry->id);
?>

<Response>
    <?php
    if (count($events) > 0){
        $vendor_events = groupEventsByVendor($events);
        $call_in_process = detectCallInProcess($vendor_events);
        
        if ($call_in_process == 1){
            if ($live == "true"){
                ?>
                <Say>Hello. Someone just called from <?php echo $account->name?>, but another vendor has already connected to the caller.</Say>
                <?php
            }else{
                ?>
                <Say>Hello. <?php echo urldecode($name)?> filled out a form on <?php echo $account->name?>, but another vendor has already connected a call.</Say>
                <?php   
            }
            scheduleCallback($params);
        }else{
            if (isset($_GET['callback'])){
                connectCallback($name, $account->name, $params, $live);
            }else{
                connectCall($name, $account->name, $params, $live);
            }
        }
    }else{
        if (isset($_GET['callback'])){
            connectCallback($name, $account->name, $params, $live);
        }else{
            connectCall($name, $account->name, $params, $live);
        }
    }
    ?>
</Response>