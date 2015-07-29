<?php
require_once("service/SimplePdoLeadConnectService.php");
require_once("entity/Vendor.php");

if (array_key_exists('id', $_GET) && $_GET['id']) {
    $service = new SimplePdoLeadConnectService();
    $vendor = $service->loadVendor($_GET['id']);
}
?>


<html>
<head>
<title>Vendor</title>
</head>
<body>
    <h1>Vendor</h1>
<form method="post" action="_saveVendor.php">
    <p>
    Name:<br />
    <input type="text" name="name" <?php if (array_key_exists('id', $_GET) && $_GET['id'])?>value="<?php echo $vendor->name?>"/>
    </p>
    Phone:<br />
    <input type="text" name="phone" <?php if (array_key_exists('id', $_GET) && $_GET['id'])?>value="<?php echo $vendor->phone?>"/>
    </p>
    </p>
    Email:<br />
    <input type="text" name="email" <?php if (array_key_exists('id', $_GET) && $_GET['id'])?>value="<?php echo $vendor->email?>"/>
    </p>
    <input type="hidden" name="account_id" value="<?php echo $_GET['account_id']?>" />
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
</body>
</html>