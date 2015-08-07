<?php
require_once("service/SimplePdoLeadConnectService.php");
$service = new SimplePdoLeadConnectService();

$prospects = $service->loadAccountProspects($_GET['account_id']);

$vendors = $service->loadAccountVendors($_GET['account_id']);
?>
<html>
    <head>
        <title>Inquiries</title>
    </head>
    <body>
        <h1>Inquiries</h1>
        <table>
            <th>Date</th>
            <th>Phone</th>
            <th>Accepted</th>
            <th>Callback</th>
            <th>Completed</th>
            <th>Name</th>
            <th>Note</th>
            <?php
            foreach ($prospects as $prospect){
                $inquiries = $service->loadProspectInquiries($prospect->id);
                foreach ($inquiries as $inquiry){
                    ?>
                    <tr>
                        <td><?php echo $inquiry->timestamp?></td>
                        <td><a href="inquiry.php?inquiry_key=<?php echo $inquiry->key?>"><?php echo $prospect->phone?></a></td>
                        <?php
                        $accepted_count = 0;
                        $callback_count = 0;
                        $answered_count = 0;
                        $completed_count = 0;
                        
                        $events = $service->loadEvents($inquiry->id);
                            foreach ($events as $event){
                                if ($event->event == "accepted"){
                                    $accepted_count ++;
                                }
                                
                                if ($event->event == "callback"){
                                    $callback_count ++;
                                }
                                
                                if ($event->event == "answered"){
                                    $answered_count ++;
                                }
                                
                                if ($event->event == "completed"){
                                    $completed_count ++;
                                }
                            }
                        ?>
                        <td><?php echo number_format($accepted_count / count($vendors) * 100) . "%"?></td>
                        <td><?php echo number_format($callback_count / count($vendors) * 100) . "%"?></td>
                        <td><?php echo number_format(($answered_count + $completed_count) / count($vendors) * 100) . "%"?></td>
                        <td><?php echo $prospect->name?></td>
                        <td><?php echo $inquiry->note?></td>
                        
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
    </body>
</html>