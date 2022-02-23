<!--
    Login Page
-->
<?php
    if(isset($_POST["Login"]))
    {
        // Submit (Register) button is pressed

        $message = "<ul>";    // For Displaying
        $chk_valid_username = False;
        $chk_valid_password = False;

        // Check for empty values
        if(!strlen($_POST['username']) > 0)
        {
            // Empty Value
            $message .= "<li>You forgot to enter your username</li>";
        }
        else
        {
            $chk_valid_username = True;
        }

        if(!strlen($_POST['password']) > 0)
        {
            // Empty Value
            $message .= "<li>You forgot to enter your password</li>";
        }
        else
        {
            $chk_valid_password = True;
        }

        $message .= "</ul>";

        // Check for errors
        $errors_Found = True;
        if($chk_valid_username && $chk_valid_password)
        {
            // All OK
            $errors_Found = False; 
            // header("refresh: 0, url=process_register.php");
            header("Location: process_login.php", TRUE, 307);    // True and permission 307 will keep $_POST data while redirecting
        }
    }

?>

<html>
	<head>
        <title>Login Page</title>
        <link href="./assets/styles/css/index.css" type="text/stylesheet" rel="stylesheet"/>
	</head>

	<body>
        <?php include("./header.inc.php"); ?> <!-- Include Header -->

        <hr/>

        <h1>InfoTech Services</h1>

        <hr/>

        <div>
            <p>Login</p>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <!--
                    Login Form
                -->
                Username : <input type="text" name="username" value="<?php if(isset($_POST['username'])) echo $_POST['username']; ?>" placeholder="Your username here">
                Password : <input type="password" name="password" value="<?php if(isset($_POST['password'])) echo $_POST['password']; ?>" placeholder="Your password here">
                <input type="submit" name="Login" value="Login">
            </form>

            <div id="errors">
                <?php
                    if(isset($errors_Found))
                    {
                        if($errors_Found)
                        {
                            echo "Errors: <br/>";
                            echo "$message";
                        }
                    }
                ?>
            </div>
        </div>

        <hr/>

        <?php include("./footer.inc.php"); ?> <!-- Include Footer -->
	</body>
</html>

