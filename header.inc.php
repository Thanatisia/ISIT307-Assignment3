<?php
    // Start Session for use
    //session_start();

    // Check button press
    if(isset($_POST["Logout"]))
    {
        // Logout button pressed
        header("Location: process_logout.php"); 
    }

    // Check Sessions
    $role= "";
    $u_name = "";
    if(isset($_SESSION["username"]) && isset($_SESSION["role"]))
    {
        $role = $_SESSION["role"];
        $u_name = $_SESSION["username"];
    }   
?>
<html>
	<head>
		<title>Header</title>
	</head>

    <body>
        <div>
            <a href="./index.php">Home Page</a> | 
            <?php
                // Role-specific
                if(!$role == "")
                {
                    if($role == "admin")
                    {
                        echo "<a href='admin.php'>Admin</a>";
                    }
                    elseif($role == "client")
                    {
                        echo "<a href='my_account.php'>My Account</a>";
                    }

                    echo " | ";
                }

                // Session-specific
                if(isset($_SESSION["username"]))
                {
                    // Is Logged In
                    echo "<a href='process_logout.php'>Logout</a>";
                }
                else
                {
                    // Not Logged In
                    echo "<a href='login.php'>Login</a>";

                    echo " | ";

                    echo "<a href='register.php'>Register</a>";
                }

                echo " | ";
            ?>

            <!-- form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="submit" name="Logout" value="Logout">
            </form-->
        </div>
	</body>
</html>

