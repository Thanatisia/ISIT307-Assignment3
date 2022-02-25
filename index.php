<!--
    ISIT307 Web Server Programming Assignment 3
-->

<?php
    // Start Session for use
    session_start();

    if(isset($_POST["logout"]) || !isset($_SESSION["role"]))
    {
        // Close Session if logout button is pressed
        session_destroy();
    }

    // Get SESSION values
    //$u_name = "";
    //if(isset($_SESSION["username"]))
    //{
        //echo "<script>alert('" . $_SESSION["username"] . "');</script>";
        //$u_name = $_SESSION["username"];
        //$u_role = $_SESSION["role"];
    //}
?>

<html>
	<head>
        <title>Index Page</title>
        <link href="./assets/styles/css/index.css" type="text/stylesheet" rel="stylesheet"/>
	</head>

	<body>
        <?php include("./header.inc.php"); ?> <!-- Include Header -->

        <hr/>

        <h1>IT for rent</h1>

        <?php
            /*
             * If Logged In
             */
            
            if($u_firstname == "")
            {
                echo "<h3>Welcome!</h3>" . 
                     "<br/>" . 
                     "<p> Please Register or Sign In to access the site!" . "<br/>" . "</p>";
            }
            else
            {
                echo "<h3>Welcome! $u_firstname $u_surname</h3>";
            }
        ?>

        <hr/>

        <?php include("./footer.inc.php"); ?> <!-- Include Footer -->
	</body>
</html>

