<?php
header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

require_once("/home/ubuntu/workspace/include/service/SimplePdoLeadConnectService.php");
require_once("/home/ubuntu/workspace/include/entity/Account.php");
require_once("/home/ubuntu/workspace/include/entity/Vendor.php");
require_once("/home/ubuntu/workspace/include/entity/Prospect.php");
require_once("/home/ubuntu/workspace/include/entity/Inquiry.php");
$service = new SimplePdoLeadConnectService();

$sid = $_REQUEST['CallSid'];

if (isset($_REQUEST['To'])){
    $live = "true";
    $phone = substr($_REQUEST['From'], -10);
    $name = NULL;
    $email = NULL;
    $note = NULL;
    $account = $service->loadAccountFromPhone(substr($_REQUEST['To'], -10));
}else{
    $live = "false";
    $key = $_POST['key'];
    $phone = $_POST['phone'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $note = $_POST['note'];
    
    $account = $service->loadAccountFromKey($key);
}

$vendors = $service->loadAccountVendors($account->id);

if (!$service->loadProspectByPhone($phone, $account->id)){
    $p = new Prospect();
    $p->account_id = $account->id;
    $p->name = $name;
    $p->email = $email;
    $p->phone = $phone;

    $service->saveProspect($p);
}

$prospect = $service->loadProspectByPhone($phone, $account->id);

$inquiry_key = md5(microtime().rand());

$i = new Inquiry();
$i->inquiry_key = $inquiry_key;
$i->prospect_id = $prospect->id;
$i->status = "new";
$i->note = $note;

$service->saveInquiry($i);

require '/home/ubuntu/workspace/vendor/twilio-php-master/Services/Twilio.php';
$version = "2010-04-01";
$sid = 'AC3b2e8a3fabcbfe627c092046e3023ce4';
$token = $account->api_key;
         
$phonenumber = $account->phone; 

$name = urlencode($name);

foreach ($vendors as $vendor){
    $client = new Services_Twilio($sid, $token, $version);
    
    try {
        $call = $client->account->calls->create($phonenumber,"+1".$vendor->phone,'https://lead-connect-jamesmunnerlyn.c9.io/vendor-connect.php?phone='.$phone.'&name='.$name.'&inquiry-key='.$inquiry_key.'&vendor-id='.$vendor->id.'&live='.$live.'&sid='.$sid);
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
<Response>
    <Say>Please hold while we try to reach one of our associates.</Say>
    <Dial>
        <Conference><?php echo $sid?></Conference>
    </Dial>
</Response>