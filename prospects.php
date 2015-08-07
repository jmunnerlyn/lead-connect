<?php
require_once("service/SimplePdoLeadConnectService.php");
require_once("entity/Prospect.php");
$service = new SimplePdoLeadConnectService();

$prospects = $service->loadAccountProspects($_GET['account_id']);
?>
<html>
    <head>
        <title>Prospects</title>
    </head>
    <body>
        <h1>Prospects</h1>
        <?php
        foreach ($prospects as $prospect){
            echo $prospect->phone . "<br />";
        }
        ?>
    </body>
</html>