<html>
    <head>
        <title>Thank You</title>
    </head>
    <body>
        <h1>Thank You</h1>
        <p>We'll get in touch with you ASAP.</p>
    </body>
</html>
<?php
//set POST variables
$url = 'http://'.$_SERVER["HTTP_HOST"].'/inbound-inquiry.php';
$fields = array(
            // Add the fields you want to pass through
            // Remove stripslashes if get_magic_quotes_gpc() returns 0.
            'phone'=>urlencode(stripslashes($_POST['phone'])),
            'number_sid'=>urlencode(stripslashes($_POST['number_sid'])),
            'name'=>urlencode(stripslashes($_POST['name'])),
            'note'=>urlencode(stripslashes($_POST['note'])),
            'email'=>urlencode(stripslashes($_POST['email']))
        );

//url-ify the data for the POST
$fields_string = NULL;
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string,'&');

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_POST,count($fields));

// returns the response as a string instead of printing it
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);

echo $result;
?>
