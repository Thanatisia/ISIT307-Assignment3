<!--
    Registration Form    
-->
<?php
    if(isset($_POST["Register"]))
    {
        // Submit (Register) button is pressed

        $message = "<ul>";    // For Displaying
        $chk_valid_username = False;
        $chk_valid_password = False;
        $chk_valid_name = False;
        $chk_valid_surname = False;
        $chk_valid_phone = False;
        $chk_valid_email = False;

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

        if(!strlen($_POST['name']) > 0)
        {
            // Empty Value
            $message .= "<li>You forgot to enter your Name</li>";
        }
        else
        {
            $chk_valid_name = True;
        }

        if(!strlen($_POST['surname']) > 0)
        {
            // Empty Value
            $message .= "<li>You forgot to enter your Surname</li>";
        }
        else
        {
            $chk_valid_surname = True;
        }

        if(!strlen($_POST['phone']) > 0)
        {
            // Empty Value
            $message .= "<li>You forgot to enter your Phone Number</li>";
        }
        else
        {
            $chk_valid_phone = True;
        }

        if(!strlen($_POST['email']) > 0)
        {
            // Empty Value
            $message .= "<li>You forgot to enter your Email Address</li>";
        }
        else
        {
            $chk_valid_email = True;
        }

        $message .= "</ul>";

        // Check for errors
        $errors_Found = True;
        if($chk_valid_username && $chk_valid_password && $chk_valid_name && $chk_valid_surname && $chk_valid_email && $chk_valid_phone)
        {
            // All OK
            $errors_Found = False; 
            // header("refresh: 0, url=process_register.php");
            header("Location: process_register.php", TRUE, 307);    // True and permission 307 will keep $_POST data while redirecting
        }
    }
?>

<html>
	<head>
        <title>Registration Page</title>
        <link href="./assets/styles/css/index.css" type="text/stylesheet" rel="stylesheet"/>
	</head>

	<body>
        <?php include("./header.inc.php"); ?> <!-- Include Header -->

        <hr/>

        <h1>InfoTech Services</h1>

        <hr/>

        <div>
            <p>Registration</p>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <!--
                    Login Form
                -->
                <div> Login Details <br/>
                    Username : <input type="text" name="username" value="<?php if(isset($_POST['username'])) echo $_POST['username']; ?>" placeholder="Your username here">
                    Password : <input type="password" name="password" value="<?php if(isset($_POST['password'])) echo $_POST['password']; ?>" placeholder="Your password here">
                </div>

                <br/>

                <div> Profile Information <br/>
                    Name     : <input type="text" name="name" value="<?php if(isset($_POST['name'])) echo $_POST['name']; ?>" placeholder="Your First Name here"> <br/>
                    Surname  : <input type="text" name="surname" value="<?php if(isset($_POST['surname'])) echo $_POST['surname']; ?>" placeholder="Your surname here"> <br/>
                    Phone    : <input type="tel" name="phone" value="<?php if(isset($_POST['phone'])) echo $_POST['phone']; ?>" placeholder="Your Phone Number here"> <br/>
                    Email    : <input type="email" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>" placeholder="Your email address here"> <br/>
                </div>
                <input type="submit" name="Register" value="Register">
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

