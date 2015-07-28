<?php
function groupEventsByVendor($events){
    require_once("/home/ubuntu/workspace/include/service/SimplePdoLeadConnectService.php");

    $vendor_events = array();
    
    foreach ($events as $event){
        if (array_key_exists($event->vendor_id,$vendor_events)){
            $vendor_events[$event->vendor_id] = array($event->id, $event->vendor_id, $event->inquiry_id, $event->timestamp, $event->event);
        }else{
            $vendor_events[$event->vendor_id] = array($event->id, $event->vendor_id, $event->inquiry_id, $event->timestamp, $event->event);
        }
    }
    return $vendor_events;
}  

function initiateCallbacks($vendor_events){
    require_once("/home/ubuntu/workspace/include/service/SimplePdoLeadConnectService.php");
    $service = new SimplePdoLeadConnectService();
    
    // Include the Twilio PHP library
    require '/home/ubuntu/workspace/vendor/twilio-php-master/Services/Twilio.php';
         
    // Twilio REST API version
    $version = "2010-04-01";
         
    // Set our Account SID and AuthToken
    $sid = 'AC3b2e8a3fabcbfe627c092046e3023ce4';
    
    $call_initiated = 0;
    foreach ($vendor_events as $vendor){
        if ($call_initiated == 0){
            if (in_array('callback',$vendor)){
                if (!in_array('completed',$vendor)){
                    $vendor_id = $vendor[1];
                    $inquiry_id = $vendor[2];
                    $pi = $service->loadProspectInquiry($inquiry_id);
                    $name = urlencode($pi->name);
                    $phone = $pi->phone;
                    $inquiry_key = $pi->inquiry_key;
                    $vendor = $service->loadVendor($vendor_id);
                    $account = $service->loadAccount($pi->account_id);
                    $token = $account->api_key;
                    $client = new Services_Twilio($sid, $token, $version);
    
                    $call = $client->account->calls->create($account->phone,"+1".$vendor->phone,'https://lead-connect-jamesmunnerlyn.c9.io/vendor-connect.php?phone='.$phone.'&name='.$name.'&inquiry-key='.$inquiry_key.'&vendor-id='.$vendor->id.'&callback=true');
                    $call_initiated = 1;
                }
            }   
        }
    }
}

function saveEvent($type, $vendor_id, $inquiry_id){
    require_once("/home/ubuntu/workspace/include/service/SimplePdoLeadConnectService.php");
    require_once("/home/ubuntu/workspace/include/entity/Event.php");
    $service = new SimplePdoLeadConnectService();
    $e = new Event();
    $e->event = $type;
    $e->vendor_id = $vendor_id;
    $e->inquiry_id = $inquiry_id;
    $service->saveEvent($e);
}

function scheduleCallback($params){
    ?>
    <Gather numDigits="1" action="schedule-callback.php?<?php echo $params?>" method="POST">
        <Say>To schedule a callback once the other vendor's call has ended, press 1.</Say>
    </Gather>
    <?php
}

function callProspect($phone, $params){
    ?>
    <Dial action="https://lead-connect-jamesmunnerlyn.c9.io/event.php?<?php echo $params?>">+1<?php echo $phone?></Dial>
    <Say>The call failed or the remote party hung up. Goodbye.</Say>
    <?php
}

function detectCallInProcess($vendor_events){
    $call_in_process = 0;
    foreach ($vendor_events as $vendor){
        if (in_array('accepted',$vendor)){
            if (!in_array('completed',$vendor)){
                $call_in_process = 1;
            }
        }
    }
    return $call_in_process;
}

function connectCallback($name, $account_name, $params, $live){
    if ($live == "true"){
        ?>
        <Say>Hello. This is the callback you requested for the prospect who called from <?php echo $account_name?>.</Say>    
        <?php
    }else{
        ?>
        <Say>Hello. This is the callback you requested for <?php echo urldecode($name)?> who filled out a form on <?php echo $account_name?>.</Say>    
        <?php
    }
    ?>
    <Gather numDigits="1" action="call-prospect.php?<?php echo $params?>" method="POST">
        <Say>To call <?php echo urldecode($name)?>, press 1.</Say>
    </Gather>
    <?php
}

function connectCall($name, $account_name, $params, $live){
    if ($live == "true"){
        ?>
        <Say>Hello. A prospect is on the line who called from <?php echo $account_name?>.</Say>
        <?php
    }else{
        ?>
        <Say>Hello. <?php echo urldecode($name)?> filled out a form on <?php echo $account_name?>.</Say>
        <?php
    }
    ?>
    <Gather numDigits="1" action="call-prospect.php?<?php echo $params?>" method="POST">
        <?php
        if ($live == "true"){
            ?>
            <Say>To connect to the live call, press 1.</Say>
            <?php
        }else{
            ?>
            
            <Say>To call <?php echo urldecode($name)?>, press 1.</Say>
            <?php
        }
    ?>
    </Gather>
    <?php
}

function connectConference($sid, $params){
    ?>
    <Say>Connecting.</Say>
    <Dial action="https://lead-connect-jamesmunnerlyn.c9.io/event.php?<?php echo $params?>">
        <Conference><?php echo $sid?></Conference>
    </Dial>
    <?php
}

?>
