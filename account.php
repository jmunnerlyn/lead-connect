<?php
require_once("service/SimplePdoLeadConnectService.php");
require_once("entity/Account.php");
require_once("entity/Vendor.php");

if (array_key_exists('id', $_GET) && $_GET['id']) {
    $service = new SimplePdoLeadConnectService();
    $account = $service->loadAccount($_GET['id']);
    $vendors = $service->loadAccountVendors($_GET['id']);
}
?>


<html>
<head>
<title>Account</title>
</head>
<body>
    <h1>Account</h1>
<form method="post" action="_saveAccount.php">
    <p>
    Name:<br />
    <input type="text" name="name" <?php if (array_key_exists('id', $_GET) && $_GET['id']){?>value="<?php echo $account->name;}?>"/>
    </p>
    <p>
    Host:<br />
    <input type="text" name="host" <?php if (array_key_exists('id', $_GET) && $_GET['id']){?>value="<?php echo $account->host;}?>"/>
    </p>
    <p>
    Phone:<br />
    <input type="text" name="phone" <?php if (array_key_exists('id', $_GET) && $_GET['id']){?>value="<?php echo $account->phone;}?>"/>
    </p>
    <p>
    API Key:<br />
    <input type="text" name="api_key" <?php if (array_key_exists('id', $_GET) && $_GET['id']){?>value="<?php echo $account->api_key;}?>"/>
    </p>
    <p>
    Number SID:<br />
    <input type="text" name="number_sid" <?php if (array_key_exists('id', $_GET) && $_GET['id']){?>value="<?php echo $account->number_sid;}?>"/>
    </p>
    <p>
    Greeting:<br />
    <input type="text" name="greeting" <?php if (array_key_exists('id', $_GET) && $_GET['id']){?>value="<?php echo $account->greeting;}?>"/>
    </p>
    <p>
    <?php
    if (array_key_exists('id', $_GET) && $_GET['id']) {
        ?>
        <input type="hidden" name="id" value="<?php echo $_GET['id']?>" />
    <?php
    }
    ?>
    <input type="submit" />
    </p>
</form>

<?php 
if (array_key_exists('id', $_GET) && $_GET['id']){
	?>
	<h2>Vendors</h2>
	<a href="vendor.php?account_id=<?php echo $account->id?>">Add Vendor</a>
	<?php
	if (count($vendors) > 0){
    		?>
		<table>
    		<th>Name</th>
    		<th>Phone</th>
    		<th>Email</th>
    		<th>Action</th>
    		<?php
    
		foreach ($vendors as $vendor){
        	?>
        	<tr>
            		<td><a href="vendor.php?id=<?php echo $vendor->id?>&account_id=<?php echo $account->id?>"><?php echo $vendor->name?></a></td>
            		<td><?php echo $vendor->phone?></td>
            		<td><?php echo $vendor->email?></td>
            		<td><a href="_deleteVendor.php?id=<?php echo $vendor->id?>&account_id=<?php echo $vendor->account_id?>" onclick="return confirm('Are you sure?')">Delete</a></td>
        	</tr>
        	<?php
    		}
    		?>
		</table>
		<?php
	}
}
?>
</body>
</html>
