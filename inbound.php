<?php

require_once("service/SimplePdoLeadConnectService.php");
$service = new SimplePdoLeadConnectService();
$account = $service->loadAccountFromPhone(substr($_REQUEST['To'], -10));   
$to = urlencode($_REQUEST['To']);
$from = urlencode($_REQUEST['From']);
    
header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<Response>
    <Say><?php echo $account->greeting?></Say>
    <Gather numDigits="1" action="inbound-inquiry.php&#63;To=<?php echo $to?>&amp;From=<?php echo $from?>" method="POST">
        <Say>Press 1 to speak with an associate.</Say>
    </Gather>
</Response>