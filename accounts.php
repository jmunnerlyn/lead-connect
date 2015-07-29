<?php
require_once("service/SimplePdoLeadConnectService.php");
require_once("entity/Account.php");

$service = new SimplePdoLeadConnectService;

$accounts = $service->loadAccounts();

?>

<html>
    <head>
        <title></title>
    </head>
    <body>
        <?php
        if (array_key_exists('message', $_GET) && $_GET['message']) {
	        echo "<p>" . $_GET['message'] . "</p>";
        }
        ?>
        <h1>Accounts</h1>
        <a href="account.php">Create Account</a>
        <?php
        if (count($accounts) > 0 ){
            
            ?>    
            <table>
                <th>Name</th>
                <th>Host</th>
                <th>Phone</th>
                <th>API Key</th>
                <th>Action</th>
            <?php
            foreach ($accounts as $account){
                ?>
                <tr>
                    <td><a href="account.php?id=<?php echo $account->id;?>"><?php echo $account->name;?></a></td>
                    <td><?php echo $account->host?></td>
                    <td><?php echo $account->phone?></td>
                    <td><?php echo $account->api_key?></td>
                    <td><a href="_deleteAccount.php?id=<?php echo $account->id?>" onclick="return confirm('Are you sure?')">Delete</a></td>
                </tr>
                <?php
            }
        }
        ?>
        </table>
    </body>
</html>