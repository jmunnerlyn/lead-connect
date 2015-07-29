<?php //echo $_SERVER['SERVER_NAME'];?>
<html>
    <head>
        <title>
            Test Form
        </title>
    </head>
    <body>
        <h1>Test Form</h1>
        <form action="http://<?php echo $_SERVER['HTTP_HOST']?>/test-thank-you.php" method="post">
            <p>Name: 
            <input type="text" name="name" />
            </p>
            <p>Phone
            <input type="text" name="phone" />
            </p>
            <p>Email
            <input type="text" name="email" />
            </p>
            <p>Note:
            <textarea name="note">
                
            </textarea>
            </p>
            <input type="hidden" name="key" value="7a64549301ead0ae9fcfdc6f4d5cd5f4" />
            <input type="hidden" name="host" value="<?php $_SERVER['REQUEST_URI']?>" />
            <input type="submit" />
        </form>
    </body>
</html>