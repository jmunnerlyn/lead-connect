<?php
header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

require_once("service/SimplePdoLeadConnectService.php");
require_once("entity/Account.php");
require_once("entity/Vendor.php");
require_once("entity/Prospect.php");
require_once("entity/Inquiry.php");
$service = new SimplePdoLeadConnectService();

if (isset($_REQUEST['To'])){
	$sid = $_REQUEST['CallSid'];
    $live = "true";
    $phone = substr($_REQUEST['From'], -10);
    $name = NULL;
    $email = NULL;
    $note = NULL;
    $account = $service->loadAccountFromPhone(substr($_REQUEST['To'], -10));
}else{
    $live = "false";
    $number_sid = $_POST['number_sid'];
    $phone = $_POST['phone'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $note = $_POST['note'];
    
    $account = $service->loadAccountFromNumberSid($number_sid);
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

require 'twilio-php-master/Services/Twilio.php';
$version = "2010-04-01";
$account_sid = 'AC3b2e8a3fabcbfe627c092046e3023ce4';
$token = '7a64549301ead0ae9fcfdc6f4d5cd5f4';
         
$phonenumber = $account->phone; 

$name = urlencode($name);

foreach ($vendors as $vendor){
    $client = new Services_Twilio($account_sid, $token, $version);
    
    try {
        $call = $client->account->calls->create($phonenumber,'+1'.$vendor->phone,'http://'.$_SERVER["HTTP_HOST"].'/vendor-connect.php?phone='.$phone.'&name='.$name.'&inquiry-key='.$inquiry_key.'&vendor-id='.$vendor->id.'&live='.$live.'&sid='.$sid);
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
